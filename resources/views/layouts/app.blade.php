<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

```
<meta charset="utf-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<meta name="csrf-token" content="{{ csrf_token() }}">

<title>
    {{ $title ?? 'CRB Asset Management System' }}
</title>

@vite([
    'resources/css/app.css',
    'resources/js/app.js'
])
```

</head>

<body>

<div class="crb-app">

```
{{-- =========================================================
     SIDEBAR
========================================================== --}}

<aside class="crb-sidebar">

    <div class="crb-brand">

        <div class="crb-brand-icon">
            CRB
        </div>

        <div class="crb-brand-text">

            <strong>CRB ASSETS</strong>

            <span>Management System</span>

        </div>

    </div>

    @include('layouts.navigation')

</aside>


{{-- =========================================================
     MAIN AREA
========================================================== --}}

<div class="crb-main">


    {{-- =====================================================
         TOP BAR
    ====================================================== --}}

    <header class="crb-topbar">

        <div class="crb-search">

            <i class="bi bi-search"></i>

            <input
                type="text"
                placeholder="Search assets..."
            >

        </div>


        <div class="crb-topbar-right">

            <i class="bi bi-bell crb-notification"></i>


            @auth

                <div class="crb-user">

                    <div class="crb-user-avatar">

                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}

                    </div>


                    <div class="crb-user-info">

                        <strong>
                            {{ auth()->user()->name }}
                        </strong>

                        <span>

                            @switch(auth()->user()->role)

                                @case('hardware_officer')

                                    Hardware Officer

                                    @break

                                @case('administration_officer')

                                    Administration Officer

                                    @break

                                @case('system_admin')

                                    System Administrator

                                    @break

                                @default

                                    User

                            @endswitch

                        </span>

                    </div>

                </div>

            @endauth

        </div>

    </header>


    {{-- =====================================================
         CONTENT
    ====================================================== --}}

    <main class="crb-content">


        {{-- SUCCESS MESSAGE --}}

        @if (session('success'))

            <div
                class="alert alert-success alert-dismissible fade show"
                role="alert"
            >

                <i class="bi bi-check-circle me-2"></i>

                {{ session('success') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"
                ></button>

            </div>

        @endif


        {{-- ERROR MESSAGE --}}

        @if (session('error'))

            <div
                class="alert alert-danger alert-dismissible fade show"
                role="alert"
            >

                <i class="bi bi-exclamation-circle me-2"></i>

                {{ session('error') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"
                ></button>

            </div>

        @endif


        {{-- VALIDATION ERRORS --}}

        @if ($errors->any())

            <div
                class="alert alert-danger"
                role="alert"
            >

                <div class="fw-semibold">

                    <i class="bi bi-exclamation-triangle me-2"></i>

                    Please correct the following errors:

                </div>

                <ul class="mb-0 mt-2">

                    @foreach ($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        {{-- =================================================
             BLADE COMPONENT CONTENT
        ================================================== --}}

        {{ $slot }}

    </main>

</div>
```

</div>

</body>

</html>
