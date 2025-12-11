<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\Distributor;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Créer les rôles avec guard_name explicite
        $superAdminRole = Role::firstOrCreate([
            'name' => 'super_admin',
            'guard_name' => 'web'
        ]);
        
        $managerRole = Role::firstOrCreate([
            'name' => 'manager', 
            'guard_name' => 'web'
        ]);
        
        $distributorRole = Role::firstOrCreate([
            'name' => 'distributor',
            'guard_name' => 'web'
        ]);
        
        $employeeRole = Role::firstOrCreate([
            'name' => 'employee',
            'guard_name' => 'web'
        ]);

        // 2. Créer les permissions
        $permissions = [
            // Dashboard
            'view dashboard',
            'view statistics',
            
            // Gestion des utilisateurs
            'view users',
            'create users',
            'edit users',
            'delete users',
            'assign roles',
            
            // Gestion des écoles
            'view schools',
            'create schools',
            'edit schools',
            'delete schools',
            
            // Gestion des livraisons
            'view deliveries',
            'create deliveries',
            'edit deliveries',
            'delete deliveries',
            'view own deliveries',
            
            // Gestion des paiements
            'view payments',
            'create payments',
            'edit payments',
            'delete payments',
            'view own payments',
            
            // Rapports
            'view reports',
            'export reports',
            
            // Configuration
            'manage settings',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        // 3. Assigner toutes les permissions au super admin
        $superAdminRole->givePermissionTo(Permission::all());

        // 4. Permissions pour manager
        $managerPermissions = [
            'view dashboard',
            'view statistics',
            'view schools',
            'create schools',
            'edit schools',
            'view deliveries',
            'create deliveries',
            'edit deliveries',
            'view payments',
            'create payments',
            'edit payments',
            'view reports',
            'export reports',
        ];
        $managerRole->givePermissionTo($managerPermissions);

        // 5. Permissions pour distributeur
        $distributorPermissions = [
            'view dashboard',
            'view own deliveries',
            'create deliveries',
            'view own payments',
            'view statistics',
        ];
        $distributorRole->givePermissionTo($distributorPermissions);

        // 6. Permissions pour employee
        $employeePermissions = [
            'view dashboard',
            'view schools',
            'view deliveries',
            'view payments',
        ];
        $employeeRole->givePermissionTo($employeePermissions);

        // 7. Synchroniser les utilisateurs existants
        $this->syncExistingUsers();
        
        $this->command->info('✅ Rôles et permissions créés avec succès!');
    }
    
    private function syncExistingUsers(): void
    {
        $users = User::all();
        
        foreach ($users as $user) {
            // Basé sur la colonne 'role' existante dans la table users
            switch ($user->role) {
                case 'admin':
                    if (!$user->hasRole('super_admin')) {
                        $user->assignRole('super_admin');
                    }
                    break;
                    
                case 'manager':
                    if (!$user->hasRole('manager')) {
                        $user->assignRole('manager');
                    }
                    break;
                    
                case 'distributor':
                    if (!$user->hasRole('distributor')) {
                        $user->assignRole('distributor');
                        
                        // Créer un profil distributeur si nécessaire
                        if (!$user->distributorProfile) {
                            Distributor::create([
                                'user_id' => $user->id,
                                'name' => $user->name,
                                'wilaya' => $user->wilaya ?? 'Alger',
                                'phone' => $user->phone ?? null,
                            ]);
                        }
                    }
                    break;
                    
                default:
                    // Pour les autres rôles, assigner 'employee'
                    if (!$user->hasAnyRole(['super_admin', 'manager', 'distributor'])) {
                        $user->assignRole('employee');
                    }
                    break;
            }
        }
        
        $this->command->info("✅ {$users->count()} utilisateurs synchronisés avec les rôles Spatie");
    }
}