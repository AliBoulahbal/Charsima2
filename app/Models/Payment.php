<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    // Fusionner tous les champs fillable en une seule déclaration
    protected $fillable = [
        'distributor_id',
        'kiosk_id',
        'school_id',
        'delivery_id',
        'amount',
        'payment_date',
        'method',
        'wilaya',
        'school_name',
        'reference_number',
        'notes',
        'confirmed_by',
        'payment_type',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'integer',
    ];

    /**
     * Relation avec le distributeur
     */
    public function distributor()
    {
        return $this->belongsTo(Distributor::class);
    }

    /**
     * Relation avec le kiosque
     */
    public function kiosk()
    {
        return $this->belongsTo(Kiosk::class);
    }

    /**
     * Relation avec l'école
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Relation avec la livraison
     */
    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }

    /**
     * Relation avec l'utilisateur via distributeur
     */
    public function user()
    {
        return $this->hasOneThrough(
            User::class,
            Distributor::class,
            'id',
            'id',
            'distributor_id',
            'user_id'
        );
    }

    /**
     * Relation avec l'utilisateur qui a confirmé
     */
    public function confirmedBy()
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }

    /**
     * Accessor pour le montant formaté
     */
    public function getAmountFormattedAttribute()
    {
        return number_format($this->amount, 0, ',', ' ') . ' DA';
    }

    /**
     * Accessor pour la méthode formatée
     */
    public function getMethodFormattedAttribute()
    {
        $methods = [
            'cash' => 'Espèces',
            'check' => 'Chèque',
            'transfer' => 'Virement',
            'card' => 'Carte bancaire',
            'post_office' => 'Poste Algérienne',
            'other' => 'Autre',
            'free' => 'Gratuit',
        ];
        
        return $methods[$this->method] ?? $this->method;
    }

    /**
     * Accessor pour la couleur de la méthode
     */
    public function getMethodColorAttribute()
    {
        $colors = [
            'cash' => 'success',
            'check' => 'warning',
            'transfer' => 'info',
            'card' => 'primary',
            'post_office' => 'secondary',
            'other' => 'dark',
            'free' => 'success',
        ];
        
        return $colors[$this->method] ?? 'secondary';
    }

    /**
     * Scope pour les paiements par distributeur
     */
    public function scopeByDistributor($query, $distributorId)
    {
        return $query->where('distributor_id', $distributorId);
    }

    /**
     * Scope pour les paiements par kiosque
     */
    public function scopeByKiosk($query, $kioskId)
    {
        return $query->where('kiosk_id', $kioskId);
    }

    /**
     * Scope pour les paiements par école
     */
    public function scopeBySchool($query, $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }

    /**
     * Scope pour les paiements par wilaya
     */
    public function scopeByWilaya($query, $wilaya)
    {
        return $query->where('wilaya', $wilaya);
    }

    /**
     * Scope pour les paiements par méthode
     */
    public function scopeByMethod($query, $method)
    {
        return $query->where('method', $method);
    }

    /**
     * Scope pour les paiements entre dates
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('payment_date', [$startDate, $endDate]);
    }

    /**
     * Scope pour les paiements du mois courant
     */
    public function scopeCurrentMonth($query)
    {
        return $query->whereMonth('payment_date', now()->month)
            ->whereYear('payment_date', now()->year);
    }

    /**
     * Vérifier si le paiement est pour une vente en ligne
     */
    public function isForOnlineSale()
    {
        return $this->delivery && $this->delivery->delivery_type === 'online';
    }

    /**
     * Vérifier si le paiement est pour un kiosque
     */
    public function isForKioskSale()
    {
        return $this->kiosk_id !== null;
    }

    /**
     * Obtenir le nom du client
     */
    public function getCustomerNameAttribute()
    {
        if ($this->delivery && $this->delivery->teacher_name) {
            return $this->delivery->teacher_name;
        }
        
        if ($this->distributor) {
            return $this->distributor->name;
        }
        
        if ($this->kiosk) {
            return $this->kiosk->owner_name;
        }
        
        return 'Client';
    }

    /**
     * Obtenir le téléphone du client
     */
    public function getCustomerPhoneAttribute()
    {
        if ($this->delivery && $this->delivery->teacher_phone) {
            return $this->delivery->teacher_phone;
        }
        
        if ($this->distributor) {
            return $this->distributor->phone;
        }
        
        if ($this->kiosk) {
            return $this->kiosk->phone;
        }
        
        return null;
    }

    /**
     * Obtenir le récapitulatif du paiement
     */
    public function getSummaryAttribute()
    {
        return [
            'id' => $this->id,
            'date' => $this->payment_date->format('d/m/Y'),
            'amount' => $this->amount_formatted,
            'method' => $this->method_formatted,
            'reference' => $this->reference_number,
            'customer' => $this->customer_name,
            'school' => $this->school_name ?? ($this->school->name ?? 'N/A'),
            'wilaya' => $this->wilaya ?? 'N/A',
            'confirmed_by' => $this->confirmedBy->name ?? 'N/A',
        ];
    }
}