<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\AccessCode;
use Illuminate\Http\Request;

class AccessCodeController extends Controller
{
    public function index()
    {
        $student  = auth()->user()->student;
        $usages   = $student->accessCodeUsages()->with('code.lectures')->latest()->get();

        return view('portal.codes', compact('usages'));
    }

    public function redeem(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:32',
        ], [
            'code.required' => 'أدخل الكود أولاً.',
        ]);

        $student = auth()->user()->student;
        $code    = AccessCode::where('code', strtoupper(trim($request->code)))
            ->with('lectures')
            ->first();

        if (!$code) {
            return back()->withErrors(['code' => 'الكود غير موجود. تأكد من كتابته بشكل صحيح.'])->withInput();
        }

        if ($code->type !== 'lecture') {
            return back()->withErrors(['code' => 'هذا الكود خاص بالتسجيل ولا يمكن استخدامه هنا.'])->withInput();
        }

        if ($reason = $code->unusableReason()) {
            return back()->withErrors(['code' => $reason])->withInput();
        }

        if ($code->usages()->where('student_id', $student->id)->exists()) {
            return back()->withErrors(['code' => 'لقد استخدمت هذا الكود من قبل.'])->withInput();
        }

        $code->grantTo($student);

        $lectureTitles = $code->lectures->pluck('title')->implode('، ');

        return redirect()->route('portal.codes')
            ->with('success', "تم! فُتحت لك المحاضرات: {$lectureTitles}");
    }
}
