<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asset extends Model
{
    protected $fillable = [
        'asset_code',
        'asset_name',
        'serial_number',
        'asset_category_id',
        'asset_type_id',
        'department_id',
        'employee_id',
        'location',
        'purchase_date',
        'purchase_price',
        'supplier',
        'condition',
        'status',
        'barcode',
        'notes',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'purchase_price' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function category(): BelongsTo
    {
        return $this->belongsTo(
            AssetCategory::class,
            'asset_category_id'
        );
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(
            AssetType::class,
            'asset_type_id'
        );
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function getStatusLabelAttribute(): string
    {
        return ucwords(
            str_replace('_', ' ', $this->status)
        );
    }

    public function getConditionLabelAttribute(): string
    {
        return ucfirst($this->condition);
    }
}