<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Tous
            'profil.voir',
            'profil.modifier',
            'cotisation.payer',
            'election.voir',

            // Alumni+
            'candidature.soumettre',
            'vote.cast',

            // BLA, BCA, BEN, Admin
            'membres.gerer',

            // CENA, CEC, CEL, Admin
            'candidature.valider',
            'candidature.rejeter',
            'liste.electorale.voir',

            // CENA, Admin
            'election.gerer',
            'election.proclamer',

            // BEN, CENA, Admin
            'dashboard.national',
            'rapport.financier',

            // Admin only
            'config.app',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'sanctum']);
        }

        $roles = [
            'alumni',
            'bla',
            'bca',
            'ben',
            'cena',
            'cec',
            'cel',
            'admin',
        ];

        foreach ($roles as $role) {
            Role::create(['name' => $role, 'guard_name' => 'sanctum']);
        }

        // Assign permissions
        // Tous les rôles basiques
        $allRoles = Role::whereIn('name', ['alumni', 'bla', 'bca', 'ben', 'cena', 'cec', 'cel', 'admin'])->get();
        foreach ($allRoles as $role) {
            $role->givePermissionTo(['profil.voir', 'profil.modifier', 'cotisation.payer', 'election.voir']);
        }

        // Alumni+
        $voteRoles = Role::whereIn('name', ['alumni', 'bla', 'bca', 'ben'])->get();
        foreach ($voteRoles as $role) {
            $role->givePermissionTo(['candidature.soumettre', 'vote.cast']);
        }

        // BLA, BCA, BEN, Admin
        $managerRoles = Role::whereIn('name', ['bla', 'bca', 'ben', 'admin'])->get();
        foreach ($managerRoles as $role) {
            $role->givePermissionTo('membres.gerer');
        }

        // Commissions
        $commissionRoles = Role::whereIn('name', ['cena', 'cec', 'cel', 'admin'])->get();
        foreach ($commissionRoles as $role) {
            $role->givePermissionTo(['candidature.valider', 'candidature.rejeter', 'liste.electorale.voir']);
        }

        // CENA, Admin
        $cenaAdmin = Role::whereIn('name', ['cena', 'admin'])->get();
        foreach ($cenaAdmin as $role) {
            $role->givePermissionTo(['election.gerer', 'election.proclamer']);
        }

        // BEN, CENA, Admin
        $nationalRoles = Role::whereIn('name', ['ben', 'cena', 'admin'])->get();
        foreach ($nationalRoles as $role) {
            $role->givePermissionTo(['dashboard.national', 'rapport.financier']);
        }

        // Admin only
        Role::where('name', 'admin')->first()->givePermissionTo('config.app');
    }
}
