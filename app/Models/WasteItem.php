<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WasteItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'waste_category_id',
        'name',
        'unit',
        'buy_price_per_unit',
        'points_per_unit',
        'co2_factor_per_unit',
        'description',
        'is_active',
    ];

    protected $casts = [
        'buy_price_per_unit' => 'decimal:2',
        'points_per_unit' => 'integer',
        'co2_factor_per_unit' => 'decimal:3',
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(WasteCategory::class, 'waste_category_id');
    }

    public function depositDetails()
    {
        return $this->hasMany(WasteDepositDetail::class);
    }
}
