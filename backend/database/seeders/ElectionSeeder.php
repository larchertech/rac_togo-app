<?php

namespace Database\Seeders;

use App\Models\Cdej;
use App\Models\Cluster;
use App\Models\Commission;
use App\Models\Election;
use Illuminate\Database\Seeder;

class ElectionSeeder extends Seeder
{
    public function run(): void
    {
        $annee = (int) date('Y');

        // Créer une commission CENA nationale
        $commissionCena = Commission::create([
            'type' => 'cena',
            'niveau' => 'national',
            'membres' => [],
            'config' => ['delai_recours' => 5],
        ]);

        // Élections BLA pour quelques CDEJ
        $cdejs = Cdej::inRandomOrder()->limit(10)->get();
        foreach ($cdejs as $cdej) {
            Election::create([
                'type' => 'bla',
                'niveau' => (string) $cdej->id,
                'statut' => 'preparation',
                'commission_id' => $commissionCena->id,
                'ouverture_candidatures' => now()->addDays(10),
                'cloture_candidatures' => now()->addDays(40),
                'date_vote' => now()->create(2026, 5, 9),
                'heure_ouverture_vote' => now()->createFromTime(7, 0),
                'heure_cloture_vote' => now()->createFromTime(18, 0),
                'mode_scrutin' => 'majoritaire_simple',
                'postes' => ['president', 'vice_president', 'sg', 'tresorier'],
                'config' => ['exemption_cotisation' => false],
            ]);
        }

        // Élections BCA pour quelques clusters
        $clusters = Cluster::inRandomOrder()->limit(5)->get();
        foreach ($clusters as $cluster) {
            Election::create([
                'type' => 'bca',
                'niveau' => (string) $cluster->id,
                'statut' => 'brouillon',
                'commission_id' => $commissionCena->id,
                'ouverture_candidatures' => now()->addDays(20),
                'cloture_candidatures' => now()->addDays(50),
                'date_vote' => now()->create(2026, 5, 30),
                'heure_ouverture_vote' => now()->createFromTime(7, 0),
                'heure_cloture_vote' => now()->createFromTime(18, 0),
                'mode_scrutin' => 'majoritaire_simple',
                'postes' => ['president', 'vice_president', 'sg', 'tresorier', 'conseiller'],
                'config' => ['exemption_cotisation' => false],
            ]);
        }

        // Élection BE nationale
        Election::create([
            'type' => 'be',
            'niveau' => 'national',
            'statut' => 'brouillon',
            'commission_id' => $commissionCena->id,
            'ouverture_candidatures' => now()->addDays(30),
            'cloture_candidatures' => now()->addDays(60),
            'date_vote' => now()->create(2026, 6, 20),
            'heure_ouverture_vote' => now()->createFromTime(7, 0),
            'heure_cloture_vote' => now()->createFromTime(18, 0),
            'mode_scrutin' => 'majoritaire_absolu',
            'postes' => ['president', 'vice_president', 'sg', 'tresorier', 'conseiller'],
            'config' => ['exemption_cotisation' => false],
        ]);
    }
}
