<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Distributor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'wilaya',
        'user_id',
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec les livraisons
     */
    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }

    /**
     * Relation avec les paiements
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Calcul du total des livraisons
     */
    public function getTotalDeliveriesAttribute()
    {
        return $this->deliveries()->count();
    }

    /**
     * Calcul du montant total des livraisons
     */
    public function getTotalDeliveredAmountAttribute()
    {
        return $this->deliveries()->sum('total_price');
    }

    /**
     * Calcul du montant total payé
     */
    public function getTotalPaidAmountAttribute()
    {
        return $this->payments()->sum('amount');
    }

    /**
     * Calcul du solde restant
     */
    public function getRemainingAmountAttribute()
    {
        return $this->total_delivered_amount - $this->total_paid_amount;
    }
}