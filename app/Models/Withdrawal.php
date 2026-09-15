<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'user_id',
        'amount',
        'points_used',
        'type',
        'channel',
        'account_number',
        'account_holder_name',
        'status',
        'proof_file',
        'admin_notes',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'points_used' => 'integer',
        'approved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class, 'reference_id')
                    ->where('reference_type', 'withdrawal');
    }
}
