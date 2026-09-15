<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WasteDepositDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'waste_deposit_id',
        'waste_item_id',
        'weight',
        'price_per_unit',
        'points_per_unit',
        'subtotal_amount',
        'subtotal_points',
        'subtotal_co2',
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'price_per_unit' => 'decimal:2',
        'points_per_unit' => 'integer',
        'subtotal_amount' => 'decimal:2',
        'subtotal_points' => 'integer',
        'subtotal_co2' => 'decimal:2',
    ];

    public function deposit()
    {
        return $this->belongsTo(WasteDeposit::class, 'waste_deposit_id');
    }

    public function item()
    {
        return $this->belongsTo(WasteItem::class, 'waste_item_id');
    }
}
