{{-- Staff dashboard: profile, leave balance, and recent requests --}}
@extends('layouts.app')

@section('title', 'My Dashboard')

@section('content')

{{-- ========== TOP BAR ========== --}}
<div class="topbar">
    <div>
        <div class="crumb">Dashboard</div>
        <h1>Welcome back, {{ $user->first_name }}</h1>
    </div>
    <a href="{{ route('leave.create') }}" class="btn-amber">Request leave</a>
</div>

{{-- ========== HERO SUMMARY ========== --}}
@php
    $ringCircumference = 263.9;
    $ringOffset = $totalLeaveDays > 0
        ? $ringCircumference - ($leaveBalance / $totalLeaveDays * $ringCircumference)
        : 0;
@endphp
<div class="hero-card">
    <div class="hero-grid">
        <div>
            <div class="hero-id">
                <div class="hero-avatar">{{ $user->initials }}</div>
                <div>
                    <div class="hero-name">{{ $user->full_name }}</div>
                    <div class="hero-role">{{ $user->position_title }} · {{ $user->unit_name }}</div>
                </div>
            </div>
            <div class="hero-meta">
                <div class="hero-meta-item">
                    <div class="k">Staff ID</div>
                    <div class="v">{{ $user->staff_id }}</div>
                </div>
                <div class="hero-meta-item">
                    <div class="k">Employment</div>
                    <div class="v">{{ $user->employment_type }}</div>
                </div>
                <div class="hero-meta-item">
                    <div class="k">Date joined</div>
                    <div class="v">{{ $user->date_joined }}</div>
                </div>
                <div class="hero-meta-item">
                    <div class="k">Status</div>
                    <div class="v">{{ $user->employment_status }}</div>
                </div>
            </div>
        </div>

        <div class="balance-ring-wrap">
            <svg class="ring" viewBox="0 0 100 100" aria-hidden="true">
                <circle cx="50" cy="50" r="42" fill="none" stroke="rgba(244,241,234,0.12)" stroke-width="10"/>
                <circle
                    cx="50" cy="50" r="42"
                    fill="none"
                    stroke="#F0DDBB"
                    stroke-width="10"
                    stroke-linecap="round"
                    stroke-dasharray="{{ $ringCircumference }}"
                    stroke-dashoffset="{{ $ringOffset }}"
                    transform="rotate(-90 50 50)"
                />
            </svg>
            <div>
                <div class="ring-label">Leave balance</div>
                <div class="ring-days">{{ $leaveBalance }}</div>
                <div class="ring-sub">of {{ $totalLeaveDays }} days left</div>
            </div>
        </div>
    </div>
</div>

{{-- ========== QUICK STATS ========== --}}
<div class="stat-row">
    <div class="stat-card">
        <div class="stat-ic amber">◔</div>
        <div>
            <div class="lbl">Pending</div>
            <div class="val">{{ $pendingCount }} {{ Str::plural('request', $pendingCount) }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-ic moss">✓</div>
        <div>
            <div class="lbl">Approved this year</div>
            <div class="val">{{ $approvedCount }} {{ Str::plural('request', $approvedCount) }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-ic slate">Σ</div>
        <div>
            <div class="lbl">Days taken</div>
            <div class="val">{{ $daysTakenThisYear }} {{ Str::plural('day', $daysTakenThisYear) }}</div>
        </div>
    </div>
</div>

{{-- ========== PROFILE + LEAVE ========== --}}
<div class="content-grid">
    <div class="left-col">

        {{-- Profile card --}}
        <div class="card">
            <div class="card-head">
                <h2>My profile</h2>
                <a href="{{ route('profile.edit') }}">Edit</a>
            </div>
            <div class="profile-body">
                <div class="pfield"><span class="k">Unit</span><span class="v">{{ $user->unit_name }}</span></div>
                <div class="pfield"><span class="k">Position</span><span class="v">{{ $user->position_title }}</span></div>
                <div class="pfield"><span class="k">Category</span><span class="v">{{ $user->staff_category }}</span></div>
                <div class="pfield"><span class="k">Employment type</span><span class="v">{{ $user->employment_type }}</span></div>
                <div class="pfield">
                    <span class="k">Status</span>
                    <span class="v"><span class="badge active">{{ $user->employment_status }}</span></span>
                </div>
                <div class="pfield"><span class="k">Email</span><span class="v">{{ $user->email }}</span></div>
            </div>
        </div>

        {{-- Leave used by type --}}
        <div class="card">
            <div class="card-head">
                <h2>Leave used by type</h2>
            </div>
            <div class="breakdown-body">
                @foreach ($leaveByType as $type)
                    @php
                        $pct = $type->allocated > 0 ? round($type->used / $type->allocated * 100) : 0;
                    @endphp
                    <div class="bd-row">
                        <div class="bd-top">
                            <span class="name">{{ $type->name }}</span>
                            <span class="days">{{ $type->used }} / {{ $type->allocated }} days</span>
                        </div>
                        <div class="bd-track">
                            <div class="bd-fill {{ $type->css_class }} bd-pct-{{ min(100, max(0, (int) $pct)) }}"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Leave history timeline --}}
    <div class="card">
        <div class="card-head">
            <h2>Recent leave requests</h2>
            <a href="{{ route('leave.index') }}">View all</a>
        </div>
        <div class="timeline">
            @forelse ($leaveRequests as $leave)
                <div class="tl-item">
                    <div class="tl-dot-col">
                        <div class="tl-dot {{ strtolower($leave->status) }}"></div>
                        @if (!$loop->last)
                            <div class="tl-line"></div>
                        @endif
                    </div>
                    <div class="tl-body">
                        <div class="tl-top">
                            <div>
                                <div class="tl-type">{{ $leave->type }}</div>
                                <div class="tl-dates">
                                    {{ $leave->start_date }} – {{ $leave->end_date }}
                                    · {{ $leave->duration }} {{ Str::plural('day', $leave->duration) }}
                                </div>
                            </div>
                            <span class="status-pill {{ strtolower($leave->status) }}">{{ $leave->status }}</span>
                        </div>
                        @if ($leave->comment)
                            <div class="tl-reason">{{ $leave->comment }}</div>
                        @endif
                    </div>
                </div>
            @empty
                <p class="timeline-empty">No leave requests yet.</p>
            @endforelse
        </div>
    </div>
</div>

@endsection
