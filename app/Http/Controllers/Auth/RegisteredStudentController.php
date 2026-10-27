<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AccessCode;
use App\Models\Lecture;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisteredStudentController extends Controller
{
    public function create()
    {
        if (!session()->has('errors') && !session()->hasOldInput()) {
            $this->forceCleanSession();
        }

        return view('auth.register');
    }

    public function store(Request $request)
    {
        $this->forceCleanSession();

        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|max:255|unique:users,email|unique:students,email',
            'phone'        => 'required|string|max:20',
            'address'      => 'required|string|max:255',
            'parent_phone' => 'required|string|max:20',
            'level'        => 'required|in:first,second,third',
            'access_code'  => 'nullable|string|max:32',
            'password'     => 'required|string|min:8|confirmed',
        ], [
            'name.required'         => 'الاسم الكامل مطلوب.',
            'email.required'        => 'البريد الإلكتروني مطلوب.',
            'email.email'           => 'صيغة البريد الإلكتروني غير صحيحة.',
            'email.unique'          => 'هذا البريد الإلكتروني مسجل مسبقاً.',
            'phone.required'        => 'رقم الهاتف مطلوب.',
            'address.required'      => 'العنوان مطلوب.',
            'parent_phone.required' => 'رقم ولي الأمر مطلوب.',
            'level.required'        => 'الرجاء اختيار الصف الدراسي.',
            'level.in'              => 'الصف الدراسي المختار غير صحيح.',
            'password.required'     => 'كلمة المرور مطلوبة.',
            'password.min'          => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل.',
            'password.confirmed'    => 'تأكيد كلمة المرور غير متطابق.',
        ]);

        // Optional invite code: when provided it must be a valid,
        // active registration code with uses remaining.
        $accessCode = null;
        if ($request->filled('access_code')) {
            $accessCode = AccessCode::where('code', strtoupper(trim($request->access_code)))->first();

            if (!$accessCode || $accessCode->type !== 'registration') {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'access_code' => 'كود الدعوة غير صحيح. تأكد من كتابته أو اترك الحقل فارغاً.',
                ]);
            }

            if ($reason = $accessCode->unusableReason()) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'access_code' => $reason,
                ]);
            }
        }

        // Create the student record first
        $student = Student::create([
            'name'         => $request->name,
            'email'        => $request->email,
            'phone'        => $request->phone,
            'address'      => $request->address,
            'parent_phone' => $request->parent_phone,
            'enrolled_at'  => now()->toDateString(),
            'status'       => 'active',
            'level'        => $request->level, // default — admin can change later
        ]);

        // Create linked user account
        User::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'role'       => 'student',
            'student_id' => $student->id,
        ]);

        // Auto-enroll into every published lecture matching this student's
        // level, plus any lecture marked "all levels".
        $lectureIds = Lecture::where('status', 'published')
            ->where(function ($query) use ($student) {
                $query->where('level', $student->level)
                      ->orWhere('level', 'all');
            })
            ->pluck('id');

        if ($lectureIds->isNotEmpty()) {
            $student->lectures()->syncWithoutDetaching($lectureIds);
        }

        // Record invite-code usage so the admin sees who registered with it.
        if ($accessCode !== null) {
            $accessCode->grantTo($student);
        }

        return redirect('/login')
            ->with('status', 'تم التسجيل بنجاح! يمكنك الآن تسجيل الدخول.')
            ->withCookie(cookie()->forget(config('session.cookie')));
    }

    /**
     * Forcibly clear any authenticated session before showing/processing
     * the registration form. On some shared hosts (file-based sessions),
     * a plain auth()->logout() + invalidate() can leave a stale session
     * cookie that causes the NEXT page (e.g. /login) to think the user
     * is still authenticated and bounce them elsewhere. This is a
     * belt-and-suspenders version that also drops the session cookie itself.
     */
    private function forceCleanSession(): void
    {
        if (auth()->check()) {
            auth()->logout();
        }

        if (request()->hasSession()) {
            request()->session()->flush();
            request()->session()->regenerate(true);
        }
    }
}