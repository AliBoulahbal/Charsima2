<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kiosk extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'owner_name',
        'phone',
        'email',
        'address',
        'wilaya',
        'district',
        'latitude',
        'longitude',
        'is_active',
        'user_id', // Si vous voulez un compte utilisateur pour le kiosque
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    /**
     * Relation avec les livraisons
     */
    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Total des ventes
     */
    public function getTotalSalesAttribute()
    {
        return $this->deliveries()->sum('final_price');
    }

    /**
     * Nombre de ventes
     */
    public function getSalesCountAttribute()
    {
        return $this->deliveries()->count();
    }

    /**
     * Ventes du mois
     */
    public function getMonthlySalesAttribute()
    {
        return $this->deliveries()
            ->whereMonth('delivery_date', now()->month)
            ->whereYear('delivery_date', now()->year)
            ->sum('final_price');
    }
}