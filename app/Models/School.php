<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'district',
        'phone',
        'manager_name',
        'student_count',
        'wilaya',
        'latitude',      // Nouveau: Coordonnée GPS latitude
        'longitude',     // Nouveau: Coordonnée GPS longitude
        'radius',        // Nouveau: Rayon de validation en km
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'radius' => 'decimal:3',
        'student_count' => 'integer',
    ];

    /**
     * Relation avec les livraisons
     */
    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }

    /**
     * Vérifie si une position GPS est dans le rayon autorisé de l'école
     */
    public function isWithinRadius($userLat, $userLng)
    {
        // Si l'école n'a pas de coordonnées GPS, on accepte la livraison
        if (is_null($this->latitude) || is_null($this->longitude)) {
            return true;
        }
        
        $distance = $this->calculateDistance($userLat, $userLng);
        return $distance <= ($this->radius ?? 0.05); // 50m par défaut
    }

    /**
     * Calcule la distance entre l'école et une position GPS donnée
     */
    public function calculateDistance($userLat, $userLng)
    {
        if (is_null($this->latitude) || is_null($this->longitude)) {
            return 0;
        }
        
        $earthRadius = 6371; // Rayon de la Terre en km
        
        $dLat = deg2rad($userLat - $this->latitude);
        $dLon = deg2rad($userLng - $this->longitude);
        
        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($this->latitude)) * cos(deg2rad($userLat)) *
             sin($dLon/2) * sin($dLon/2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        
        return $earthRadius * $c; // Distance en km
    }

    /**
     * Trouve les écoles à proximité d'une position GPS
     */
    public static function nearby($latitude, $longitude, $radiusKm = 2)
    {
        return self::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get()
            ->map(function ($school) use ($latitude, $longitude) {
                $school->distance = $school->calculateDistance($latitude, $longitude);
                return $school;
            })
            ->filter(function ($school) use ($radiusKm) {
                return $school->distance <= $radiusKm;
            })
            ->sortBy('distance')
            ->values();
    }

    /**
     * Vérifie si l'école a des coordonnées GPS définies
     */
    public function hasCoordinates()
    {
        return !is_null($this->latitude) && !is_null($this->longitude);
    }

    /**
     * Formate les coordonnées GPS pour l'affichage
     */
    public function getCoordinatesFormattedAttribute()
    {
        if (!$this->hasCoordinates()) {
            return 'Non définies';
        }
        
        return number_format($this->latitude, 6) . ', ' . number_format($this->longitude, 6);
    }

    /**
     * Formate le rayon pour l'affichage
     */
    public function getRadiusFormattedAttribute()
    {
        if (is_null($this->radius)) {
            return '50m (défaut)';
        }
        
        $meters = $this->radius * 1000;
        
        if ($meters < 1000) {
            return number_format($meters, 0) . 'm';
        }
        
        return number_format($this->radius, 2) . 'km';
    }

    /**
     * Scope pour les écoles avec coordonnées GPS
     */
    public function scopeWithCoordinates($query)
    {
        return $query->whereNotNull('latitude')
                    ->whereNotNull('longitude');
    }

    /**
     * Scope pour les écoles sans coordonnées GPS
     */
    public function scopeWithoutCoordinates($query)
    {
        return $query->whereNull('latitude')
                    ->orWhereNull('longitude');
    }

    /**
     * Nombre total de cartes livrées
     */
    public function getTotalCardsDeliveredAttribute()
    {
        return $this->deliveries()->sum('quantity');
    }

    /**
     * Montant total des livraisons
     */
    public function getTotalDeliveredAmountAttribute()
    {
        return $this->deliveries()->sum('total_price');
    }

    /**
     * Dernière livraison
     */
    public function getLastDeliveryAttribute()
    {
        return $this->deliveries()->latest('delivery_date')->first();
    }

    /**
     * Nombre de livraisons avec localisation validée
     */
    public function getValidatedDeliveriesCountAttribute()
    {
        return $this->deliveries()
            ->where('location_validated', true)
            ->count();
    }

    /**
     * Nombre de livraisons sans localisation validée
     */
    public function getNonValidatedDeliveriesCountAttribute()
    {
        return $this->deliveries()
            ->where('location_validated', false)
            ->orWhereNull('location_validated')
            ->count();
    }

    /**
     * Scope pour filtrer par wilaya
     */
    public function scopeByWilaya($query, $wilaya)
    {
        return $query->where('wilaya', $wilaya);
    }

    /**
     * Scope pour rechercher
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('manager_name', 'like', "%{$search}%")
                    ->orWhere('district', 'like', "%{$search}%");
    }

    /**
     * Scope pour rechercher par coordonnées approximatives
     */
    public function scopeNearCoordinates($query, $latitude, $longitude, $tolerance = 0.01)
    {
        return $query->whereBetween('latitude', [
                $latitude - $tolerance,
                $latitude + $tolerance
            ])
            ->whereBetween('longitude', [
                $longitude - $tolerance,
                $longitude + $tolerance
            ]);
    }

    /**
     * Met à jour les coordonnées GPS de l'école
     */
    public function updateCoordinates($latitude, $longitude, $radius = null)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        
        if (!is_null($radius)) {
            $this->radius = $radius;
        }
        
        return $this->save();
    }

    /**
     * Vérifie si l'école est active (a reçu des livraisons récemment)
     */
    public function isActive($months = 6)
    {
        return $this->deliveries()
            ->where('delivery_date', '>=', now()->subMonths($months))
            ->exists();
    }

    /**
     * Obtenir le statut GPS de l'école
     */
    public function getGpsStatusAttribute()
    {
        if (!$this->hasCoordinates()) {
            return [
                'status' => 'missing',
                'label' => 'Coordonnées manquantes',
                'color' => 'danger',
                'icon' => 'location_off',
            ];
        }
        
        if (!$this->isActive()) {
            return [
                'status' => 'inactive',
                'label' => 'Coordonnées définies (inactive)',
                'color' => 'warning',
                'icon' => 'location_on',
            ];
        }
        
        return [
            'status' => 'active',
            'label' => 'Coordonnées actives',
            'color' => 'success',
            'icon' => 'check_circle',
        ];
    }

    /**
     * Génére un lien Google Maps
     */
    public function getGoogleMapsLinkAttribute()
    {
        if (!$this->hasCoordinates()) {
            return null;
        }
        
        return "https://www.google.com/maps?q={$this->latitude},{$this->longitude}";
    }

    /**
     * Génére un lien OpenStreetMap
     */
    public function getOpenStreetMapLinkAttribute()
    {
        if (!$this->hasCoordinates()) {
            return null;
        }
        
        return "https://www.openstreetmap.org/?mlat={$this->latitude}&mlon={$this->longitude}#map=18/{$this->latitude}/{$this->longitude}";
    }

    /**
     * Accessor pour la latitude formatée
     */
    public function getLatitudeFormattedAttribute()
    {
        return is_null($this->latitude) ? null : number_format($this->latitude, 8);
    }

    /**
     * Accessor pour la longitude formatée
     */
    public function getLongitudeFormattedAttribute()
    {
        return is_null($this->longitude) ? null : number_format($this->longitude, 8);
    }

    /**
     * Vérifie si le rayon est le rayon par défaut
     */
    public function isDefaultRadius()
    {
        return is_null($this->radius) || $this->radius == 0.05;
    }

    /**
     * Retourne le rayon à utiliser (valeur ou défaut)
     */
    public function getEffectiveRadiusAttribute()
    {
        return $this->radius ?? 0.05;
    }
}