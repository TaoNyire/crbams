<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    /**
     * Display a listing of assets.
     *
     * System Administrator:
     * - Can view ALL assets.
     *
     * Hardware Officer:
     * - Can view Hardware assets only.
     *
     * Administration Officer:
     * - Can view Administration assets only.
     */
    public function index(Request $request)
    {
        $query = $this->assetsForCurrentUser()->with([
            'category',
            'type',
            'department',
            'employee',
        ]);

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function (Builder $q) use ($search): void {
                $q->where('asset_code', 'like', "%{$search}%")
                    ->orWhere('asset_name', 'like', "%{$search}%")
                    ->orWhere('serial_number', 'like', "%{$search}%")
                    ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        /*
        |--------------------------------------------------------------------------
        | STATUS FILTER
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        /*
        |--------------------------------------------------------------------------
        | CATEGORY FILTER
        |--------------------------------------------------------------------------
        */

        if ($request->filled('category')) {
            $query->where('asset_category_id', $request->category);
        }

        /*
        |--------------------------------------------------------------------------
        | DEPARTMENT FILTER
        |--------------------------------------------------------------------------
        */

        if ($request->filled('department')) {
            $query->where('department_id', $request->department);
        }

        /*
        |--------------------------------------------------------------------------
        | GET ASSETS
        |--------------------------------------------------------------------------
        */

        $assets = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | FILTER OPTIONS
        |--------------------------------------------------------------------------
        */

        $categories = $this->categoriesForCurrentUser()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $departments = Department::where('is_active', true)
            ->orderBy('name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | STATISTICS
        |--------------------------------------------------------------------------
        */

        $statisticsQuery = $this->assetsForCurrentUser();

        $totalAssets = (clone $statisticsQuery)->count();

        $availableAssets = (clone $statisticsQuery)
            ->where('status', 'available')
            ->count();

        $assignedAssets = (clone $statisticsQuery)
            ->where('status', 'assigned')
            ->count();

        $repairAssets = (clone $statisticsQuery)
            ->where('status', 'under_repair')
            ->count();

        $retiredAssets = (clone $statisticsQuery)
            ->where('status', 'retired')
            ->count();

        return view('assets.index', compact(
            'assets',
            'categories',
            'departments',
            'totalAssets',
            'availableAssets',
            'assignedAssets',
            'repairAssets',
            'retiredAssets'
        ));
    }

    /**
     * Show the form for creating a new asset.
     *
     * System Administrator is NOT allowed to create assets.
     */
    public function create()
    {
        $this->ensureCanManageAssets();

        $categories = $this->categoriesForCurrentUser()
            ->where('is_active', true)
            ->with('assetTypes')
            ->orderBy('name')
            ->get();

        $departments = Department::where('is_active', true)
            ->orderBy('name')
            ->get();

        $employees = Employee::with('department')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        return view('assets.create', compact(
            'categories',
            'departments',
            'employees'
        ));
    }

    /**
     * Store a newly created asset.
     *
     * System Administrator is NOT allowed to create assets.
     */
    public function store(Request $request)
    {
        $this->ensureCanManageAssets();

        $validated = $request->validate([
            'asset_code' => [
                'required',
                'string',
                'max:255',
                'unique:assets,asset_code',
            ],

            'asset_name' => [
                'required',
                'string',
                'max:255',
            ],

            'serial_number' => [
                'nullable',
                'string',
                'max:255',
                'unique:assets,serial_number',
            ],

            'asset_category_id' => [
                'required',
                'exists:asset_categories,id',
            ],

            'asset_type_id' => [
                'required',
                'exists:asset_types,id',
            ],

            'department_id' => [
                'nullable',
                'exists:departments,id',
            ],

            'employee_id' => [
                'nullable',
                'exists:employees,id',
            ],

            'location' => [
                'nullable',
                'string',
                'max:255',
            ],

            'purchase_date' => [
                'nullable',
                'date',
            ],

            'purchase_price' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'supplier' => [
                'nullable',
                'string',
                'max:255',
            ],

            'condition' => [
                'required',
                'in:new,good,fair,poor,damaged',
            ],

            'status' => [
                'required',
                'in:available,assigned,under_repair,disposed,lost,retired',
            ],

            'barcode' => [
                'nullable',
                'string',
                'max:255',
                'unique:assets,barcode',
            ],

            'notes' => [
                'nullable',
                'string',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | MANAGEMENT AREA VALIDATION
        |--------------------------------------------------------------------------
        */

        $this->ensureCategoryIsAccessible(
            (int) $validated['asset_category_id']
        );

        $this->ensureAssetTypeMatchesCategory(
            (int) $validated['asset_type_id'],
            (int) $validated['asset_category_id']
        );

        /*
        |--------------------------------------------------------------------------
        | ASSET ASSIGNMENT VALIDATION
        |--------------------------------------------------------------------------
        */

        if (
            $validated['status'] === 'assigned'
            && empty($validated['employee_id'])
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'employee_id' =>
                        'An employee must be selected when an asset is assigned.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | CLEAR EMPLOYEE WHEN NOT ASSIGNED
        |--------------------------------------------------------------------------
        */

        if ($validated['status'] !== 'assigned') {
            $validated['employee_id'] = null;
        }

        Asset::create($validated);

        return redirect()
            ->route('assets.index')
            ->with('success', 'Asset registered successfully.');
    }

    /**
     * Display the specified asset.
     *
     * System Administrator can view all assets.
     */
    public function show(Asset $asset)
    {
        $this->ensureAssetIsAccessible($asset);

        $asset->load([
            'category',
            'type',
            'department',
            'employee',
        ]);

        return view('assets.show', compact('asset'));
    }

    /**
     * Show the form for editing the specified asset.
     *
     * System Administrator is NOT allowed to edit assets.
     */
    public function edit(Asset $asset)
    {
        $this->ensureCanManageAssets();

        $this->ensureAssetIsAccessible($asset);

        $categories = $this->categoriesForCurrentUser()
            ->where('is_active', true)
            ->with('assetTypes')
            ->orderBy('name')
            ->get();

        $departments = Department::where('is_active', true)
            ->orderBy('name')
            ->get();

        $employees = Employee::with('department')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        return view('assets.edit', compact(
            'asset',
            'categories',
            'departments',
            'employees'
        ));
    }

    /**
     * Update the specified asset.
     *
     * System Administrator is NOT allowed to update assets.
     */
    public function update(Request $request, Asset $asset)
    {
        $this->ensureCanManageAssets();

        $this->ensureAssetIsAccessible($asset);

        $validated = $request->validate([
            'asset_code' => [
                'required',
                'string',
                'max:255',
                'unique:assets,asset_code,' . $asset->id,
            ],

            'asset_name' => [
                'required',
                'string',
                'max:255',
            ],

            'serial_number' => [
                'nullable',
                'string',
                'max:255',
                'unique:assets,serial_number,' . $asset->id,
            ],

            'asset_category_id' => [
                'required',
                'exists:asset_categories,id',
            ],

            'asset_type_id' => [
                'required',
                'exists:asset_types,id',
            ],

            'department_id' => [
                'nullable',
                'exists:departments,id',
            ],

            'employee_id' => [
                'nullable',
                'exists:employees,id',
            ],

            'location' => [
                'nullable',
                'string',
                'max:255',
            ],

            'purchase_date' => [
                'nullable',
                'date',
            ],

            'purchase_price' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'supplier' => [
                'nullable',
                'string',
                'max:255',
            ],

            'condition' => [
                'required',
                'in:new,good,fair,poor,damaged',
            ],

            'status' => [
                'required',
                'in:available,assigned,under_repair,disposed,lost,retired',
            ],

            'barcode' => [
                'nullable',
                'string',
                'max:255',
                'unique:assets,barcode,' . $asset->id,
            ],

            'notes' => [
                'nullable',
                'string',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | MANAGEMENT AREA VALIDATION
        |--------------------------------------------------------------------------
        */

        $this->ensureCategoryIsAccessible(
            (int) $validated['asset_category_id']
        );

        $this->ensureAssetTypeMatchesCategory(
            (int) $validated['asset_type_id'],
            (int) $validated['asset_category_id']
        );

        /*
        |--------------------------------------------------------------------------
        | ASSET ASSIGNMENT VALIDATION
        |--------------------------------------------------------------------------
        */

        if (
            $validated['status'] === 'assigned'
            && empty($validated['employee_id'])
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'employee_id' =>
                        'An employee must be selected when an asset is assigned.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | CLEAR EMPLOYEE WHEN NOT ASSIGNED
        |--------------------------------------------------------------------------
        */

        if ($validated['status'] !== 'assigned') {
            $validated['employee_id'] = null;
        }

        $asset->update($validated);

        return redirect()
            ->route('assets.show', $asset)
            ->with('success', 'Asset updated successfully.');
    }

    /**
     * Retire the specified asset.
     *
     * System Administrator is NOT allowed to retire assets.
     */
    public function destroy(Asset $asset)
    {
        $this->ensureCanManageAssets();

        $this->ensureAssetIsAccessible($asset);

        $asset->update([
            'status' => 'retired',
            'employee_id' => null,
        ]);

        return redirect()
            ->route('assets.index')
            ->with('success', 'Asset retired successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESS CONTROL
    |--------------------------------------------------------------------------
    */

    /**
     * Ensure the current user can perform operational asset management.
     *
     * System Administrator has READ-ONLY access to assets.
     */
    private function ensureCanManageAssets(): void
    {
        $user = auth()->user();

        abort_unless(
            in_array(
                $user->role,
                [
                    'hardware_officer',
                    'administration_officer',
                ],
                true
            ),
            403,
            'System Administrators have read-only access to assets.'
        );
    }

    /**
     * Get assets available to the current user.
     *
     * System Administrator:
     * - All assets.
     *
     * Hardware Officer:
     * - Hardware assets only.
     *
     * Administration Officer:
     * - Administration assets only.
     */
    private function assetsForCurrentUser(): Builder
    {
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | SYSTEM ADMINISTRATOR
        |--------------------------------------------------------------------------
        |
        | System Admin has full visibility but no operational permissions.
        |
        */

        if ($user->role === 'system_admin') {
            return Asset::query();
        }

        /*
        |--------------------------------------------------------------------------
        | OPERATIONAL OFFICERS
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $user->management_area,
            403,
            'No management area has been assigned to this user.'
        );

        return Asset::query()
            ->whereHas(
                'category',
                function (Builder $query) use ($user): void {
                    $query->where(
                        'responsible_officer',
                        $user->management_area
                    );
                }
            );
    }

    /**
     * Get categories available to the current user.
     */
    private function categoriesForCurrentUser(): Builder
    {
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | SYSTEM ADMINISTRATOR
        |--------------------------------------------------------------------------
        |
        | System Admin can view categories.
        |
        | The create/store methods are protected separately by
        | ensureCanManageAssets().
        |
        */

        if ($user->role === 'system_admin') {
            return AssetCategory::query();
        }

        /*
        |--------------------------------------------------------------------------
        | OPERATIONAL OFFICERS
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $user->management_area,
            403,
            'No management area has been assigned to this user.'
        );

        return AssetCategory::query()
            ->where(
                'responsible_officer',
                $user->management_area
            );
    }

    /**
     * Ensure the selected category belongs to the
     * current user's management area.
     */
    private function ensureCategoryIsAccessible(int $categoryId): void
    {
        abort_unless(
            $this->categoriesForCurrentUser()
                ->whereKey($categoryId)
                ->exists(),
            403,
            'You are not authorized to manage assets in this category.'
        );
    }

    /**
     * Ensure an asset type belongs to its submitted category.
     */
    private function ensureAssetTypeMatchesCategory(
        int $assetTypeId,
        int $categoryId
    ): void {
        abort_unless(
            AssetCategory::query()
                ->whereKey($categoryId)
                ->whereHas(
                    'assetTypes',
                    function (Builder $query) use ($assetTypeId): void {
                        $query->whereKey($assetTypeId);
                    }
                )
                ->exists(),
            422,
            'The selected asset type does not belong to the selected category.'
        );
    }

    /**
     * Ensure the requested asset belongs to the current user's
     * management area.
     *
     * System Administrator can access all assets for viewing.
     */
    private function ensureAssetIsAccessible(Asset $asset): void
    {
        abort_unless(
            $this->assetsForCurrentUser()
                ->whereKey($asset->id)
                ->exists(),
            403,
            'You are not authorized to manage this asset.'
        );
    }
}