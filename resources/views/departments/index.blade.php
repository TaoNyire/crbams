<x-app-layout>
    <x-slot name="title">
        Departments
    </x-slot>

<div class="d-flex justify-content-between align-items-center mb-4">

    <div class="crb-page-title mb-0">

        <h1>Departments</h1>

        <p>
            Manage CRB departments and monitor their employees and assets.
        </p>

    </div>

    <a href="{{ route('departments.create') }}" class="btn btn-crb">

        <i class="bi bi-building-add me-1"></i>

        Add Department

    </a>

</div>


{{-- SUCCESS MESSAGE --}}

@if (session('success'))

    <div class="alert alert-success mb-4">

        <i class="bi bi-check-circle me-1"></i>

        {{ session('success') }}

    </div>

@endif


{{-- ERROR MESSAGE --}}

@if (session('error'))

    <div class="alert alert-danger mb-4">

        <i class="bi bi-exclamation-triangle me-1"></i>

        {{ session('error') }}

    </div>

@endif


{{-- DEPARTMENT CARD --}}

<div class="crb-card">

    <div class="crb-card-header">

        <div>

            <h5>

                <i class="bi bi-building me-2"></i>

                CRB Departments

            </h5>

            <small>
                {{ $departments->total() }} department(s) registered
            </small>

        </div>

    </div>


    @if ($departments->count())

        <div class="table-responsive">

            <table class="table crb-table align-middle">

                <thead>

                    <tr>

                        <th>Department</th>

                        <th>Code</th>

                        <th>Employees</th>

                        <th>Assets</th>

                        <th>Status</th>

                        <th class="text-end">Actions</th>

                    </tr>

                </thead>


                <tbody>

                    @foreach ($departments as $department)

                        <tr>

                            {{-- DEPARTMENT --}}

                            <td>

                                <div class="d-flex align-items-center gap-2">

                                    <div class="crb-avatar">

                                        <i class="bi bi-building"></i>

                                    </div>

                                    <div>

                                        <a
                                            href="{{ route('departments.show', $department) }}"
                                            class="crb-asset-code"
                                        >
                                            {{ $department->name }}
                                        </a>

                                        @if ($department->description)

                                            <div class="crb-muted">

                                                {{ \Illuminate\Support\Str::limit($department->description, 45) }}

                                            </div>

                                        @endif

                                    </div>

                                </div>

                            </td>


                            {{-- CODE --}}

                            <td>

                                @if ($department->code)

                                    <span class="crb-muted">

                                        {{ $department->code }}

                                    </span>

                                @else

                                    <span class="crb-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- EMPLOYEES --}}

                            <td>

                                <span class="fw-semibold">

                                    {{ $department->employees_count }}

                                </span>

                                <span class="crb-muted">

                                    employee{{ $department->employees_count == 1 ? '' : 's' }}

                                </span>

                            </td>


                            {{-- ASSETS --}}

                            <td>

                                <span class="fw-semibold">

                                    {{ $department->assets_count }}

                                </span>

                                <span class="crb-muted">

                                    asset{{ $department->assets_count == 1 ? '' : 's' }}

                                </span>

                            </td>


                            {{-- STATUS --}}

                            <td>

                                @if ($department->is_active)

                                    <span class="crb-status available">

                                        <span class="crb-status-dot"></span>

                                        Active

                                    </span>

                                @else

                                    <span class="crb-status retired">

                                        <span class="crb-status-dot"></span>

                                        Inactive

                                    </span>

                                @endif

                            </td>


                            {{-- ACTIONS --}}

                            <td class="text-end">

                                <div class="crb-actions">

                                    {{-- VIEW --}}

                                    <a
                                        href="{{ route('departments.show', $department) }}"
                                        class="crb-action-btn"
                                        title="View Department"
                                    >

                                        <i class="bi bi-eye"></i>

                                    </a>


                                    {{-- EDIT --}}

                                    <a
                                        href="{{ route('departments.edit', $department) }}"
                                        class="crb-action-btn"
                                        title="Edit Department"
                                    >

                                        <i class="bi bi-pencil"></i>

                                    </a>


                                    {{-- DELETE --}}

                                    <form
                                        action="{{ route('departments.destroy', $department) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Are you sure you want to delete this department?');"
                                    >

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="crb-action-btn danger"
                                            title="Delete Department"
                                        >

                                            <i class="bi bi-trash"></i>

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>


        {{-- PAGINATION --}}

        @if ($departments->hasPages())

            <div class="crb-pagination">

                {{ $departments->links() }}

            </div>

        @endif

    @else

        {{-- EMPTY STATE --}}

        <div class="crb-empty-state">

            <div class="crb-empty-icon">

                <i class="bi bi-building"></i>

            </div>

            <h4>No Departments Yet</h4>

            <p>
                Start by creating the first department in the CRB system.
            </p>

            <a
                href="{{ route('departments.create') }}"
                class="btn btn-crb"
            >

                <i class="bi bi-building-add me-1"></i>

                Add Department

            </a>

        </div>

    @endif

</div>

</x-app-layout>