<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\LectureController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\StudentPortalController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\MaintenanceController;
use App\Http\Controllers\Admin\DatabaseBackupController;
use App\Http\Controllers\Admin\AdminActivityController;
use App\Http\Controllers\Admin\AccessCodeController as AdminAccessCodeController;
use App\Http\Controllers\Portal\AccessCodeController as PortalAccessCodeController;

use App\Http\Controllers\Auth\RegisteredStudentController;
use App\Http\Middleware\LogAdminActivity;

// Language switch
Route::get('/lang/{lang}', [LanguageController::class, 'switch'])->name('lang.switch');

Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::get('/photo-picker', function () {
    return view('photo-picker');
});

Route::get('/login-redirect', function () {
    return redirect()->route('login');
});

// =====================
// ADMIN ROUTES
// =====================
Route::middleware(['auth', 'admin', LogAdminActivity::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('lectures', LectureController::class);
    Route::resource('students', StudentController::class);
    Route::resource('quizzes', QuizController::class);
    Route::get('/quizzes/{quiz}/take',                          [QuizController::class, 'take'])->name('quizzes.take');
    Route::post('/quizzes/{quiz}/submit',                       [QuizController::class, 'submit'])->name('quizzes.submit');
    Route::get('/quizzes/{quiz}/results',                       [QuizController::class, 'results'])->name('quizzes.results');
    Route::patch('/quizzes/{quiz}/attempts/{attempt}/grade',    [QuizController::class, 'gradeAttempt'])->name('quizzes.attempts.grade');
    Route::get('/leaderboard',                                  [LeaderboardController::class, 'index'])->name('leaderboard.index');
    Route::get('/leaderboard/{lecture}',                        [LeaderboardController::class, 'show'])->name('leaderboard.show');
    Route::delete('/students/{student}/attempts/{quiz}/reset',  [StudentController::class, 'resetAttempt'])->name('students.attempts.reset');
    Route::delete('/lecture-materials/{material}',              [LectureController::class, 'destroyMaterial'])->name('lectures.materials.destroy');

    // Payments & subscription plans (admin)
    Route::get('/payments',                          [PaymentController::class, 'index'])->name('admin.payments.index');
    Route::post('/payments/{payment}/approve',        [PaymentController::class, 'approve'])->name('admin.payments.approve');
    Route::post('/payments/{payment}/reject',         [PaymentController::class, 'reject'])->name('admin.payments.reject');
    Route::get('/payments/plans',                     [PaymentController::class, 'plans'])->name('admin.payments.plans');
    Route::post('/payments/plans',                     [PaymentController::class, 'storePlan'])->name('admin.payments.plans.store');
    Route::post('/payments/plans/{plan}/toggle',        [PaymentController::class, 'togglePlan'])->name('admin.payments.plans.toggle');
    Route::delete('/payments/plans/{plan}',             [PaymentController::class, 'destroyPlan'])->name('admin.payments.plans.destroy');

    // ── Access codes (registration / lecture unlock) ──
    Route::resource('access-codes', AdminAccessCodeController::class, [
        'parameters' => ['access-codes' => 'accessCode'],
    ]);
    Route::post('/access-codes/{accessCode}/toggle',                    [AdminAccessCodeController::class, 'toggle'])->name('access-codes.toggle');
    Route::post('/access-codes/{accessCode}/students',                  [AdminAccessCodeController::class, 'addStudent'])->name('access-codes.students.store');
    Route::delete('/access-codes/{accessCode}/students/{student}',      [AdminAccessCodeController::class, 'removeStudent'])->name('access-codes.students.destroy');

    // ── Maintenance mode ──
    Route::get('/maintenance',      [MaintenanceController::class, 'index'])->name('admin.maintenance.index');
    Route::post('/maintenance/down', [MaintenanceController::class, 'down'])->name('admin.maintenance.down');
    Route::post('/maintenance/up',   [MaintenanceController::class, 'up'])->name('admin.maintenance.up');

    // ── Backups & audit log ──
    Route::get('/backups/database', [DatabaseBackupController::class, 'download'])->name('admin.backups.database');
    Route::get('/activity-log',      [AdminActivityController::class, 'index'])->name('admin.activity.index');
});

// =====================
// STUDENT PORTAL ROUTES
// =====================
Route::middleware(['auth', 'student'])->prefix('portal')->name('portal.')->group(function () {

    // Always accessible — even without an active subscription,
    // a student must be able to see their dashboard and pay.
    Route::get('/',               [StudentPortalController::class, 'index'])->name('dashboard');
    Route::get('/subscription',   [SubscriptionController::class, 'index'])->name('subscription');
    Route::post('/subscription/{plan}/pay', [SubscriptionController::class, 'submitPayment'])->name('subscription.pay');

    // ── Enter an access code (no subscription needed — this is how they unlock) ──
    Route::get('/codes',         [PortalAccessCodeController::class, 'index'])->name('codes');
    Route::post('/codes/redeem', [PortalAccessCodeController::class, 'redeem'])->name('codes.redeem');

    // Gated behind an active subscription
    Route::middleware(['subscription'])->group(function () {
        Route::get('/lectures',                          [StudentPortalController::class, 'lectures'])->name('lectures');
        Route::get('/lectures/{lecture}',                [StudentPortalController::class, 'showLecture'])->name('lectures.show');
        Route::get('/quizzes',                           [StudentPortalController::class, 'quizzes'])->name('quizzes');
        Route::get('/quizzes/{quiz}/take',               [StudentPortalController::class, 'takeQuiz'])->name('quizzes.take');
        Route::post('/quizzes/{quiz}/submit',            [StudentPortalController::class, 'submitQuiz'])->name('quizzes.submit');
        Route::get('/quizzes/{quiz}/results/{attempt}',  [StudentPortalController::class, 'results'])->name('quizzes.results');
        Route::get('/results',                           [StudentPortalController::class, 'myResults'])->name('results');
        Route::get('/results/{attempt}',                 [StudentPortalController::class, 'reviewAttempt'])->name('results.review');
        Route::get('/leaderboard',                       [LeaderboardController::class, 'index'])->name('leaderboard.index');
        Route::get('/leaderboard/{lecture}',             [LeaderboardController::class, 'show'])->name('leaderboard.show');
    });
});

// ── Notifications (shared by admin + student shells) ──
Route::middleware('auth')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/feed', [NotificationController::class, 'feed'])->name('notifications.feed');
    Route::post('/notifications/read', [NotificationController::class, 'read'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.read-all');
    Route::post('/notifications/seen', [NotificationController::class, 'seen'])->name('notifications.seen');
});

// ── Self-hosted lecture video: streamed for uploads, redirected for direct links ──
Route::get('/lectures/{lecture}/video', [LectureController::class, 'streamVideo'])
    ->middleware('auth')->name('lectures.video');

// ── Serve lecture material files directly (fallback when storage symlink unavailable) ──
Route::get('/files/materials/{path}', function (string $path) {
    $fullPath = storage_path('app/public/lecture-materials/' . $path);

    if (!file_exists($fullPath)) {
        abort(404);
    }

    // Only allow authenticated users to download
    if (!auth()->check()) {
        abort(403);
    }

    $mime = mime_content_type($fullPath);
    $name = basename($fullPath);

    return response()->file($fullPath, [
        'Content-Type'        => $mime,
        'Content-Disposition' => 'attachment; filename="' . $name . '"',
    ]);
})->middleware(['auth'])->where('path', '.*')->name('files.materials');

require __DIR__.'/auth.php';

// ── Our custom student registration OVERRIDES Laravel's default Breeze
// register routes from auth.php above. This must stay AFTER the require
// because Laravel's router keeps the LAST route registered for a given
// method+URI — defining these here guarantees ours wins, not Breeze's
// default RegisteredUserController.
Route::get('/register', [RegisteredStudentController::class, 'create'])->name('register');
Route::post('/register', [RegisteredStudentController::class, 'store'])->middleware('throttle:10,1');