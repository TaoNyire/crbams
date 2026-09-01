
{{-- =========================================================
CRB ASSET MANAGEMENT SYSTEM
MAIN NAVIGATION
========================================================= --}}

{{-- =========================================================
MAIN
========================================================= --}}

<div class="crb-sidebar-section">
    Main
</div>

<ul class="crb-nav">

    {{-- DASHBOARD --}}

    <li>
        <a
            href="{{ route('dashboard') }}"
            class="{{ request()->routeIs('dashboard')
                || request()->routeIs('hardware.dashboard')
                || request()->routeIs('administration.dashboard')
                || request()->routeIs('system-admin.dashboard')
                ? 'active'
                : '' }}"
        >
            <i class="bi bi-grid-1x2-fill"></i>
            <span>Dashboard</span>
        </a>
    </li>


    {{-- =========================================================
        ASSETS
    ========================================================== --}}

    @if (
        auth()->user()->role === 'hardware_officer' ||
        auth()->user()->role === 'administration_officer' ||
        auth()->user()->role === 'system_admin'
    )

        <li>

            <a
                href="{{ route('assets.index') }}"
                class="{{ request()->routeIs('assets.*') ? 'active' : '' }}"
            >

                <i class="bi bi-box-seam"></i>

                @if (auth()->user()->role === 'hardware_officer')

                    <span>Hardware Assets</span>

                @elseif (auth()->user()->role === 'administration_officer')

                    <span>Administration Assets</span>

                @else

                    <span>Asset Register</span>

                @endif

            </a>

        </li>

    @endif

</ul>


{{-- =============================================================
HARDWARE OFFICER
============================================================= --}}

@if (auth()->user()->role === 'hardware_officer')

    <div class="crb-sidebar-section">
        Hardware Management
    </div>

    <ul class="crb-nav">

        <li>
            <a
                href="{{ route('assets.create') }}"
                class="{{ request()->routeIs('assets.create') ? 'active' : '' }}"
            >
                <i class="bi bi-plus-square"></i>
                <span>Register Asset</span>
            </a>
        </li>

        <li>
            <a href="#">
                <i class="bi bi-tools"></i>
                <span>Maintenance</span>
            </a>
        </li>

        <li>
            <a href="#">
                <i class="bi bi-arrow-left-right"></i>
                <span>Asset Movement</span>
            </a>
        </li>

        <li>
            <a href="#">
                <i class="bi bi-person-check"></i>
                <span>Assignments</span>
            </a>
        </li>

    </ul>

@endif


{{-- =============================================================
ADMINISTRATION OFFICER
============================================================= --}}

@if (auth()->user()->role === 'administration_officer')

    <div class="crb-sidebar-section">
        Administration Management
    </div>

    <ul class="crb-nav">

        <li>
            <a
                href="{{ route('assets.create') }}"
                class="{{ request()->routeIs('assets.create') ? 'active' : '' }}"
            >
                <i class="bi bi-plus-square"></i>
                <span>Register Asset</span>
            </a>
        </li>

        <li>
            <a
                href="{{ route('assets.index') }}"
                class="{{ request()->routeIs('assets.*') ? 'active' : '' }}"
            >
                <i class="bi bi-building-gear"></i>
                <span>Administration Assets</span>
            </a>
        </li>

        <li>
            <a href="#">
                <i class="bi bi-car-front"></i>
                <span>Vehicles</span>
            </a>
        </li>

        <li>
            <a href="#">
                <i class="bi bi-house"></i>
                <span>Furniture</span>
            </a>
        </li>

        <li>
            <a href="#">
                <i class="bi bi-box"></i>
                <span>Other Assets</span>
            </a>
        </li>

        <li>
            <a href="#">
                <i class="bi bi-arrow-left-right"></i>
                <span>Asset Movement</span>
            </a>
        </li>

        <li>
            <a href="#">
                <i class="bi bi-person-check"></i>
                <span>Assignments</span>
            </a>
        </li>

    </ul>

@endif


{{-- =============================================================
SYSTEM ADMINISTRATOR
GOVERNANCE / MONITORING
============================================================= --}}

@if (auth()->user()->role === 'system_admin')

    <div class="crb-sidebar-section">
        System Administration
    </div>

    <ul class="crb-nav">

        {{-- EMPLOYEES --}}

        <li>
            <a
                href="{{ route('employees.index') }}"
                class="{{ request()->routeIs('employees.*') ? 'active' : '' }}"
            >
                <i class="bi bi-people"></i>
                <span>Employees</span>
            </a>
        </li>


        {{-- USERS --}}

        <li>
            <a
                href="{{ route('users.index') }}"
                class="{{ request()->routeIs('users.*') ? 'active' : '' }}"
            >
                <i class="bi bi-person-gear"></i>
                <span>Users</span>
            </a>
        </li>


        {{-- DEPARTMENTS --}}

        <li>
            <a
                href="{{ route('departments.index') }}"
                class="{{ request()->routeIs('departments.*') ? 'active' : '' }}"
            >
                <i class="bi bi-building"></i>
                <span>Departments</span>
            </a>
        </li>


        {{-- ASSET CATEGORIES --}}

        <li>
            <a
                href="{{ route('asset-categories.index') }}"
                class="{{ request()->routeIs('asset-categories.*') ? 'active' : '' }}"
            >
                <i class="bi bi-tags"></i>
                <span>Asset Categories</span>
            </a>
        </li>


        {{-- SYSTEM TRAIL --}}

        <li>
            <a href="#">
                <i class="bi bi-clock-history"></i>
                <span>System Trail</span>
            </a>
        </li>

    </ul>


    {{-- =========================================================
        OVERSIGHT
    ========================================================== --}}

    <div class="crb-sidebar-section">
        Oversight
    </div>

    <ul class="crb-nav">

        {{-- SYSTEM ACTIVITY --}}

        <li>
            <a href="#">
                <i class="bi bi-activity"></i>
                <span>System Activity</span>
            </a>
        </li>


        {{-- REPORTS --}}

        <li>
            <a href="#">
                <i class="bi bi-bar-chart"></i>
                <span>Reports</span>
            </a>
        </li>


        {{-- COMPLIANCE --}}

        <li>
            <a href="#">
                <i class="bi bi-shield-check"></i>
                <span>Compliance</span>
            </a>
        </li>

    </ul>

@endif


{{-- =============================================================
REPORTS
OPERATIONAL OFFICERS
============================================================= --}}

@if (
    auth()->user()->role === 'hardware_officer' ||
    auth()->user()->role === 'administration_officer'
)

    <div class="crb-sidebar-section">
        Reports
    </div>

    <ul class="crb-nav">

        <li>
            <a href="#">
                <i class="bi bi-bar-chart"></i>
                <span>Reports</span>
            </a>
        </li>

    </ul>

@endif


{{-- =============================================================
ACCOUNT
============================================================= --}}

<div class="crb-sidebar-section">
    Account
</div>

<ul class="crb-nav">

    {{-- PROFILE --}}

    <li>

        <a
            href="{{ route('profile.edit') }}"
            class="{{ request()->routeIs('profile.*') ? 'active' : '' }}"
        >

            <i class="bi bi-person-circle"></i>

            <span>My Profile</span>

        </a>

    </li>


    {{-- LOGOUT --}}

    <li>

        <form
            method="POST"
            action="{{ route('logout') }}"
            style="margin:0;"
        >

            @csrf

            <a
                href="{{ route('logout') }}"
                onclick="event.preventDefault(); this.closest('form').submit();"
            >

                <i class="bi bi-box-arrow-right"></i>

                <span>Logout</span>

            </a>

        </form>

    </li>

</ul>

