<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EN.AR Workforce Management System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Source+Serif+4:opsz,wght@8..60,500;8..60,600;8..60,700&family=Source+Sans+3:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

    {{-- ========== NAVBAR ========== --}}
    <x-navbar />

    {{-- ========== HERO ==========
         Introduces EN.AR and the purpose of the system.
         Primary actions: Log in / learn about roles.
    --}}
    <section class="hero">
        <div class="container hero-inner">
            <p class="hero-brand">EN.AR</p>
            <h1>Workforce Management System</h1>
            <p class="hero-text">
                A centralized app for EN.AR Limited to manage staff records,
                units, job positions, employment classifications, and leave requests —
                with role-based access control.
            </p>
            <div class="hero-actions">
                <a href="/login" class="btn btn-dark">Log in</a>
                <a href="#roles" class="btn btn-outline">View roles</a>
            </div>
        </div>
    </section>

    {{-- ========== PROBLEM ==========
         Explains why the system exists (from README overview).
    --}}
    <section class="block" id="about">
        <div class="container">
            <h2>Why this system exists</h2>
            <p class="lead">
                Workforce information was handled manually or across disconnected sources.
                This application brings units, job roles, employment types, staff categories,
                and leave records into one role-aware place.
            </p>
        </div>
    </section>

    {{-- ========== ROLES ==========
         Four user roles from the README permissions table.
    --}}
    <section class="block block-alt" id="roles">
        <div class="container">
            <h2>Who can use it</h2>
            <p class="lead">Access depends on your role. Permissions are enforced in the UI and on the server.</p>

            <div class="role-grid">
                <div class="role-item">
                    <h3>Administrator</h3>
                    <p>Full access to all modules.</p>
                </div>
                <div class="role-item">
                    <h3>HR / Admin Officer</h3>
                    <p>Manage staff records, units, positions, and leave requests.</p>
                </div>
                <div class="role-item">
                    <h3>Unit Head</h3>
                    <p>View staff in their unit and review leave for that unit.</p>
                </div>
                <div class="role-item">
                    <h3>Staff</h3>
                    <p>View own profile, submit leave, and track leave history.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ========== MODULES ==========
         Core modules listed in the README.
    --}}
    <section class="block" id="modules">
        <div class="container">
            <h2>What you can do</h2>
            <p class="lead">Core modules for day-to-day workforce management.</p>

            <ol class="module-list">
                <li>
                    <strong>Authentication &amp; Roles</strong>
                    <span>Login with role-based access control.</span>
                </li>
                <li>
                    <strong>Staff Management</strong>
                    <span>Add, view, update, and archive staff. Records are never hard-deleted.</span>
                </li>
                <li>
                    <strong>Unit / Department Management</strong>
                    <span>Create units, assign a unit head, and view staff per unit.</span>
                </li>
                <li>
                    <strong>Job Position Management</strong>
                    <span>Manage job titles and assign them to staff.</span>
                </li>
                <li>
                    <strong>Employment Classification</strong>
                    <span>Track staff category, employment type, and employment status.</span>
                </li>
                <li>
                    <strong>Leave Management</strong>
                    <span>Submit leave, approve or reject with comments, and track status.</span>
                </li>
                <li>
                    <strong>Dashboard</strong>
                    <span>Headcounts, unit summaries, on leave, and pending requests.</span>
                </li>
                <li>
                    <strong>Search &amp; Filtering</strong>
                    <span>Filter staff by name, unit, position, category, type, and status.</span>
                </li>
            </ol>
        </div>
    </section>

    {{-- ========== CTA ==========
         Final prompt to sign in.
    --}}
    <section class="block block-cta" id="start">
        <div class="container cta-inner">
            <h2>Ready to open your workspace?</h2>
            <p>Sign in with your EN.AR account to see the dashboard for your role.</p>
            <div class="hero-actions">
                <a href="/login" class="btn btn-dark">Log in</a>
                <a href="/signup" class="btn btn-outline">Sign up</a>
            </div>
        </div>
    </section>

    {{-- ========== FOOTER ========== --}}
    <x-footbar />

</body>
</html>
