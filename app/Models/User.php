<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasFactory, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // admin, manager, distributor
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relation avec le profil distributeur
     */
    public function distributorProfile()
    {
        return $this->hasOne(Distributor::class);
    }
    
    /**
     * Relation avec le Kiosque (pour l'utilisateur qui gère le kiosque)
     * Ceci corrige l'erreur BadMethodCallException.
     */
    public function kiosk()
    {
        return $this->hasOne(Kiosk::class);
    }

    /**
     * Relation avec les livraisons (si l'utilisateur est distributeur)
     */
    public function deliveries()
    {
        return $this->hasMany(Delivery::class, 'distributor_id');
    }

    /**
     * Relation avec les paiements (si l'utilisateur est distributeur)
     */
    public function payments()
    {
        return $this->hasMany(Payment::class, 'distributor_id');
    }

    /**
     * Vérifie si l'utilisateur est super admin
     */
    public function isSuperAdmin()
    {
        return $this->role === 'admin' || $this->hasRole('super_admin');
    }

    /**
     * Vérifie si l'utilisateur est distributeur
     */
    public function isDistributor()
    {
        return $this->role === 'distributor' || $this->hasRole('distributor');
    }

    /**
     * Vérifie si l'utilisateur est manager
     */
    public function isManager()
    {
        return $this->role === 'manager' || $this->hasRole('manager');
    }

    /**
     * Get the wilaya via distributor profile or kiosk profile
     */
    public function getWilayaAttribute()
    {
        // 1. Essayer de récupérer la Wilaya via le profil Distributeur
        if ($this->distributorProfile) {
            return $this->distributorProfile->wilaya;
        }
        
        // 2. Sinon, essayer via le Kiosque
        if ($this->kiosk) {
            return $this->kiosk->wilaya;
        }

        return null;
    }
}