<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WasteDeposit extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'user_id',
        'officer_id',
        'deposit_type',
        'status',
        'pickup_address',
        'pickup_date',
        'pickup_time_slot',
        'total_weight_kg',
        'total_amount',
        'total_points',
        'total_co2_kg',
        'notes',
    ];

    protected $casts = [
        'pickup_date' => 'date',
        'total_weight_kg' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'total_points' => 'integer',
        'total_co2_kg' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function officer()
    {
        return $this->belongsTo(User::class, 'officer_id');
    }

    public function details()
    {
        return $this->hasMany(WasteDepositDetail::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class, 'reference_id')
                    ->where('reference_type', 'deposit');
    }
}
