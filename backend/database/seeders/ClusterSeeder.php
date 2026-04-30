<?php

namespace Database\Seeders;

use App\Models\Cluster;
use Illuminate\Database\Seeder;

class ClusterSeeder extends Seeder
{
    public function run(): void
    {
        $clusters = [
            ['nom' => 'Golfe Ouest', 'region' => 'Maritime'],
            ['nom' => 'Lacs & Bas Mono', 'region' => 'Maritime'],
            ['nom' => 'Lomé Est', 'region' => 'Maritime'],
            ['nom' => 'Lomé Ouest', 'region' => 'Maritime'],
            ['nom' => 'Vo Nord', 'region' => 'Maritime'],
            ['nom' => 'Vo Sud', 'region' => 'Maritime'],
            ['nom' => 'Yoto Sud', 'region' => 'Maritime'],
            ['nom' => 'Zio Centre', 'region' => 'Maritime'],
            ['nom' => 'Zio Sud (Maritime)', 'region' => 'Maritime'],
            ['nom' => 'Ave', 'region' => 'Plateaux'],
            ['nom' => 'Avé-Agou', 'region' => 'Plateaux'],
            ['nom' => 'Agou', 'region' => 'Plateaux'],
            ['nom' => 'Haho Sud', 'region' => 'Plateaux'],
            ['nom' => 'Kpele', 'region' => 'Plateaux'],
            ['nom' => 'Ogou', 'region' => 'Cent.-Plat.'],
            ['nom' => 'Anie', 'region' => 'Cent.-Plat.'],
            ['nom' => 'Binah', 'region' => 'Kara-Sav.'],
            ['nom' => 'Doufelgou', 'region' => 'Kara-Sav.'],
            ['nom' => 'Kozah', 'region' => 'Kara-Sav.'],
        ];

        foreach ($clusters as $cluster) {
            Cluster::create($cluster);
        }
    }
}
