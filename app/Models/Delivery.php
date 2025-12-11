<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'distributor_id',
        'kiosk_id',
        'delivery_date',
        'quantity',
        'unit_price',
        'total_price',
        'discount_percentage',
        'final_price',
        'delivery_type',
        'teacher_name',
        'teacher_phone',
        'teacher_subject',
        'teacher_email',
        'customer_cin',
        'delivery_address',
        'status',
        'transaction_id',
        'payment_method',
        'online_payment_status',
        'payment_code',
        'payment_code_expires_at',
        'payment_confirmation_date',
        'payment_confirmed_by',
        'payment_receipt_number',
        'bank_deposit_slip',
        'payment_verification_notes',
        'wilaya',
        'notes',
        'latitude',
        'longitude',
        'location_validated',
    ];

    protected $casts = [
        'delivery_date' => 'date',
        'quantity' => 'integer',
        'unit_price' => 'integer',
        'total_price' => 'integer',
        'final_price' => 'integer',
        'discount_percentage' => 'decimal:2',
        'status' => 'string',
        'online_payment_status' => 'string',
        'payment_code_expires_at' => 'datetime',
        'payment_confirmation_date' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'location_validated' => 'boolean',
    ];

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($delivery) {
            // Générer un ID de transaction unique pour les commandes en ligne
            if ($delivery->delivery_type === 'online') {
                $delivery->transaction_id = 'ONL-' . date('Ymd') . '-' . strtoupper(Str::random(6));
                $delivery->payment_code = 'PAY-' . strtoupper(Str::random(8));
                $delivery->payment_code_expires_at = now()->addDays(3);
                $delivery->online_payment_status = 'payment_code_generated';
                $delivery->status = 'pending_payment';
            } elseif ($delivery->delivery_type === 'kiosk') {
                $delivery->transaction_id = 'KIO-' . date('Ymd') . '-' . strtoupper(Str::random(6));
                $delivery->status = 'confirmed';
            } elseif ($delivery->delivery_type === 'teacher_free') {
                $delivery->transaction_id = 'FREE-' . date('Ymd') . '-' . strtoupper(Str::random(6));
                $delivery->status = 'confirmed';
                $delivery->payment_method = 'free';
            } else {
                $delivery->transaction_id = 'SCH-' . date('Ymd') . '-' . strtoupper(Str::random(6));
                $delivery->status = $delivery->status ?? 'confirmed';
            }
        });

        static::saving(function ($delivery) {
            // Calculer le prix total
            $delivery->total_price = $delivery->quantity * $delivery->unit_price;
            
            // Calculer le prix final avec discount
            $discount = $delivery->discount_percentage ?? 0;
            $delivery->final_price = $delivery->total_price - ($delivery->total_price * ($discount / 100));
            
            // Pour les enseignants, prix final = 0
            if ($delivery->delivery_type === 'teacher_free') {
                $delivery->final_price = 0;
                $delivery->discount_percentage = 100;
            }
            
            // Définir le statut par défaut si non défini
            if (empty($delivery->status)) {
                $delivery->status = $delivery->delivery_type === 'online' ? 'pending_payment' : 'confirmed';
            }
        });
    }

    /**
     * Relation avec l'école
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

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
     * Relation avec l'utilisateur via kiosque
     */
    public function kioskUser()
    {
        return $this->hasOneThrough(
            User::class,
            Kiosk::class,
            'id',
            'id',
            'kiosk_id',
            'user_id'
        );
    }

    /**
     * Relation avec les paiements
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Relation avec l'utilisateur qui a confirmé le paiement
     */
    public function paymentConfirmedBy()
    {
        return $this->belongsTo(User::class, 'payment_confirmed_by');
    }

    /**
     * Accessor pour le code de paiement formaté
     */
    public function getPaymentCodeFormattedAttribute()
    {
        if (!$this->payment_code) {
            return null;
        }
        
        return $this->payment_code;
    }

    /**
     * Accessor pour le statut du code de paiement
     */
    public function getPaymentCodeStatusAttribute()
    {
        if (!$this->payment_code) {
            return 'no_code';
        }
        
        if ($this->online_payment_status === 'payment_confirmed') {
            return 'paid';
        }
        
        if ($this->payment_code_expires_at && now()->gt($this->payment_code_expires_at)) {
            return 'expired';
        }
        
        return 'pending';
    }

    /**
     * Accessor pour les instructions de paiement
     */
    public function getPaymentInstructionsAttribute()
    {
        if (!$this->payment_code) {
            return null;
        }
        
        $expiryDate = $this->payment_code_expires_at ? 
            $this->payment_code_expires_at->format('d/m/Y à H:i') : 'Non défini';
        
        return [
            'code' => $this->payment_code,
            'amount' => number_format($this->final_price, 0, ',', ' ') . ' DA',
            'expires_at' => $expiryDate,
            'payment_methods' => [
                'bank_transfer' => [
                    'bank_name' => 'Banque Nationale d\'Algérie',
                    'account_name' => 'Société des Cartes Scolaires',
                    'account_number' => '00123456789',
                    'rib' => '1234567890123456789012',
                    'reference' => $this->payment_code,
                ],
                'post_office' => [
                    'ccp_number' => '1234567',
                    'account_name' => 'Cartes Scolaires',
                    'reference' => $this->payment_code,
                ],
                'cash' => [
                    'locations' => [
                        'Siège social - Alger Centre',
                        'Agence Oran - Rue Larbi Ben M\'Hidi',
                        'Agence Constantine - Place des Martyrs',
                    ],
                    'reference' => $this->payment_code,
                ]
            ],
            'instructions' => "Présentez ce code au guichet: {$this->payment_code}\n" .
                            "Montant à payer: " . number_format($this->final_price, 0, ',', ' ') . " DA\n" .
                            "Valable jusqu'au: {$expiryDate}"
        ];
    }

    /**
     * Accessor pour le statut formaté
     */
    public function getStatusFormattedAttribute()
    {
        $statusLabels = [
            'pending_payment' => 'En attente de paiement',
            'pending_delivery' => 'Paiement confirmé - Livraison en attente',
            'confirmed' => 'Confirmé',
            'delivered' => 'Livré',
            'cancelled' => 'Annulé',
        ];
        
        return $statusLabels[$this->status] ?? $this->status;
    }

    /**
     * Accessor pour le type de livraison formaté
     */
    public function getDeliveryTypeFormattedAttribute()
    {
        $typeLabels = [
            'school' => 'Livraison école',
            'kiosk' => 'Vente kiosque',
            'online' => 'Vente en ligne',
            'teacher_free' => 'Carte enseignant gratuite',
        ];
        
        return $typeLabels[$this->delivery_type] ?? $this->delivery_type;
    }

    /**
     * Accessor pour la méthode de paiement formatée
     */
    public function getPaymentMethodFormattedAttribute()
    {
        $methodLabels = [
            'cash' => 'Espèces',
            'bank_transfer' => 'Virement bancaire',
            'check' => 'Chèque',
            'post_office' => 'Poste Algérienne',
            'card' => 'Carte bancaire',
            'free' => 'Gratuit',
        ];
        
        return $methodLabels[$this->payment_method] ?? $this->payment_method;
    }

    /**
     * Accessor pour le statut de paiement en ligne formaté
     */
    public function getOnlinePaymentStatusFormattedAttribute()
    {
        $statusLabels = [
            'payment_code_generated' => 'Code de paiement généré',
            'payment_confirmed' => 'Paiement confirmé',
            'payment_cancelled' => 'Paiement annulé',
            'payment_expired' => 'Code expiré',
        ];
        
        return $statusLabels[$this->online_payment_status] ?? $this->online_payment_status;
    }

    /**
     * Vérifier si le paiement est confirmé
     */
    public function isPaymentConfirmed()
    {
        return $this->online_payment_status === 'payment_confirmed';
    }

    /**
     * Vérifier si le code de paiement a expiré
     */
    public function isPaymentCodeExpired()
    {
        return $this->payment_code_expires_at && now()->gt($this->payment_code_expires_at);
    }

    /**
     * Vérifier si la livraison peut être annulée
     */
    public function canBeCancelled()
    {
        return in_array($this->status, ['pending_payment', 'pending_delivery']) && 
               !$this->isPaymentConfirmed();
    }

    /**
     * Calculer les jours restants avant expiration du code
     */
    public function getDaysRemainingAttribute()
    {
        if (!$this->payment_code_expires_at) {
            return null;
        }
        
        $days = now()->diffInDays($this->payment_code_expires_at, false);
        return max(0, $days);
    }

    /**
     * Accessor pour le montant de la remise
     */
    public function getDiscountAmountAttribute()
    {
        return $this->total_price - $this->final_price;
    }

    /**
     * Accessor pour le montant de la remise formaté
     */
    public function getDiscountAmountFormattedAttribute()
    {
        return number_format($this->discount_amount, 0, ',', ' ') . ' DA';
    }

    /**
     * Accessor pour le récapitulatif
     */
    public function getSummaryAttribute()
    {
        return [
            'commande_n°' => $this->transaction_id,
            'code_paiement' => $this->payment_code,
            'type' => $this->delivery_type_formatted,
            'école' => $this->school->name ?? 'Non spécifiée',
            'quantité' => $this->quantity . ' cartes',
            'prix_unitaire' => number_format($this->unit_price, 0, ',', ' ') . ' DA',
            'prix_total' => number_format($this->total_price, 0, ',', ' ') . ' DA',
            'réduction' => $this->discount_percentage . '%',
            'montant_réduction' => $this->discount_amount_formatted,
            'montant_à_payer' => number_format($this->final_price, 0, ',', ' ') . ' DA',
            'statut' => $this->status_formatted,
            'statut_paiement' => $this->online_payment_status_formatted,
            'date_commande' => $this->created_at->format('d/m/Y H:i'),
            'date_limite_paiement' => $this->payment_code_expires_at ? 
                $this->payment_code_expires_at->format('d/m/Y à H:i') : 'N/A',
            'jours_restants' => $this->days_remaining,
        ];
    }

    /**
     * Générer un code de paiement (pour renouvellement)
     */
    public function generateNewPaymentCode()
    {
        $this->payment_code = 'PAY-' . strtoupper(Str::random(8));
        $this->payment_code_expires_at = now()->addDays(2);
        $this->online_payment_status = 'payment_code_generated';
        $this->status = 'pending_payment';
        $this->save();
        
        return $this->payment_code;
    }

    /**
     * Confirmer le paiement
     */
    public function confirmPayment($paymentData)
    {
        $this->update([
            'online_payment_status' => 'payment_confirmed',
            'payment_method' => $paymentData['payment_method'],
            'payment_confirmation_date' => $paymentData['payment_date'],
            'payment_confirmed_by' => $paymentData['confirmed_by'] ?? null,
            'payment_receipt_number' => $paymentData['payment_receipt_number'],
            'bank_deposit_slip' => $paymentData['bank_deposit_slip'] ?? null,
            'payment_verification_notes' => $paymentData['payment_verification_notes'] ?? null,
            'status' => $paymentData['confirm_delivery'] ?? false ? 'confirmed' : 'pending_delivery',
        ]);
        
        return $this;
    }

    /**
     * Annuler la commande
     */
    public function cancelOrder($reason = null)
    {
        $this->update([
            'status' => 'cancelled',
            'online_payment_status' => 'payment_cancelled',
            'notes' => $this->notes . "\nANNULÉ: " . ($reason ?? 'Client a annulé') . 
                     ' - ' . now()->format('d/m/Y H:i'),
        ]);
        
        return $this;
    }

    /**
     * Scope pour les livraisons par type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('delivery_type', $type);
    }

    /**
     * Scope pour les livraisons en ligne
     */
    public function scopeOnline($query)
    {
        return $query->where('delivery_type', 'online');
    }

    /**
     * Scope pour les livraisons kiosque
     */
    public function scopeKiosk($query)
    {
        return $query->where('delivery_type', 'kiosk');
    }

    /**
     * Scope pour les livraisons école
     */
    public function scopeSchool($query)
    {
        return $query->where('delivery_type', 'school');
    }

    /**
     * Scope pour les cartes enseignants gratuites
     */
    public function scopeTeacherFree($query)
    {
        return $query->where('delivery_type', 'teacher_free');
    }

    /**
     * Scope pour les livraisons par statut
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope pour les paiements en attente
     */
    public function scopePendingPayment($query)
    {
        return $query->where('status', 'pending_payment');
    }

    /**
     * Scope pour les paiements confirmés
     */
    public function scopePaymentConfirmed($query)
    {
        return $query->where('online_payment_status', 'payment_confirmed');
    }

    /**
     * Scope pour les codes expirés
     */
    public function scopeExpiredPaymentCodes($query)
    {
        return $query->where('online_payment_status', 'payment_code_generated')
            ->where('payment_code_expires_at', '<', now());
    }

    /**
     * Scope pour les livraisons avec code de paiement
     */
    public function scopeWithPaymentCode($query)
    {
        return $query->whereNotNull('payment_code');
    }

    /**
     * Scope pour les livraisons par wilaya
     */
    public function scopeByWilaya($query, $wilaya)
    {
        return $query->where('wilaya', $wilaya);
    }

    /**
     * Scope pour les livraisons par distributeur
     */
    public function scopeByDistributor($query, $distributorId)
    {
        return $query->where('distributor_id', $distributorId);
    }

    /**
     * Scope pour les livraisons par kiosque
     */
    public function scopeByKiosk($query, $kioskId)
    {
        return $query->where('kiosk_id', $kioskId);
    }

    /**
     * Scope pour les livraisons par école
     */
    public function scopeBySchool($query, $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }

    /**
     * Scope pour les livraisons par date
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('delivery_date', [$startDate, $endDate]);
    }

    /**
     * Scope pour les livraisons du mois courant
     */
    public function scopeCurrentMonth($query)
    {
        return $query->whereMonth('delivery_date', now()->month)
            ->whereYear('delivery_date', now()->year);
    }

    /**
     * Scope pour les livraisons de l'année courante
     */
    public function scopeCurrentYear($query)
    {
        return $query->whereYear('delivery_date', now()->year);
    }

    /**
     * Obtenir les statistiques par wilaya
     */
    public static function getWilayaStats()
    {
        return self::select('wilaya')
            ->selectRaw('COUNT(*) as deliveries_count')
            ->selectRaw('SUM(quantity) as total_cards')
            ->selectRaw('SUM(final_price) as total_amount')
            ->whereNotNull('wilaya')
            ->groupBy('wilaya')
            ->orderByDesc('total_amount')
            ->get();
    }

    /**
     * Obtenir les statistiques par type
     */
    public static function getTypeStats()
    {
        return self::select('delivery_type')
            ->selectRaw('COUNT(*) as deliveries_count')
            ->selectRaw('SUM(quantity) as total_cards')
            ->selectRaw('SUM(final_price) as total_amount')
            ->groupBy('delivery_type')
            ->orderByDesc('total_amount')
            ->get();
    }

    /**
     * Obtenir les statistiques mensuelles
     */
    public static function getMonthlyStats($year = null)
    {
        $year = $year ?? now()->year;
        
        return self::selectRaw('YEAR(delivery_date) as year, MONTH(delivery_date) as month')
            ->selectRaw('COUNT(*) as deliveries_count')
            ->selectRaw('SUM(quantity) as total_cards')
            ->selectRaw('SUM(final_price) as total_amount')
            ->whereYear('delivery_date', $year)
            ->whereNotNull('delivery_date')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();
    }

    /**
     * Obtenir le total des ventes
     */
    public static function getTotalSales()
    {
        return self::sum('final_price');
    }

    /**
     * Obtenir le total des cartes vendues
     */
    public static function getTotalCardsSold()
    {
        return self::sum('quantity');
    }

    /**
     * Obtenir la moyenne des discounts
     */
    public static function getAverageDiscount()
    {
        return self::where('delivery_type', '!=', 'teacher_free')
            ->avg('discount_percentage');
    }
}