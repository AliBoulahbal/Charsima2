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
     * Relation avec l'utilisateur (le compte qui se connecte)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec les livraisons (effectuées par ce distributeur)
     */
    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }

    /**
     * Relation avec les paiements (reçus par ce distributeur)
     */
    public function payments()
    {
        // Supposons que la table 'payments' a une colonne 'distributor_id'
        return $this->hasMany(Payment::class);
    }

    /**
     * Calcul du total des livraisons (Accessor)
     */
    public function getTotalDeliveriesAttribute()
    {
        return $this->deliveries()->count();
    }

    /**
     * Calcul du montant total des livraisons (Accessor)
     */
    public function getTotalDeliveredAmountAttribute()
    {
        // Utilisation de 'final_price' pour plus de précision après discount
        return $this->deliveries()->sum('final_price'); 
    }

    /**
     * Calcul du montant total payé (Accessor)
     */
    public function getTotalPaidAmountAttribute()
    {
        return $this->payments()->sum('amount');
    }
    
    /**
     * Calcul du montant restant dû (Accessor)
     */
    public function getTotalRemainingAmountAttribute()
    {
        // Solde = Montant livré - Montant payé
        return $this->getTotalDeliveredAmountAttribute() - $this->getTotalPaidAmountAttribute();
    }
    
    /**
     * Vérifie si le distributeur est actif (a eu des livraisons récemment)
     */
    public function isActive($months = 3)
    {
        return $this->deliveries()
            ->where('delivery_date', '>=', now()->subMonths($months))
            ->exists();
    }

    /**
     * Obtenir le statut actif
     */
    public function getStatusAttribute()
    {
        return $this->isActive() ? 'Actif' : 'Inactif';
    }

    /**
 * Calcul du nombre total de cartes livrées (Accessor)
 */
public function getTotalCardsAttribute()
{
    return $this->deliveries()->sum('quantity');
}
}