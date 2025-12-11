<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Appeler le seeder des rôles et permissions
        $this->call(RolePermissionSeeder::class);
        
        // 2. Créer ou trouver l'utilisateur super admin
        $superAdminUser = User::firstOrCreate([
            'email' => 'admin@example.com'
        ], [
            'name' => 'Super Admin',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);

        // 3. Attribuer le rôle de super_admin
        $superAdminUser->assignRole('super_admin');
        
        // 4. Appeler le seeder des écoles (IMPORTANT!)
        $this->call(SchoolsSeeder::class);
        
        $this->command->info('✅ Base de données peuplée avec succès!');
        $this->command->info('✅ Super Admin créé: admin@example.com / password123');
        $this->command->info('✅ ' . \App\Models\School::count() . ' écoles importées');
        
        // 5. Optionnel: Créer des données de test
        if (app()->environment('local')) {
            $this->call(TestDataSeeder::class);
        }
    }
}