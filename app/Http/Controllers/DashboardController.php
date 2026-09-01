<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;

class DashboardController extends Controller
{
    /**
     * Main dashboard dispatcher.
     *
     * Sends the logged-in user to the correct management interface.
     */
    public function index(): RedirectResponse
    {
        $user = auth()->user();

        if ($user->role === 'system_admin') {
            return redirect()->route('system-admin.dashboard');
        }

        if ($user->role === 'hardware_officer') {
            return redirect()->route('hardware.dashboard');
        }

        if ($user->role === 'administration_officer') {
            return redirect()->route('administration.dashboard');
        }

        abort(403, 'No management area has been assigned to this user.');
    }

    /**
     * Hardware Officer Dashboard.
     */
    public function hardware(): View
    {
        return view(
            'dashboard.hardware',
            $this->dashboardData('hardware')
        );
    }

    /**
     * Administration Officer Dashboard.
     */
    public function administration(): View
    {
        return view(
            'dashboard.administration',
            $this->dashboardData('administration')
        );
    }

    /**
     * System Administrator Dashboard.
     *
     * The System Administrator has system-wide visibility,
     * but does not perform operational asset management.
     */
    public function systemAdmin(): View
    {
        /*
        |--------------------------------------------------------------------------
        | PEOPLE
        |--------------------------------------------------------------------------
        */

        $totalStaff = Employee::count();

        $activeStaff = Employee::where('is_active', true)
            ->count();

        $inactiveStaff = Employee::where('is_active', false)
            ->count();

        $totalDepartments = Department::count();

        $activeDepartments = Department::where('is_active', true)
            ->count();


        /*
        |--------------------------------------------------------------------------
        | SYSTEM USERS
        |--------------------------------------------------------------------------
        */

        $totalUsers = User::count();

        $activeUsers = User::count();

        $usersByRole = User::query()
            ->select('role')
            ->get()
            ->countBy('role');


        /*
        |--------------------------------------------------------------------------
        | ASSET OVERSIGHT
        |--------------------------------------------------------------------------
        */

        $totalAssets = Asset::count();

        $hardwareAssets = $this->assetsFor('hardware')
            ->count();

        $administrationAssets = $this->assetsFor('administration')
            ->count();

        $assignedAssets = Asset::whereNotNull('employee_id')
            ->count();

        $unassignedAssets = Asset::whereNull('employee_id')
            ->count();

        $repairAssets = Asset::where(
            'status',
            'under_repair'
        )->count();


        /*
        |--------------------------------------------------------------------------
        | PEOPLE / ASSET COMPLIANCE
        |--------------------------------------------------------------------------
        */

        $inactiveStaffWithAssets = Employee::where(
            'is_active',
            false
        )
            ->whereHas('assets')
            ->count();

        $assetsWithoutDepartment = Asset::whereNull(
            'department_id'
        )->count();


        /*
        |--------------------------------------------------------------------------
        | RECENT ASSETS
        |--------------------------------------------------------------------------
        |
        | These are READ-ONLY oversight records.
        |
        */

        $recentAssets = Asset::query()
            ->with([
                'category',
                'type',
                'department',
                'employee',
            ])
            ->latest()
            ->take(8)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | RECENT STAFF
        |--------------------------------------------------------------------------
        */

        $recentStaff = Employee::query()
            ->with('department')
            ->latest()
            ->take(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | DASHBOARD DATA
        |--------------------------------------------------------------------------
        */

        return view('dashboard.system-admin', [

            /*
            | People
            */
            'totalStaff' => $totalStaff,
            'activeStaff' => $activeStaff,
            'inactiveStaff' => $inactiveStaff,
            'totalDepartments' => $totalDepartments,
            'activeDepartments' => $activeDepartments,

            /*
            | Users
            */
            'totalUsers' => $totalUsers,
            'activeUsers' => $activeUsers,
            'usersByRole' => $usersByRole,

            /*
            | Assets
            */
            'totalAssets' => $totalAssets,
            'hardwareAssets' => $hardwareAssets,
            'administrationAssets' => $administrationAssets,
            'assignedAssets' => $assignedAssets,
            'unassignedAssets' => $unassignedAssets,
            'repairAssets' => $repairAssets,

            /*
            | Compliance
            */
            'inactiveStaffWithAssets' => $inactiveStaffWithAssets,
            'assetsWithoutDepartment' => $assetsWithoutDepartment,

            /*
            | Recent activity / oversight
            */
            'recentAssets' => $recentAssets,
            'recentStaff' => $recentStaff,
        ]);
    }

    /**
     * Build dashboard statistics for one management area.
     *
     * @return array{
     *     totalAssets: int,
     *     availableAssets: int,
     *     assignedAssets: int,
     *     repairAssets: int,
     *     recentAssets: Collection<int, Asset>
     * }
     */
    private function dashboardData(
        ?string $responsibleOfficer = null
    ): array {
        $assets = $this->assetsFor($responsibleOfficer);

        return [
            'totalAssets' => (clone $assets)->count(),

            'availableAssets' => (clone $assets)
                ->where('status', 'available')
                ->count(),

            'assignedAssets' => (clone $assets)
                ->where('status', 'assigned')
                ->count(),

            'repairAssets' => (clone $assets)
                ->where('status', 'under_repair')
                ->count(),

            'recentAssets' => $assets
                ->with([
                    'category',
                    'type',
                    'department',
                    'employee',
                ])
                ->latest()
                ->take(5)
                ->get(),
        ];
    }

    /**
     * Get assets belonging to a management area.
     */
    private function assetsFor(
        ?string $responsibleOfficer = null
    ): Builder {
        return Asset::query()
            ->when(
                $responsibleOfficer,
                function (
                    Builder $query,
                    string $officer
                ): void {
                    $query->whereHas(
                        'category',
                        function (
                            Builder $categoryQuery
                        ) use ($officer): void {
                            $categoryQuery->where(
                                'responsible_officer',
                                $officer
                            );
                        }
                    );
                }
            );
    }
}