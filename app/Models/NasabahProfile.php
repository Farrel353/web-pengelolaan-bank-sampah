<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NasabahProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'no_rekening',
        'balance',
        'points',
        'co2_saved_kg',
        'address',
        'rt_rw',
        'qr_code_token',
        'status',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'points' => 'integer',
        'co2_saved_kg' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Hitung level nasabah berdasarkan total poin atau setoran
     */
    public function getTierAttribute(): string
    {
        if ($this->points >= 5000) {
            return 'Pahlawan Bumi 🌟';
        } elseif ($this->points >= 2000) {
            return 'Pelindung Alam 🌿';
        } elseif ($this->points >= 500) {
            return 'Pionir Hijau 🌱';
        }
        return 'Sahabat Lingkungan 🍃';
    }
}
