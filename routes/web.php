<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/login', 'auth-views.login')->name('login');
Route::view('/signup', 'auth-views.signup')->name('signup');

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
