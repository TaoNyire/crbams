<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssetCategory extends Model
{
    protected $fillable = [
        'name',
        'responsible_officer',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function assetTypes(): HasMany
    {
        return $this->hasMany(AssetType::class);
    }

    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class);
    }
}
