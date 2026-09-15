<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reference_type',
        'reference_id',
        'type',
        'amount',
        'points',
        'balance_after',
        'points_after',
        'description',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'points' => 'integer',
        'balance_after' => 'decimal:2',
        'points_after' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
