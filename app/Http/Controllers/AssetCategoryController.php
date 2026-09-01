<?php

namespace App\Http\Controllers;

use App\Models\AssetCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AssetCategoryController extends Controller
{
    /**
     * Display a listing of asset categories.
     */
    public function index()
    {
        $categories = $this->categoriesForCurrentUser()
            ->withCount('assets')
            ->withCount('assetTypes')
            ->orderBy('name')
            ->paginate(15);

        return view('asset-categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new asset category.
     */
    public function create()
    {
        return view('asset-categories.create', [
            'canChooseResponsibleOfficer' => $this->canChooseResponsibleOfficer(),
        ]);
    }

    /**
     * Store a newly created asset category.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:asset_categories,name',
            ],

            'responsible_officer' => [
                'required',
                Rule::in([
                    'hardware',
                    'administration',
                ]),
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        if (! $this->canChooseResponsibleOfficer()) {
            $validated['responsible_officer'] = auth()->user()->management_area;
        }

        AssetCategory::create($validated);

        return redirect()
            ->route('asset-categories.index')
            ->with('success', 'Asset category created successfully.');
    }

    /**
     * Display the specified asset category.
     */
    public function show(AssetCategory $assetCategory)
    {
        $this->ensureCategoryIsAccessible($assetCategory);

        $assetCategory->load([
            'assetTypes',
        ]);

        $assetCategory->loadCount('assets');

        return view(
            'asset-categories.show',
            compact('assetCategory')
        );
    }

    /**
     * Show the form for editing the specified asset category.
     */
    public function edit(AssetCategory $assetCategory)
    {
        $this->ensureCategoryIsAccessible($assetCategory);

        return view(
            'asset-categories.edit',
            [
                'assetCategory' => $assetCategory,
                'canChooseResponsibleOfficer' => $this->canChooseResponsibleOfficer(),
            ]
        );
    }

    /**
     * Update the specified asset category.
     */
    public function update(
        Request $request,
        AssetCategory $assetCategory
    ) {
        $this->ensureCategoryIsAccessible($assetCategory);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('asset_categories', 'name')
                    ->ignore($assetCategory->id),
            ],

            'responsible_officer' => [
                'required',
                Rule::in([
                    'hardware',
                    'administration',
                ]),
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        if (! $this->canChooseResponsibleOfficer()) {
            $validated['responsible_officer'] = auth()->user()->management_area;
        }

        $assetCategory->update($validated);

        return redirect()
            ->route('asset-categories.index')
            ->with('success', 'Asset category updated successfully.');
    }

    /**
     * Remove the specified asset category.
     */
    public function destroy(AssetCategory $assetCategory)
    {
        $this->ensureCategoryIsAccessible($assetCategory);

        /*
         * Prevent deletion when assets or asset types
         * are already associated with the category.
         */
        if ($assetCategory->assets()->exists()) {
            return redirect()
                ->route('asset-categories.index')
                ->with(
                    'error',
                    'This category cannot be deleted because assets are associated with it.'
                );
        }

        if ($assetCategory->assetTypes()->exists()) {
            return redirect()
                ->route('asset-categories.index')
                ->with(
                    'error',
                    'This category cannot be deleted because asset types are associated with it.'
                );
        }

        $assetCategory->delete();

        return redirect()
            ->route('asset-categories.index')
            ->with(
                'success',
                'Asset category deleted successfully.'
            );
    }

    /**
     * Get categories that the current user is authorized to manage.
     */
    private function categoriesForCurrentUser(): Builder
    {
        $user = auth()->user();

        if ($this->canChooseResponsibleOfficer()) {
            return AssetCategory::query();
        }

        abort_unless($user->management_area, 403, 'No management area has been assigned to this user.');

        return AssetCategory::query()
            ->where('responsible_officer', $user->management_area);
    }

    /**
     * Determine whether the current user can manage all category areas.
     */
    private function canChooseResponsibleOfficer(): bool
    {
        return auth()->user()->role === 'system_admin';
    }

    /**
     * Ensure a category belongs to the current user's management area.
     */
    private function ensureCategoryIsAccessible(AssetCategory $assetCategory): void
    {
        abort_unless(
            $this->categoriesForCurrentUser()->whereKey($assetCategory->id)->exists(),
            403,
            'You are not authorized to manage this category.'
        );
    }
}
