{{-- Admin dashboard: workforce summary and breakdowns --}}
@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')

{{-- ========== TOP BAR ========== --}}
<div class="topbar">
    <div>
        <div class="crumb">Dashboard / Administrator</div>
        <h1>Workforce overview</h1>
        <p class="topbar-sub">Live summary of all staff records across EN.AR Limited.</p>
    </div>
    <a href="#" class="btn-amber">Add staff</a>
</div>

{{-- ========== KEY STATS ========== --}}
<div class="stat-row">
    <div class="stat-card">
        <div class="stat-ic slate">Σ</div>
        <div>
            <div class="lbl">Total workforce</div>
            <div class="val">{{ $total }} staff</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-ic moss">✓</div>
        <div>
            <div class="lbl">Active</div>
            <div class="val">{{ $active }} staff</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-ic amber">◔</div>
        <div>
            <div class="lbl">Inactive / archived</div>
            <div class="val">{{ $inactive }} staff</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-ic amber">✉</div>
        <div>
            <div class="lbl">Pending requests</div>
            <div class="val">{{ $pendingRequests }}</div>
        </div>
    </div>
</div>

{{-- ========== BREAKDOWNS + RECENT ========== --}}
<div class="content-grid">
    <div class="left-col">

        {{-- Headcount by department --}}
        <div class="card">
            <div class="card-head">
                <h2>Headcount by department</h2>
            </div>
            <div class="breakdown-body">
                @forelse ($byDepartment as $row)
                    @php
                        $pct = $total > 0 ? round($row->total / $total * 100) : 0;
                    @endphp
                    <div class="bd-row">
                        <div class="bd-top">
                            <span class="name">{{ $row->name }}</span>
                            <span class="days">{{ $row->total }} {{ Str::plural('staff', $row->total) }} · {{ $pct }}%</span>
                        </div>
                        <div class="bd-track">
                            <div class="bd-fill moss bd-pct-{{ min(100, max(0, (int) $pct)) }}"></div>
                        </div>
                    </div>
                @empty
                    <p class="breakdown-empty">No staff records yet.</p>
                @endforelse
            </div>
        </div>

        {{-- Headcount by employment type --}}
        <div class="card">
            <div class="card-head">
                <h2>Headcount by work mode</h2>
            </div>
            <div class="breakdown-body">
                @forelse ($byWorkMode as $row)
                    @php
                        $pct = $total > 0 ? round($row->total / $total * 100) : 0;
                    @endphp
                    <div class="bd-row">
                        <div class="bd-top">
                            <span class="name">{{ $row->name }}</span>
                            <span class="days">{{ $row->total }} {{ Str::plural('staff', $row->total) }} · {{ $pct }}%</span>
                        </div>
                        <div class="bd-track">
                            <div class="bd-fill slate bd-pct-{{ min(100, max(0, (int) $pct)) }}"></div>
                        </div>
                    </div>
                @empty
                    <p class="breakdown-empty">No staff records yet.</p>
                @endforelse
            </div>
        </div>

    </div>

    {{-- Recent staff (right column) --}}
    <div class="card">
        <div class="card-head">
            <h2>Recently added staff</h2>
            <a href="#">View all</a>
        </div>
        <div class="recent-table">
            @forelse ($recentStaff as $staff)
                <div class="rt-row">
                    <div class="rt-id">
                        <div class="rt-avatar">{{ ucfirst(substr($staff->first_name, 0, 1)) }}{{ ucfirst(substr($staff->last_name, 0, 1)) }}</div>
                        <div>
                            <div class="rt-name">{{ $staff->first_name }} {{ $staff->last_name }}</div>
                            <div class="rt-role">{{ $staff->department }} · {{ $staff->position }}</div>
                        </div>
                    </div>
                    <span class="status-pill {{ strtolower($staff->employment_status) }}">{{ $staff->employment_status }}</span>
                </div>
            @empty
                <p class="timeline-empty">No staff added yet.</p>
            @endforelse
        </div>
    </div>
</div>

@endsection
