<?php

namespace Database\Seeders;

use App\Models\Lecture;
use App\Models\Quiz;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('production')) {
            $this->command?->warn('The demo seeder is disabled in production.');

            return;
        }

        $admin = $this->seedAdmin();

        if (! $admin) {
            $this->command?->warn(
                'No admin account found. Set ADMIN_EMAIL and ADMIN_PASSWORD in .env, then run the seeder again.'
            );

            return;
        }

        $lectures = $this->seedLectures($admin);
        $this->seedStudents($lectures);
        $this->seedQuiz($lectures[0]);
    }

    /**
     * Create the admin account from environment variables instead of shipping
     * hardcoded credentials. Falls back to an existing admin for demo data.
     */
    private function seedAdmin(): ?User
    {
        $email = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');

        if ($email && $password) {
            return User::firstOrCreate(
                ['email' => $email],
                [
                    'name'     => env('ADMIN_NAME', 'Admin'),
                    'password' => Hash::make($password),
                    'role'     => 'admin',
                ]
            );
        }

        return User::where('role', 'admin')->first();
    }

    /**
     * @return array<int, Lecture>
     */
    private function seedLectures(User $admin): array
    {
        $lectures = [];

        foreach ([
            ['title' => 'Introduction to Python',   'category' => 'Programming', 'duration_minutes' => 60,  'status' => 'published'],
            ['title' => 'Web Design Fundamentals',   'category' => 'Design',      'duration_minutes' => 45,  'status' => 'published'],
            ['title' => 'Database Management',       'category' => 'Programming', 'duration_minutes' => 90,  'status' => 'draft'],
            ['title' => 'Laravel for Beginners',     'category' => 'Programming', 'duration_minutes' => 120, 'status' => 'published'],
        ] as $data) {
            $lectures[] = Lecture::create(array_merge($data, [
                'user_id'     => $admin->id,
                'description' => 'Learn all about ' . $data['title'],
            ]));
        }

        return $lectures;
    }

    /**
     * Demo students are local-only. Their password comes from DEMO_PASSWORD or
     * is generated and printed once, so no shared password is stored in Git.
     *
     * @param  array<int, Lecture>  $lectures
     */
    private function seedStudents(array $lectures): void
    {
        $password = env('DEMO_PASSWORD') ?: Str::password(16);
        $printedPassword = false;

        $studentsData = [
            ['name' => 'Ahmed Hassan', 'email' => 'ahmed@example.com', 'phone' => '+201001234567', 'status' => 'active', 'enrolled_at' => '2024-01-15'],
            ['name' => 'Sara Mohamed', 'email' => 'sara@example.com',  'phone' => '+201112345678', 'status' => 'active', 'enrolled_at' => '2024-02-01'],
            ['name' => 'Omar Ali',     'email' => 'omar@example.com',  'phone' => null,            'status' => 'active', 'enrolled_at' => '2024-01-20'],
        ];

        foreach ($studentsData as $data) {
            $student = Student::create($data);

            User::create([
                'name'       => $data['name'],
                'email'      => $data['email'],
                'password'   => Hash::make($password),
                'role'       => 'student',
                'student_id' => $student->id,
            ]);

            $student->lectures()->attach([$lectures[0]->id, $lectures[1]->id]);

            if (! env('DEMO_PASSWORD') && ! $printedPassword) {
                $this->command?->warn("Generated demo student password: {$password}");
                $printedPassword = true;
            }
        }
    }

    private function seedQuiz(Lecture $lecture): void
    {
        $quiz = Quiz::create([
            'title'       => 'Python Basics Quiz',
            'description' => 'Test your knowledge of Python fundamentals.',
            'lecture_id'  => $lecture->id,
            'pass_score'  => 60,
            'status'      => 'published',
        ]);

        foreach ([
            ['question_text' => 'What is the correct syntax to print in Python?', 'option_a' => 'print("Hello")', 'option_b' => 'echo "Hello"',   'option_c' => 'printf("Hello")', 'option_d' => 'console.log("Hello")', 'correct_answer' => 'a', 'points' => 1],
            ['question_text' => 'Which data type is mutable in Python?',           'option_a' => 'Tuple',          'option_b' => 'String',          'option_c' => 'List',           'option_d' => 'Integer',               'correct_answer' => 'c', 'points' => 1],
            ['question_text' => 'What does "len()" function return?',              'option_a' => 'Type of object', 'option_b' => 'Length/count',    'option_c' => 'Last element',   'option_d' => 'Sum of elements',       'correct_answer' => 'b', 'points' => 2],
        ] as $question) {
            $quiz->questions()->create($question);
        }
    }
}
