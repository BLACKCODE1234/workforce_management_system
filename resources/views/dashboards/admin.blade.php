{{-- Admin dashboard: workforce overview --}}
@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')

{{-- ========== HERO BANNER ========== --}}
<div class="admin-hero">
    <div class="admin-hero-glow"></div>
    <div class="admin-hero-inner">
        <div class="admin-hero-top">
            <div>
                <div class="admin-hero-crumb">Dashboard / Administrator</div>
                <h1 class="admin-hero-title">Workforce overview</h1>
                <p class="admin-hero-sub">Live summary of all staff records across EN.AR Limited.</p>
            </div>
            <a href="#" class="btn-amber">+ Add staff</a>
        </div>
        <div class="admin-hero-chips">
            <div class="ah-chip"><span class="k">Departments</span><span class="v">{{ $byDepartment->count() }}</span></div>
            <div class="ah-chip"><span class="k">Employment modes</span><span class="v">{{ $byWorkMode->count() }}</span></div>
            <div class="ah-chip"><span class="k">Categories</span><span class="v">{{ $byCategory->count() }}</span></div>
        </div>
    </div>
</div>

{{-- ========== KEY STATS ========== --}}
<div class="stat-row">
    <div class="stat-card">
        <div class="stat-ic navy">Σ</div>
        <div>
            <div class="lbl">Total workforce</div>
            <div class="val">{{ $total }}</div>
            <div class="delta">Headcount across all units</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-ic moss">✓</div>
        <div>
            <div class="lbl">Active</div>
            <div class="val">{{ $active }}</div>
            <div class="delta">{{ $total > 0 ? round($active / $total * 100) : 0 }}% of workforce</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-ic amber">◔</div>
        <div>
            <div class="lbl">Inactive / archived</div>
            <div class="val">{{ $inactive }}</div>
            <div class="delta">Records never hard-deleted</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-ic slate">✉</div>
        <div>
            <div class="lbl">Pending requests</div>
            <div class="val">{{ $pendingRequests }}</div>
            <div class="delta">Awaiting approval</div>
        </div>
    </div>
</div>

{{-- ========== STATUS + BREAKDOWNS ========== --}}
<div class="content-grid">

    {{-- Status distribution donut --}}
    <div class="card">
        <div class="card-head">
            <h2>Employment status</h2>
        </div>
        <div class="donut-wrap">
            @php
                $actPct = $total > 0 ? round($active / $total * 100) : 0;
                $inPct = 100 - $actPct;
                $isReduced = !$total;
            @endphp
            <div class="donut" style="background: conic-gradient(#4B6B4F 0% {{ $isReduced ? 100 : $actPct }}%, #E2DCCB {{ $isReduced ? 100 : $actPct }}% 100%);">
                <div class="donut-hole">
                    <div class="donut-center">{{ $total }}</div>
                    <div class="donut-label">total staff</div>
                </div>
            </div>
            <div class="donut-legend">
                <div class="dg-item"><span class="sw moss"></span><span>Active</span><span class="cnt">{{ $active }} · {{ $actPct }}%</span></div>
                <div class="dg-item"><span class="sw slate-lite"></span><span>Inactive</span><span class="cnt">{{ $inactive }} · {{ $inPct }}%</span></div>
            </div>
        </div>
    </div>

    {{-- Headcount by department --}}
    <div class="card">
        <div class="card-head">
            <h2>Headcount by department</h2>
        </div>
        <div class="breakdown-body">
            @forelse ($byDepartment as $row)
                @php $pct = $total > 0 ? round($row->total / $total * 100) : 0; @endphp
                <div class="bd-row">
                    <div class="bd-top">
                        <span class="name">{{ $row->name }}</span>
                        <span class="days">{{ $row->total }} · {{ $pct }}%</span>
                    </div>
                    <div class="bd-track">
                        <div class="bd-fill amber bd-pct-{{ min(100, max(0, (int) $pct)) }}"></div>
                    </div>
                </div>
            @empty
                <p class="breakdown-empty">No staff records yet.</p>
            @endforelse
        </div>
    </div>
</div>

{{-- ========== BREAKDOWNS ROW ========== --}}
<div class="content-grid two">
    <div class="card">
        <div class="card-head">
            <h2>Headcount by work mode</h2>
        </div>
        <div class="breakdown-body">
            @forelse ($byWorkMode as $row)
                @php $pct = $total > 0 ? round($row->total / $total * 100) : 0; @endphp
                <div class="bd-row">
                    <div class="bd-top">
                        <span class="name">{{ $row->name }}</span>
                        <span class="days">{{ $row->total }} · {{ $pct }}%</span>
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

    <div class="card">
        <div class="card-head">
            <h2>Headcount by category</h2>
        </div>
        <div class="breakdown-body">
            @forelse ($byCategory as $row)
                @php $pct = $total > 0 ? round($row->total / $total * 100) : 0; @endphp
                <div class="bd-row">
                    <div class="bd-top">
                        <span class="name">{{ $row->name }}</span>
                        <span class="days">{{ $row->total }} · {{ $pct }}%</span>
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

{{-- ========== RECENT STAFF TABLE ========== --}}
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
                <div class="rt-right">
                    <span class="status-pill {{ strtolower($staff->employment_status) }}">{{ $staff->employment_status }}</span>
                    <span class="rt-mode">{{ $staff->work_mode }}</span>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-icon">◫</div>
                <p>No staff added yet. Use "Add staff" to start building the workforce roster.</p>
            </div>
        @endforelse
    </div>
</div>

@endsection
