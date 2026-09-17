<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\AssetType;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssetController extends Controller
{
    /**
     * Display a listing of assets.
     */
    public function index(Request $request): View
    {
        $query = $this->assetsForCurrentUser()
            ->with([
                'category',
                'type',
                'department',
                'employee',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function (Builder $q) use ($search) {
                $q->where('asset_code', 'like', "%{$search}%")
                    ->orWhere('asset_name', 'like', "%{$search}%")
                    ->orWhere('serial_number', 'like', "%{$search}%")
                    ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Filters
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('asset_category_id', $request->category);
        }

        if ($request->filled('department')) {
            $query->where('department_id', $request->department);
        }

        /*
        |--------------------------------------------------------------------------
        | Management Area Filter
        |--------------------------------------------------------------------------
        | Used by the System Administrator dashboard.
        |
        | System Administrator can filter all assets by:
        | - hardware
        | - administration
        |
        | Operational officers cannot use this filter to bypass
        | their existing access restrictions because the base query
        | is already restricted by assetsForCurrentUser().
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled('management_area') &&
            auth()->user()->role === 'system_admin'
        ) {
            $managementArea = $request->management_area;

            if (in_array($managementArea, ['hardware', 'administration'], true)) {
                $query->whereHas('category', function (Builder $q) use ($managementArea) {
                    $q->where('responsible_officer', $managementArea);
                });
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $assets = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Filter Data
        |--------------------------------------------------------------------------
        */

        $categories = $this->categoriesForCurrentUser()
            ->orderBy('name')
            ->get();

        $departments = Department::orderBy('name')->get();

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $accessibleAssets = $this->assetsForCurrentUser();

        /*
        |--------------------------------------------------------------------------
        | Apply Management Area to Statistics
        |--------------------------------------------------------------------------
        | This keeps the statistics consistent with the assets currently
        | being displayed when the System Administrator clicks a
        | management-area dashboard card.
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled('management_area') &&
            auth()->user()->role === 'system_admin'
        ) {
            $managementArea = $request->management_area;

            if (in_array($managementArea, ['hardware', 'administration'], true)) {
                $accessibleAssets->whereHas(
                    'category',
                    function (Builder $q) use ($managementArea) {
                        $q->where(
                            'responsible_officer',
                            $managementArea
                        );
                    }
                );
            }
        }

        $totalAssets = (clone $accessibleAssets)->count();

        $availableAssets = (clone $accessibleAssets)
            ->where('status', 'available')
            ->count();

        $assignedAssets = (clone $accessibleAssets)
            ->where('status', 'assigned')
            ->count();

        $repairAssets = (clone $accessibleAssets)
            ->where('status', 'under_repair')
            ->count();

        $retiredAssets = (clone $accessibleAssets)
            ->where('status', 'retired')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view('assets.index', [
            'assets' => $assets,
            'categories' => $categories,
            'departments' => $departments,

            'totalAssets' => $totalAssets,
            'availableAssets' => $availableAssets,
            'assignedAssets' => $assignedAssets,
            'repairAssets' => $repairAssets,
            'retiredAssets' => $retiredAssets,
        ]);
    }


    /**
     * Show the form for creating a new asset.
     */
    public function create(): View
    {
        $this->ensureCanManageAssets();

        $categories = $this->categoriesForCurrentUser()
            ->orderBy('name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Departments
        |--------------------------------------------------------------------------
        | No status filter because the departments table does not have
        | a status column.
        |--------------------------------------------------------------------------
        */

        $departments = Department::orderBy('name')->get();

        /*
        |--------------------------------------------------------------------------
        | Employees
        |--------------------------------------------------------------------------
        | No status filter because the employees table may not have
        | a status column.
        |--------------------------------------------------------------------------
        */

        $employees = Employee::orderBy('last_name')->get();

        return view('assets.create', compact(
            'categories',
            'departments',
            'employees'
        ));
    }


    /**
     * Store a newly created asset.
     */
    public function store(Request $request)
    {
        $this->ensureCanManageAssets();

        $validated = $request->validate([
            'asset_name' => ['required', 'string', 'max:255'],
            'asset_category_id' => ['required', 'exists:asset_categories,id'],
            'asset_type_id' => ['required', 'exists:asset_types,id'],
            'serial_number' => ['nullable', 'string', 'max:255'],
            'barcode' => ['nullable', 'string', 'max:255'],
            'purchase_date' => ['nullable', 'date'],
            'purchase_price' => ['nullable', 'numeric', 'min:0'],
            'supplier' => ['nullable', 'string', 'max:255'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'employee_id' => ['nullable', 'exists:employees,id'],
            'location' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:available,under_repair,retired'],
            'condition' => ['nullable', 'in:new,good,fair,poor,damaged'],
            'notes' => ['nullable', 'string'],
        ]);

        // New assets must enter the system unassigned. Assignment history
        // is created only through AssetAssignmentController.
        abort_if(
            !empty($validated['employee_id']) || !empty($validated['department_id']),
            422,
            'New assets cannot be assigned during creation. Create the asset first, then use the Assignment module.'
        );

        $category = AssetCategory::findOrFail($validated['asset_category_id']);
        $this->ensureCategoryIsAccessible($category);
        $this->ensureAssetTypeMatchesCategory((int) $validated['asset_type_id'], (int) $category->id);

        $validated['employee_id'] = null;
        $validated['department_id'] = null;
        $validated['asset_code'] = $this->generateAssetCode();

        Asset::create($validated);

        return redirect()
            ->route('assets.index')
            ->with('success', 'Asset successfully created. Use the Assignment module to allocate it to an employee.');
    }

    /**
     * Display the specified asset.
     */
    public function show(Asset $asset): View
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
     * Display a printable QR tag for one asset.
     */
    public function tag(Asset $asset): View
    {
        $this->ensureAssetIsAccessible($asset);

        $asset->load([
            'category',
            'type',
            'department',
            'employee',
        ]);

        /*
        |--------------------------------------------------------------------------
        | QR Destination
        |--------------------------------------------------------------------------
        |
        | The QR code currently points to the asset record.
        |
        | We will later replace this with a dedicated secure scan route
        | so that the physical CRB asset tag can be scanned safely.
        |--------------------------------------------------------------------------
        */

        $assetUrl = route('assets.show', $asset);

        return view('assets.tag', compact(
            'asset',
            'assetUrl'
        ));
    }


    /**
     * Display printable QR tags for multiple assets.
     */
    public function bulkTags(Request $request): View
    {
        /*
        |--------------------------------------------------------------------------
        | Get Selected Asset IDs
        |--------------------------------------------------------------------------
        */

        $ids = collect($request->input('assets', []))
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->values();

        abort_if(
            $ids->isEmpty(),
            422,
            'Please select at least one asset.'
        );

        /*
        |--------------------------------------------------------------------------
        | Get Accessible Assets
        |--------------------------------------------------------------------------
        */

        $assets = $this->assetsForCurrentUser()
            ->whereIn('id', $ids)
            ->with([
                'category',
                'type',
                'department',
                'employee',
            ])
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Security Check
        |--------------------------------------------------------------------------
        */

        abort_if(
            $assets->count() !== $ids->count(),
            403,
            'One or more selected assets are not accessible.'
        );

        return view('assets.tags-bulk', compact('assets'));
    }


    /**
     * Show the form for editing the specified asset.
     */
    public function edit(Asset $asset): View
    {
        $this->ensureCanManageAssets();

        $this->ensureAssetIsAccessible($asset);

        $categories = $this->categoriesForCurrentUser()
            ->orderBy('name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Departments
        |--------------------------------------------------------------------------
        */

        $departments = Department::orderBy('name')->get();

        /*
        |--------------------------------------------------------------------------
        | Employees
        |--------------------------------------------------------------------------
        */

        $employees = Employee::orderBy('last_name')->get();

        return view('assets.edit', compact(
            'asset',
            'categories',
            'departments',
            'employees'
        ));
    }


    /**
     * Update the specified asset.
     */
    public function update(Request $request, Asset $asset)
    {
        $this->ensureCanManageAssets();
        $this->ensureAssetIsAccessible($asset);

        $validated = $request->validate([
            'asset_code' => ['required', 'string', 'max:255', 'unique:assets,asset_code,' . $asset->id],
            'asset_name' => ['required', 'string', 'max:255'],
            'asset_category_id' => ['required', 'exists:asset_categories,id'],
            'asset_type_id' => ['required', 'exists:asset_types,id'],
            'serial_number' => ['nullable', 'string', 'max:255', 'unique:assets,serial_number,' . $asset->id],
            'barcode' => ['nullable', 'string', 'max:255', 'unique:assets,barcode,' . $asset->id],
            'purchase_date' => ['nullable', 'date'],
            'purchase_price' => ['nullable', 'numeric', 'min:0'],
            'supplier' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:available,assigned,under_repair,disposed,lost,retired'],
            'condition' => ['nullable', 'in:new,good,fair,poor,damaged'],
            'notes' => ['nullable', 'string'],
        ]);

        $category = AssetCategory::findOrFail($validated['asset_category_id']);
        $this->ensureCategoryIsAccessible($category);
        $this->ensureAssetTypeMatchesCategory((int) $validated['asset_type_id'], (int) $category->id);

        // Assignment ownership is controlled exclusively by the Assignment module.
        // An assigned asset may be edited, but its current assignee/status cannot be
        // changed here. To reassign it, return it first, then create a new assignment.
        if ($asset->status === 'assigned') {
            abort_unless(
                $validated['status'] === 'assigned',
                422,
                'Assigned asset status cannot be changed from the Asset Edit screen. Return the asset through the Assignment module first.'
            );
        } elseif ($validated['status'] === 'assigned') {
            abort(
                422,
                'An asset cannot be assigned from the Asset Edit screen. Use the Assignment module.'
            );
        }

        // Never accept employee_id or department_id from the edit request.
        // They remain whatever the Assignment lifecycle says they should be.
        unset($validated['employee_id'], $validated['department_id']);

        $asset->update($validated);

        return redirect()
            ->route('assets.show', $asset->fresh())
            ->with('success', 'Asset successfully updated.');
    }

    /**
     * Remove the specified asset.
     */
    public function destroy(Asset $asset)
    {
        $this->ensureCanManageAssets();

        $this->ensureAssetIsAccessible($asset);

        $asset->delete();

        return redirect()
            ->route('assets.index')
            ->with('success', 'Asset successfully deleted.');
    }


    /*
    |--------------------------------------------------------------------------
    | Access Control
    |--------------------------------------------------------------------------
    */

    /**
     * Ensure the current user is allowed to manage assets.
     */
    protected function ensureCanManageAssets(): void
    {
        $user = auth()->user();

        abort_unless(
            in_array($user->role, [
                'hardware_officer',
                'administration_officer',
            ]),
            403,
            'You are not authorized to manage assets.'
        );
    }


    /**
     * Return assets accessible to the current user.
     *
     * System Administrator:
     * - Can view all assets.
     * - Cannot create, edit, delete or assign assets.
     *
     * Hardware Officer:
     * - Can access hardware/IT assets.
     *
     * Administration Officer:
     * - Can access administration assets.
     */
    protected function assetsForCurrentUser()
    {
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | System Administrator
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'system_admin') {
            return Asset::query();
        }

        /*
        |--------------------------------------------------------------------------
        | Operational Officers
        |--------------------------------------------------------------------------
        */

        return Asset::query()
            ->whereHas('category', function (Builder $query) use ($user) {
                $query->where(
                    'responsible_officer',
                    $user->management_area
                );
            });
    }


    /**
     * Return asset categories accessible to the current user.
     */
    protected function categoriesForCurrentUser()
    {
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | System Administrator
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'system_admin') {
            return AssetCategory::query();
        }

        /*
        |--------------------------------------------------------------------------
        | Operational Officers
        |--------------------------------------------------------------------------
        */

        return AssetCategory::query()
            ->where(
                'responsible_officer',
                $user->management_area
            );
    }


    /**
     * Ensure the selected category belongs to the user's management area.
     */
    protected function ensureCategoryIsAccessible(
        AssetCategory $category
    ): void {
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | System Administrator
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'system_admin') {
            return;
        }

        abort_unless(
            $category->responsible_officer === $user->management_area,
            403,
            'You are not authorized to manage this asset category.'
        );
    }


    /**
     * Ensure an asset type belongs to the selected category.
     */
    protected function ensureAssetTypeMatchesCategory(
        int $assetTypeId,
        int $categoryId
    ): void {
        $exists = AssetType::query()
            ->where('id', $assetTypeId)
            ->where('asset_category_id', $categoryId)
            ->exists();

        abort_unless(
            $exists,
            422,
            'The selected asset type does not belong to the selected category.'
        );
    }


    /**
     * Ensure the current user can access the asset.
     */
    protected function ensureAssetIsAccessible(Asset $asset): void
    {
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | System Administrator
        |--------------------------------------------------------------------------
        |
        | System Administrator has read-only visibility of all assets.
        |
        */

        if ($user->role === 'system_admin') {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Operational User
        |--------------------------------------------------------------------------
        */

        $accessible = $this->assetsForCurrentUser()
            ->whereKey($asset->id)
            ->exists();

        abort_unless(
            $accessible,
            403,
            'You are not authorized to access this asset.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Asset Code
    |--------------------------------------------------------------------------
    */

    /**
     * Generate a unique CRB asset code.
     */
    protected function generateAssetCode(): string
    {
        $lastAsset = Asset::query()
            ->latest('id')
            ->first();

        $nextNumber = $lastAsset
            ? $lastAsset->id + 1
            : 1;

        do {
            $assetCode = 'CRB-' . str_pad(
                $nextNumber,
                5,
                '0',
                STR_PAD_LEFT
            );

            $exists = Asset::where(
                'asset_code',
                $assetCode
            )->exists();

            $nextNumber++;
        } while ($exists);

        return $assetCode;
    }
}