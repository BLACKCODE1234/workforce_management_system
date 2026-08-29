<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

Route::get('/signup', [AuthController::class, 'showSignup'])->name('signup');
Route::post('/signup', [AuthController::class, 'signup'])->name('signup.submit');

Route::get('/otp-verify', [AuthController::class, 'showOtp'])->name('otp.show');
Route::post('/otp-verify', [AuthController::class, 'verifyOtp'])->name('otp.verify');
Route::post('/otp-resend', [AuthController::class, 'resendOtp'])->name('otp.resend');

Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetOtp'])->name('password.email');
Route::get('/reset-password', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/dashboard/admin', function () {
    $total = DB::table('staff')->count();
    $active = DB::table('staff')->where('employment_status', 'Active')->count();

    $pendingRequests = 0;
    if (Schema::hasTable('leave_requests')) {
        $pendingRequests = DB::table('leave_requests')->where('status', 'Pending')->count();
    }

    $byDepartment = DB::table('staff')
        ->selectRaw("COALESCE(department, 'Unassigned') AS name, COUNT(*) AS total")
        ->groupBy('department')
        ->orderByDesc('total')
        ->get();

    $byWorkMode = DB::table('staff')
        ->selectRaw("COALESCE(work_mode, 'Unassigned') AS name, COUNT(*) AS total")
        ->groupBy('work_mode')
        ->orderByDesc('total')
        ->get();

    $byStatus = DB::table('staff')
        ->selectRaw("COALESCE(employment_status, 'Unassigned') AS name, COUNT(*) AS total")
        ->groupBy('employment_status')
        ->orderByDesc('total')
        ->get();

    $byCategory = DB::table('staff')
        ->selectRaw("COALESCE(staff_category, 'Unassigned') AS name, COUNT(*) AS total")
        ->groupBy('staff_category')
        ->orderByDesc('total')
        ->get();

    $recentStaff = DB::table('staff')
        ->orderByDesc('created_at')
        ->limit(8)
        ->get();

    return view('dashboards.admin', [
        'total' => $total,
        'active' => $active,
        'pendingRequests' => $pendingRequests,
        'inactive' => $total - $active,
        'byDepartment' => $byDepartment,
        'byWorkMode' => $byWorkMode,
        'byStatus' => $byStatus,
        'byCategory' => $byCategory,
        'recentStaff' => $recentStaff,
    ]);
})->name('dashboard.admin');


/*
| Temporary placeholder routes so dashboard links resolve.
| Replace with real controllers when auth + leave modules are built.
*/
Route::redirect('/leave', '/dashboard/staff')->name('leave.index');
Route::redirect('/leave/request', '/dashboard/staff')->name('leave.create');
Route::redirect('/profile/edit', '/dashboard/staff')->name('profile.edit');

Route::get('/dashboard/staff', function () {
    $user = (object) [
        'first_name' => 'Ama',
        'full_name' => 'Ama Mensah',
        'initials' => 'AM',
        'staff_id' => 'EN-0042',
        'position_title' => 'Operations Officer',
        'unit_name' => 'Operations',
        'employment_type' => 'Permanent',
        'employment_status' => 'Active',
        'staff_category' => 'Junior Staff',
        'date_joined' => '12 Mar 2023',
        'email' => 'ama.mensah@enar.local',
    ];

    $leaveByType = collect([
        (object) ['name' => 'Annual', 'used' => 8, 'allocated' => 21, 'css_class' => 'annual'],
        (object) ['name' => 'Sick', 'used' => 2, 'allocated' => 10, 'css_class' => 'sick'],
        (object) ['name' => 'Study', 'used' => 0, 'allocated' => 5, 'css_class' => 'study'],
        (object) ['name' => 'Other', 'used' => 1, 'allocated' => 3, 'css_class' => 'other'],
    ]);

    $leaveRequests = collect([
        (object) [
            'type' => 'Annual leave',
            'start_date' => '4 Aug 2026',
            'end_date' => '8 Aug 2026',
            'duration' => 5,
            'status' => 'Approved',
            'comment' => 'Family visit.',
        ],
        (object) [
            'type' => 'Sick leave',
            'start_date' => '21 Jul 2026',
            'end_date' => '22 Jul 2026',
            'duration' => 2,
            'status' => 'Approved',
            'comment' => null,
        ],
        (object) [
            'type' => 'Annual leave',
            'start_date' => '15 Sep 2026',
            'end_date' => '19 Sep 2026',
            'duration' => 5,
            'status' => 'Pending',
            'comment' => 'Awaiting unit head review.',
        ],
    ]);

    return view('dashboards.staff', [
        'user' => $user,
        'leaveBalance' => 10,
        'totalLeaveDays' => 21,
        'pendingCount' => 1,
        'approvedCount' => 2,
        'daysTakenThisYear' => 11,
        'leaveByType' => $leaveByType,
        'leaveRequests' => $leaveRequests,
    ]);
})->name('dashboard.staff');
