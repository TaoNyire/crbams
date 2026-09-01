<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>
        {{ $title ?? 'CRB Asset Management System' }}
    </title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

</head>


<body>

<div class="crb-app">

    {{-- =========================================================
         SIDEBAR
    ========================================================== --}}

    <aside class="crb-sidebar">


        {{-- BRAND --}}

        <div class="crb-brand">

            <div class="crb-brand-icon">
                CRB
            </div>

            <div class="crb-brand-text">

                <strong>
                    CRB ASSETS
                </strong>

                <span>
                    Management System
                </span>

            </div>

        </div>


        {{-- =====================================================
             MAIN
        ====================================================== --}}

        <div class="crb-sidebar-section">
            Main
        </div>


        <ul class="crb-nav">


            {{-- DASHBOARD --}}

            <li>

                <a
                    href="{{ route('dashboard') }}"
                    class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"
                >

                    <i class="bi bi-grid-1x2-fill"></i>

                    <span>
                        Dashboard
                    </span>

                </a>

            </li>


            {{-- ASSETS --}}

            <li>

                <a
                    href="{{ route('assets.index') }}"
                    class="{{ request()->routeIs('assets.*') ? 'active' : '' }}"
                >

                    <i class="bi bi-box-seam"></i>

                    <span>
                        Assets
                    </span>

                </a>

            </li>


            {{-- EMPLOYEES --}}

            <li>

                <a
                    href="{{ route('employees.index') }}"
                    class="{{ request()->routeIs('employees.*') ? 'active' : '' }}"
                >

                    <i class="bi bi-people"></i>

                    <span>
                        Employees
                    </span>

                </a>

            </li>


            {{-- DEPARTMENTS --}}

            <li>

                <a
                    href="{{ route('departments.index') }}"
                    class="{{ request()->routeIs('departments.*') ? 'active' : '' }}"
                >

                    <i class="bi bi-building"></i>

                    <span>
                        Departments
                    </span>

                </a>

            </li>


            {{-- CATEGORIES --}}

            @if (Route::has('asset-categories.index'))

                <li>

                    <a
                        href="{{ route('asset-categories.index') }}"
                        class="{{ request()->routeIs('asset-categories.*') ? 'active' : '' }}"
                    >

                        <i class="bi bi-tags"></i>

                        <span>
                            Categories
                        </span>

                    </a>

                </li>

            @endif


            {{-- ASSET TYPES --}}

            <li>

                <a href="#">

                    <i class="bi bi-diagram-3"></i>

                    <span>
                        Asset Types
                    </span>

                </a>

            </li>

        </ul>



        {{-- =====================================================
             HARDWARE OFFICER
        ====================================================== --}}

        @if (
            Auth::user()->management_area === 'hardware'
            || Auth::user()->role === 'system_admin'
        )


            <div class="crb-sidebar-section">
                Hardware Operations
            </div>


            <ul class="crb-nav">


                {{-- ASSET MOVEMENTS --}}

                <li>

                    <a href="#">

                        <i class="bi bi-arrow-left-right"></i>

                        <span>
                            Asset Movements
                        </span>

                    </a>

                </li>


                {{-- REPAIRS --}}

                <li>

                    <a href="#">

                        <i class="bi bi-tools"></i>

                        <span>
                            Repairs & Maintenance
                        </span>

                    </a>

                </li>


                {{-- ASSIGNMENTS --}}

                <li>

                    <a href="#">

                        <i class="bi bi-person-check"></i>

                        <span>
                            Asset Assignment
                        </span>

                    </a>

                </li>

            </ul>

        @endif



        {{-- =====================================================
             ADMINISTRATION OFFICER
        ====================================================== --}}

        @if (
            Auth::user()->management_area === 'administration'
            || Auth::user()->role === 'system_admin'
        )


            <div class="crb-sidebar-section">
                Administration Operations
            </div>


            <ul class="crb-nav">


                <li>

                    <a href="#">

                        <i class="bi bi-file-earmark-text"></i>

                        <span>
                            Asset Requests
                        </span>

                    </a>

                </li>


                <li>

                    <a href="#">

                        <i class="bi bi-arrow-left-right"></i>

                        <span>
                            Asset Movements
                        </span>

                    </a>

                </li>


                <li>

                    <a href="#">

                        <i class="bi bi-folder2-open"></i>

                        <span>
                            Asset Records
                        </span>

                    </a>

                </li>

            </ul>

        @endif



        {{-- =====================================================
             REPORTS
        ====================================================== --}}

        <div class="crb-sidebar-section">
            Reports
        </div>


        <ul class="crb-nav">

            <li>

                <a href="#">

                    <i class="bi bi-bar-chart"></i>

                    <span>
                        Reports
                    </span>

                </a>

            </li>

        </ul>



        {{-- =====================================================
             SYSTEM ADMINISTRATOR
        ====================================================== --}}

        @if (Auth::user()->role === 'system_admin')


            <div class="crb-sidebar-section">
                System Administration
            </div>


            <ul class="crb-nav">


                <li>

                    <a href="#">

                        <i class="bi bi-person-gear"></i>

                        <span>
                            Users
                        </span>

                    </a>

                </li>


                <li>

                    <a href="#">

                        <i class="bi bi-shield-lock"></i>

                        <span>
                            Roles & Permissions
                        </span>

                    </a>

                </li>


                <li>

                    <a href="#">

                        <i class="bi bi-diagram-3"></i>

                        <span>
                            Management Areas
                        </span>

                    </a>

                </li>


                <li>

                    <a href="#">

                        <i class="bi bi-gear"></i>

                        <span>
                            System Settings
                        </span>

                    </a>

                </li>

            </ul>

        @endif



    </aside>



    {{-- =========================================================
         MAIN AREA
    ========================================================== --}}

    <div class="crb-main">


        {{-- TOPBAR --}}

        <header class="crb-topbar">


            {{-- SEARCH --}}

            <div class="crb-search">

                <i class="bi bi-search"></i>

                <input
                    type="text"
                    placeholder="Search assets..."
                >

            </div>



            {{-- RIGHT SIDE --}}

            <div class="crb-topbar-right">


                {{-- NOTIFICATION --}}

                <i class="bi bi-bell crb-notification"></i>



                {{-- USER --}}

                <div class="crb-user">


                    <div class="crb-user-avatar">

                        {{ strtoupper(
                            substr(
                                Auth::user()->name ?? 'AD',
                                0,
                                2
                            )
                        ) }}

                    </div>


                    <div class="crb-user-info">

                        <strong>
                            {{ Auth::user()->name ?? 'Administrator' }}
                        </strong>


                        <span>

                            @if (Auth::user()->role === 'system_admin')

                                System Administrator

                            @elseif (Auth::user()->management_area === 'hardware')

                                Hardware Officer

                            @elseif (Auth::user()->management_area === 'administration')

                                Administration Officer

                            @else

                                User

                            @endif

                        </span>

                    </div>



                    {{-- LOGOUT --}}

                    <form
                        method="POST"
                        action="{{ route('logout') }}"
                        class="ms-2"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="btn btn-link p-0 text-muted"
                            title="Logout"
                        >

                            <i class="bi bi-box-arrow-right"></i>

                        </button>

                    </form>


                </div>


            </div>


        </header>



        {{-- =====================================================
             CONTENT
        ====================================================== --}}

        <main class="crb-content">


            {{-- SUCCESS --}}

            @if (session('success'))

                <div
                    class="alert alert-success alert-dismissible fade show"
                    role="alert"
                >

                    {{ session('success') }}


                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                    ></button>

                </div>

            @endif



            {{-- ERRORS --}}

            @if ($errors->any())

                <div
                    class="alert alert-danger"
                    role="alert"
                >

                    <strong>
                        Please correct the following errors:
                    </strong>


                    <ul class="mb-0 mt-2">

                        @foreach ($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            @endif



            {{-- PAGE CONTENT --}}

            {{ $slot }}


        </main>


    </div>


</div>


</body>

</html>