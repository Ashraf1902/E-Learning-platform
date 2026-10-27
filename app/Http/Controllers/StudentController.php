<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Lecture;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::withCount('lectures')->latest();

        // Filter by level
        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        $students = $query->paginate(10)->withQueryString();
        return view('students.index', compact('students'));
    }

    public function create()
    {
        $lectures = Lecture::where('status', 'published')->get();
        return view('students.create', compact('lectures'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:students,email',
            'phone'       => 'nullable|string|max:20',
            'enrolled_at' => 'nullable|date',
            'status'      => 'required|in:active,inactive',
            'level'       => 'required|in:first,second,third',
            'lectures'    => 'nullable|array',
            'lectures.*'  => 'exists:lectures,id',
        ]);

        $student = Student::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'phone'       => $request->phone,
            'enrolled_at' => $request->enrolled_at,
            'status'      => $request->status,
            'level'       => $request->level,
        ]);

        if ($request->has('lectures') && !empty($request->lectures)) {
            $student->lectures()->attach($request->lectures);
        }

        // Create user account for student
        \App\Models\User::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => bcrypt('password'),
            'role'       => 'student',
            'student_id' => $student->id,
        ]);

        return redirect()->route('students.index')
            ->with('success', 'تم إضافة الطالب بنجاح! كلمة المرور الافتراضية: password');
    }

    public function show(Student $student)
    {
        $student->load(['lectures', 'quizAttempts.quiz']);
        $quizzes = Quiz::where('status', 'published')->get();
        return view('students.show', compact('student', 'quizzes'));
    }

    public function edit(Student $student)
    {
        $lectures    = Lecture::where('status', 'published')->get();
        $enrolledIds = $student->lectures->pluck('id')->toArray();
        return view('students.edit', compact('student', 'lectures', 'enrolledIds'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:students,email,' . $student->id,
            'phone'       => 'nullable|string|max:20',
            'enrolled_at' => 'nullable|date',
            'status'      => 'required|in:active,inactive',
            'level'       => 'required|in:first,second,third',
            'lectures'    => 'nullable|array',
        ]);

        $student->update([
            'name'        => $request->name,
            'email'       => $request->email,
            'phone'       => $request->phone,
            'enrolled_at' => $request->enrolled_at,
            'status'      => $request->status,
            'level'       => $request->level,
        ]);

        $student->lectures()->sync($request->lectures ?? []);

        // Update linked user account email/name too
        $user = \App\Models\User::where('student_id', $student->id)->first();
        if ($user) {
            $user->update(['name' => $request->name, 'email' => $request->email]);
        }

        return redirect()->route('students.index')
            ->with('success', 'تم تحديث بيانات الطالب بنجاح!');
    }

    public function destroy(Student $student)
    {
        // Delete linked user account too
        \App\Models\User::where('student_id', $student->id)->delete();
        $student->delete();
        return redirect()->route('students.index')
            ->with('success', 'تم حذف الطالب.');
    }

    // ── Reset student quiz attempt ──
    public function resetAttempt(Student $student, Quiz $quiz)
    {
        QuizAttempt::where('student_id', $student->id)
            ->where('quiz_id', $quiz->id)
            ->delete();

        return redirect()->back()
            ->with('success', 'تم إعادة تعيين محاولة الطالب. يمكنه الآن إعادة الاختبار.');
    }
}
