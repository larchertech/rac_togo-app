<?php

namespace Database\Seeders;

use App\Models\Cdej;
use App\Models\Cluster;
use Illuminate\Database\Seeder;

class CdejSeeder extends Seeder
{
    public function run(): void
    {
        $clusters = Cluster::all()->keyBy('nom');

        $cdejs = [
            // Lomé Ouest - 16 CDEJ
            ['nom' => 'CDEJ Adawlato', 'ville' => 'Lomé', 'region' => 'Maritime', 'cluster' => 'Lomé Ouest'],
            ['nom' => 'CDEJ Agoè-Nyivé', 'ville' => 'Lomé', 'region' => 'Maritime', 'cluster' => 'Lomé Ouest'],
            ['nom' => 'CDEJ Amoutivé', 'ville' => 'Lomé', 'region' => 'Maritime', 'cluster' => 'Lomé Ouest'],
            ['nom' => 'CDEJ Assigamé', 'ville' => 'Lomé', 'region' => 'Maritime', 'cluster' => 'Lomé Ouest'],
            ['nom' => 'CDEJ Attiégou', 'ville' => 'Lomé', 'region' => 'Maritime', 'cluster' => 'Lomé Ouest'],
            ['nom' => 'CDEJ Avédji', 'ville' => 'Lomé', 'region' => 'Maritime', 'cluster' => 'Lomé Ouest'],
            ['nom' => 'CDEJ Bè-Klikamé', 'ville' => 'Lomé', 'region' => 'Maritime', 'cluster' => 'Lomé Ouest'],
            ['nom' => 'CDEJ Doumasséssé', 'ville' => 'Lomé', 'region' => 'Maritime', 'cluster' => 'Lomé Ouest'],
            ['nom' => 'CDEJ Dvorwé', 'ville' => 'Lomé', 'region' => 'Maritime', 'cluster' => 'Lomé Ouest'],
            ['nom' => 'CDEJ Hann-Mariste', 'ville' => 'Lomé', 'region' => 'Maritime', 'cluster' => 'Lomé Ouest'],
            ['nom' => 'CDEJ Hédzranawoé', 'ville' => 'Lomé', 'region' => 'Maritime', 'cluster' => 'Lomé Ouest'],
            ['nom' => 'CDEJ Kodjoviakopé', 'ville' => 'Lomé', 'region' => 'Maritime', 'cluster' => 'Lomé Ouest'],
            ['nom' => 'CDEJ Kovié', 'ville' => 'Lomé', 'region' => 'Maritime', 'cluster' => 'Lomé Ouest'],
            ['nom' => 'CDEJ Tokoin', 'ville' => 'Lomé', 'region' => 'Maritime', 'cluster' => 'Lomé Ouest'],
            ['nom' => 'CDEJ Totsi', 'ville' => 'Lomé', 'region' => 'Maritime', 'cluster' => 'Lomé Ouest'],
            ['nom' => 'CDEJ Wuiti', 'ville' => 'Lomé', 'region' => 'Maritime', 'cluster' => 'Lomé Ouest'],

            // Lomé Est - 16 CDEJ
            ['nom' => 'CDEJ Ablogamé', 'ville' => 'Lomé', 'region' => 'Maritime', 'cluster' => 'Lomé Est'],
            ['nom' => 'CDEJ Adidogomé', 'ville' => 'Lomé', 'region' => 'Maritime', 'cluster' => 'Lomé Est'],
            ['nom' => 'CDEJ Adobou', 'ville' => 'Lomé', 'region' => 'Maritime', 'cluster' => 'Lomé Est'],
            ['nom' => 'CDEJ Agbalépédogan', 'ville' => 'Lomé', 'region' => 'Maritime', 'cluster' => 'Lomé Est'],
            ['nom' => 'CDEJ Akodéséwa', 'ville' => 'Lomé', 'region' => 'Maritime', 'cluster' => 'Lomé Est'],
            ['nom' => 'CDEJ Anfamé', 'ville' => 'Lomé', 'region' => 'Maritime', 'cluster' => 'Lomé Est'],
            ['nom' => 'CDEJ Atiégou', 'ville' => 'Lomé', 'region' => 'Maritime', 'cluster' => 'Lomé Est'],
            ['nom' => 'CDEJ Baguida', 'ville' => 'Lomé', 'region' => 'Maritime', 'cluster' => 'Lomé Est'],
            ['nom' => 'CDEJ Dékon', 'ville' => 'Lomé', 'region' => 'Maritime', 'cluster' => 'Lomé Est'],
            ['nom' => 'CDEJ Djidjolé', 'ville' => 'Lomé', 'region' => 'Maritime', 'cluster' => 'Lomé Est'],
            ['nom' => 'CDEJ Gbossimé', 'ville' => 'Lomé', 'region' => 'Maritime', 'cluster' => 'Lomé Est'],
            ['nom' => 'CDEJ Kégué', 'ville' => 'Lomé', 'region' => 'Maritime', 'cluster' => 'Lomé Est'],
            ['nom' => 'CDEJ Kpogan', 'ville' => 'Lomé', 'region' => 'Maritime', 'cluster' => 'Lomé Est'],
            ['nom' => 'CDEJ Lac 1', 'ville' => 'Lomé', 'region' => 'Maritime', 'cluster' => 'Lomé Est'],
            ['nom' => 'CDEJ Sagbado', 'ville' => 'Lomé', 'region' => 'Maritime', 'cluster' => 'Lomé Est'],
            ['nom' => 'CDEJ Ségbé', 'ville' => 'Lomé', 'region' => 'Maritime', 'cluster' => 'Lomé Est'],

            // Zio Sud - 14 CDEJ
            ['nom' => 'CDEJ Afagnan', 'ville' => 'Afagnan', 'region' => 'Maritime', 'cluster' => 'Zio Sud (Maritime)'],
            ['nom' => 'CDEJ Aného', 'ville' => 'Aného', 'region' => 'Maritime', 'cluster' => 'Zio Sud (Maritime)'],
            ['nom' => 'CDEJ Badja', 'ville' => 'Badja', 'region' => 'Maritime', 'cluster' => 'Zio Sud (Maritime)'],
            ['nom' => 'CDEJ Bato', 'ville' => 'Bato', 'region' => 'Maritime', 'cluster' => 'Zio Sud (Maritime)'],
            ['nom' => 'CDEJ Dabolo', 'ville' => 'Dabolo', 'region' => 'Maritime', 'cluster' => 'Zio Sud (Maritime)'],
            ['nom' => 'CDEJ Glidji', 'ville' => 'Glidji', 'region' => 'Maritime', 'cluster' => 'Zio Sud (Maritime)'],
            ['nom' => 'CDEJ Gnivi', 'ville' => 'Gnivi', 'region' => 'Maritime', 'cluster' => 'Zio Sud (Maritime)'],
            ['nom' => 'CDEJ Ketao', 'ville' => 'Ketao', 'region' => 'Maritime', 'cluster' => 'Zio Sud (Maritime)'],
            ['nom' => 'CDEJ Kpéssi', 'ville' => 'Kpéssi', 'region' => 'Maritime', 'cluster' => 'Zio Sud (Maritime)'],
            ['nom' => 'CDEJ Malaré', 'ville' => 'Malaré', 'region' => 'Maritime', 'cluster' => 'Zio Sud (Maritime)'],
            ['nom' => 'CDEJ Péhunco', 'ville' => 'Péhunco', 'region' => 'Maritime', 'cluster' => 'Zio Sud (Maritime)'],
            ['nom' => 'CDEJ Togoville', 'ville' => 'Togoville', 'region' => 'Maritime', 'cluster' => 'Zio Sud (Maritime)'],
            ['nom' => 'CDEJ Vo-Koutime', 'ville' => 'Vo-Koutime', 'region' => 'Maritime', 'cluster' => 'Zio Sud (Maritime)'],
            ['nom' => 'CDEJ Zooti', 'ville' => 'Zooti', 'region' => 'Maritime', 'cluster' => 'Zio Sud (Maritime)'],

            // Zio Centre - 12 CDEJ
            ['nom' => 'CDEJ Adzin', 'ville' => 'Adzin', 'region' => 'Maritime', 'cluster' => 'Zio Centre'],
            ['nom' => 'CDEJ Agbelou', 'ville' => 'Agbelou', 'region' => 'Maritime', 'cluster' => 'Zio Centre'],
            ['nom' => 'CDEJ Atchoukpa', 'ville' => 'Atchoukpa', 'region' => 'Maritime', 'cluster' => 'Zio Centre'],
            ['nom' => 'CDEJ Davié', 'ville' => 'Davié', 'region' => 'Maritime', 'cluster' => 'Zio Centre'],
            ['nom' => 'CDEJ Djagblé', 'ville' => 'Djagblé', 'region' => 'Maritime', 'cluster' => 'Zio Centre'],
            ['nom' => 'CDEJ Dzrékpo', 'ville' => 'Dzrékpo', 'region' => 'Maritime', 'cluster' => 'Zio Centre'],
            ['nom' => 'CDEJ Gamé', 'ville' => 'Gamé', 'region' => 'Maritime', 'cluster' => 'Zio Centre'],
            ['nom' => 'CDEJ Kovié-Sud', 'ville' => 'Kovié-Sud', 'region' => 'Maritime', 'cluster' => 'Zio Centre'],
            ['nom' => 'CDEJ Mission-Tové', 'ville' => 'Mission-Tové', 'region' => 'Maritime', 'cluster' => 'Zio Centre'],
            ['nom' => 'CDEJ Noèpé', 'ville' => 'Noèpé', 'region' => 'Maritime', 'cluster' => 'Zio Centre'],
            ['nom' => 'CDEJ Wli', 'ville' => 'Wli', 'region' => 'Maritime', 'cluster' => 'Zio Centre'],
            ['nom' => 'CDEJ Zio-Agbo', 'ville' => 'Zio-Agbo', 'region' => 'Maritime', 'cluster' => 'Zio Centre'],

            // Vo Nord - 10 CDEJ
            ['nom' => 'CDEJ Agou-Gbo', 'ville' => 'Agou-Gbo', 'region' => 'Maritime', 'cluster' => 'Vo Nord'],
            ['nom' => 'CDEJ Djidjolé-Vo', 'ville' => 'Djidjolé', 'region' => 'Maritime', 'cluster' => 'Vo Nord'],
            ['nom' => 'CDEJ Goumé', 'ville' => 'Goumé', 'region' => 'Maritime', 'cluster' => 'Vo Nord'],
            ['nom' => 'CDEJ Kpélé-Ana', 'ville' => 'Kpélé-Ana', 'region' => 'Maritime', 'cluster' => 'Vo Nord'],
            ['nom' => 'CDEJ Kpélé-Dzigbé', 'ville' => 'Kpélé-Dzigbé', 'region' => 'Maritime', 'cluster' => 'Vo Nord'],
            ['nom' => 'CDEJ Ohumpi', 'ville' => 'Ohumpi', 'region' => 'Maritime', 'cluster' => 'Vo Nord'],
            ['nom' => 'CDEJ Tokpli', 'ville' => 'Tokpli', 'region' => 'Maritime', 'cluster' => 'Vo Nord'],
            ['nom' => 'CDEJ Vo-Adéta', 'ville' => 'Vo-Adéta', 'region' => 'Maritime', 'cluster' => 'Vo Nord'],
            ['nom' => 'CDEJ Vo-Djéto', 'ville' => 'Vo-Djéto', 'region' => 'Maritime', 'cluster' => 'Vo Nord'],
            ['nom' => 'CDEJ Vo-Kpodji', 'ville' => 'Vo-Kpodji', 'region' => 'Maritime', 'cluster' => 'Vo Nord'],

            // Vo Sud - 8 CDEJ
            ['nom' => 'CDEJ Agbanakin', 'ville' => 'Agbanakin', 'region' => 'Maritime', 'cluster' => 'Vo Sud'],
            ['nom' => 'CDEJ Akpété', 'ville' => 'Akpété', 'region' => 'Maritime', 'cluster' => 'Vo Sud'],
            ['nom' => 'CDEJ Dadja', 'ville' => 'Dadja', 'region' => 'Maritime', 'cluster' => 'Vo Sud'],
            ['nom' => 'CDEJ Gboto', 'ville' => 'Gboto', 'region' => 'Maritime', 'cluster' => 'Vo Sud'],
            ['nom' => 'CDEJ Kpété-Da', 'ville' => 'Kpété-Da', 'region' => 'Maritime', 'cluster' => 'Vo Sud'],
            ['nom' => 'CDEJ Sédomé', 'ville' => 'Sédomé', 'region' => 'Maritime', 'cluster' => 'Vo Sud'],
            ['nom' => 'CDEJ Témédja', 'ville' => 'Témédja', 'region' => 'Maritime', 'cluster' => 'Vo Sud'],
            ['nom' => 'CDEJ Vo-Sud Centre', 'ville' => 'Vo-Sud', 'region' => 'Maritime', 'cluster' => 'Vo Sud'],

            // Golfe Ouest - 8 CDEJ
            ['nom' => 'CDEJ Agoué', 'ville' => 'Agoué', 'region' => 'Maritime', 'cluster' => 'Golfe Ouest'],
            ['nom' => 'CDEJ Assouma', 'ville' => 'Assouma', 'region' => 'Maritime', 'cluster' => 'Golfe Ouest'],
            ['nom' => 'CDEJ Baguida-Maritime', 'ville' => 'Baguida', 'region' => 'Maritime', 'cluster' => 'Golfe Ouest'],
            ['nom' => 'CDEJ Gbetsogbé', 'ville' => 'Gbetsogbé', 'region' => 'Maritime', 'cluster' => 'Golfe Ouest'],
            ['nom' => 'CDEJ Kpogan-Montagne', 'ville' => 'Kpogan', 'region' => 'Maritime', 'cluster' => 'Golfe Ouest'],
            ['nom' => 'CDEJ Légbassito', 'ville' => 'Légbassito', 'region' => 'Maritime', 'cluster' => 'Golfe Ouest'],
            ['nom' => 'CDEJ Sévagan', 'ville' => 'Sévagan', 'region' => 'Maritime', 'cluster' => 'Golfe Ouest'],
            ['nom' => 'CDEJ Togblékopé', 'ville' => 'Togblékopé', 'region' => 'Maritime', 'cluster' => 'Golfe Ouest'],

            // Lacs & Bas Mono - 8 CDEJ
            ['nom' => 'CDEJ Aného-Lac', 'ville' => 'Aného', 'region' => 'Maritime', 'cluster' => 'Lacs & Bas Mono'],
            ['nom' => 'CDEJ Bassarako', 'ville' => 'Bassarako', 'region' => 'Maritime', 'cluster' => 'Lacs & Bas Mono'],
            ['nom' => 'CDEJ Davié-Mono', 'ville' => 'Davié', 'region' => 'Maritime', 'cluster' => 'Lacs & Bas Mono'],
            ['nom' => 'CDEJ Glidji-Lac', 'ville' => 'Glidji', 'region' => 'Maritime', 'cluster' => 'Lacs & Bas Mono'],
            ['nom' => 'CDEJ Gnivi-Mono', 'ville' => 'Gnivi', 'region' => 'Maritime', 'cluster' => 'Lacs & Bas Mono'],
            ['nom' => 'CDEJ Ketao-Lac', 'ville' => 'Ketao', 'region' => 'Maritime', 'cluster' => 'Lacs & Bas Mono'],
            ['nom' => 'CDEJ Kopéto', 'ville' => 'Kopéto', 'region' => 'Maritime', 'cluster' => 'Lacs & Bas Mono'],
            ['nom' => 'CDEJ Togoville-Lac', 'ville' => 'Togoville', 'region' => 'Maritime', 'cluster' => 'Lacs & Bas Mono'],

            // Yoto Sud - 6 CDEJ
            ['nom' => 'CDEJ Adédji', 'ville' => 'Adédji', 'region' => 'Maritime', 'cluster' => 'Yoto Sud'],
            ['nom' => 'CDEJ Ikpinlè', 'ville' => 'Ikpinlè', 'region' => 'Maritime', 'cluster' => 'Yoto Sud'],
            ['nom' => 'CDEJ Konfo', 'ville' => 'Konfo', 'region' => 'Maritime', 'cluster' => 'Yoto Sud'],
            ['nom' => 'CDEJ Oumako', 'ville' => 'Oumako', 'region' => 'Maritime', 'cluster' => 'Yoto Sud'],
            ['nom' => 'CDEJ Tabligbo', 'ville' => 'Tabligbo', 'region' => 'Maritime', 'cluster' => 'Yoto Sud'],
            ['nom' => 'CDEJ Yoto-Sud Centre', 'ville' => 'Yoto', 'region' => 'Maritime', 'cluster' => 'Yoto Sud'],

            // Ave - 8 CDEJ
            ['nom' => 'CDEJ Ave-Nord', 'ville' => 'Ave-Nord', 'region' => 'Plateaux', 'cluster' => 'Ave'],
            ['nom' => 'CDEJ Ave-Sud', 'ville' => 'Ave-Sud', 'region' => 'Plateaux', 'cluster' => 'Ave'],
            ['nom' => 'CDEJ Défalé', 'ville' => 'Défalé', 'region' => 'Plateaux', 'cluster' => 'Ave'],
            ['nom' => 'CDEJ Kpélé-Avé', 'ville' => 'Kpélé-Avé', 'region' => 'Plateaux', 'cluster' => 'Ave'],
            ['nom' => 'CDEJ Notsé', 'ville' => 'Notsé', 'region' => 'Plateaux', 'cluster' => 'Ave'],
            ['nom' => 'CDEJ Oudada', 'ville' => 'Oudada', 'region' => 'Plateaux', 'cluster' => 'Ave'],
            ['nom' => 'CDEJ Sodo', 'ville' => 'Sodo', 'region' => 'Plateaux', 'cluster' => 'Ave'],
            ['nom' => 'CDEJ Yévié', 'ville' => 'Yévié', 'region' => 'Plateaux', 'cluster' => 'Ave'],

            // Avé-Agou - 6 CDEJ
            ['nom' => 'CDEJ Agou-Kopé', 'ville' => 'Agou-Kopé', 'region' => 'Plateaux', 'cluster' => 'Avé-Agou'],
            ['nom' => 'CDEJ Agou-Sud', 'ville' => 'Agou-Sud', 'region' => 'Plateaux', 'cluster' => 'Avé-Agou'],
            ['nom' => 'CDEJ Avé-Gare', 'ville' => 'Avé-Gare', 'region' => 'Plateaux', 'cluster' => 'Avé-Agou'],
            ['nom' => 'CDEJ Kpalimé-Est', 'ville' => 'Kpalimé', 'region' => 'Plateaux', 'cluster' => 'Avé-Agou'],
            ['nom' => 'CDEJ Kpalimé-Ouest', 'ville' => 'Kpalimé', 'region' => 'Plateaux', 'cluster' => 'Avé-Agou'],
            ['nom' => 'CDEJ Yévié-Agou', 'ville' => 'Yévié', 'region' => 'Plateaux', 'cluster' => 'Avé-Agou'],

            // Agou - 6 CDEJ
            ['nom' => 'CDEJ Adélé', 'ville' => 'Adélé', 'region' => 'Plateaux', 'cluster' => 'Agou'],
            ['nom' => 'CDEJ Agou-Centre', 'ville' => 'Agou', 'region' => 'Plateaux', 'cluster' => 'Agou'],
            ['nom' => 'CDEJ Amoussou', 'ville' => 'Amoussou', 'region' => 'Plateaux', 'cluster' => 'Agou'],
            ['nom' => 'CDEJ Danyi', 'ville' => 'Danyi', 'region' => 'Plateaux', 'cluster' => 'Agou'],
            ['nom' => 'CDEJ Kloto', 'ville' => 'Kloto', 'region' => 'Plateaux', 'cluster' => 'Agou'],
            ['nom' => 'CDEJ Kpadapé', 'ville' => 'Kpadapé', 'region' => 'Plateaux', 'cluster' => 'Agou'],

            // Haho Sud - 6 CDEJ
            ['nom' => 'CDEJ Aklakou', 'ville' => 'Aklakou', 'region' => 'Plateaux', 'cluster' => 'Haho Sud'],
            ['nom' => 'CDEJ Anié', 'ville' => 'Anié', 'region' => 'Plateaux', 'cluster' => 'Haho Sud'],
            ['nom' => 'CDEJ Dodo', 'ville' => 'Dodo', 'region' => 'Plateaux', 'cluster' => 'Haho Sud'],
            ['nom' => 'CDEJ Haho-Centre', 'ville' => 'Haho', 'region' => 'Plateaux', 'cluster' => 'Haho Sud'],
            ['nom' => 'CDEJ Tchekpo', 'ville' => 'Tchekpo', 'region' => 'Plateaux', 'cluster' => 'Haho Sud'],
            ['nom' => 'CDEJ Togblo', 'ville' => 'Togblo', 'region' => 'Plateaux', 'cluster' => 'Haho Sud'],

            // Kpele - 6 CDEJ
            ['nom' => 'CDEJ Aklobi', 'ville' => 'Aklobi', 'region' => 'Plateaux', 'cluster' => 'Kpele'],
            ['nom' => 'CDEJ Avétonou', 'ville' => 'Avétonou', 'region' => 'Plateaux', 'cluster' => 'Kpele'],
            ['nom' => 'CDEJ Kpélé-Gare', 'ville' => 'Kpélé', 'region' => 'Plateaux', 'cluster' => 'Kpele'],
            ['nom' => 'CDEJ Kpélé-Nord', 'ville' => 'Kpélé', 'region' => 'Plateaux', 'cluster' => 'Kpele'],
            ['nom' => 'CDEJ Kpélé-Sud', 'ville' => 'Kpélé', 'region' => 'Plateaux', 'cluster' => 'Kpele'],
            ['nom' => 'CDEJ Womé', 'ville' => 'Womé', 'region' => 'Plateaux', 'cluster' => 'Kpele'],

            // Ogou - 6 CDEJ
            ['nom' => 'CDEJ Atakpamé', 'ville' => 'Atakpamé', 'region' => 'Cent.-Plat.', 'cluster' => 'Ogou'],
            ['nom' => 'CDEJ Djama', 'ville' => 'Djama', 'region' => 'Cent.-Plat.', 'cluster' => 'Ogou'],
            ['nom' => 'CDEJ Kpogno', 'ville' => 'Kpogno', 'region' => 'Cent.-Plat.', 'cluster' => 'Ogou'],
            ['nom' => 'CDEJ Ogou-Centre', 'ville' => 'Ogou', 'region' => 'Cent.-Plat.', 'cluster' => 'Ogou'],
            ['nom' => 'CDEJ Sokodé-Est', 'ville' => 'Sokodé', 'region' => 'Cent.-Plat.', 'cluster' => 'Ogou'],
            ['nom' => 'CDEJ Sokodé-Ouest', 'ville' => 'Sokodé', 'region' => 'Cent.-Plat.', 'cluster' => 'Ogou'],

            // Anie - 6 CDEJ
            ['nom' => 'CDEJ Anie-Centre', 'ville' => 'Anie', 'region' => 'Cent.-Plat.', 'cluster' => 'Anie'],
            ['nom' => 'CDEJ Djan', 'ville' => 'Djan', 'region' => 'Cent.-Plat.', 'cluster' => 'Anie'],
            ['nom' => 'CDEJ Kpessi', 'ville' => 'Kpessi', 'region' => 'Cent.-Plat.', 'cluster' => 'Anie'],
            ['nom' => 'CDEJ Morétan', 'ville' => 'Morétan', 'region' => 'Cent.-Plat.', 'cluster' => 'Anie'],
            ['nom' => 'CDEJ Sessaro', 'ville' => 'Sessaro', 'region' => 'Cent.-Plat.', 'cluster' => 'Anie'],
            ['nom' => 'CDEJ Tchamba', 'ville' => 'Tchamba', 'region' => 'Cent.-Plat.', 'cluster' => 'Anie'],

            // Binah - 6 CDEJ
            ['nom' => 'CDEJ Binah-Centre', 'ville' => 'Binah', 'region' => 'Kara-Sav.', 'cluster' => 'Binah'],
            ['nom' => 'CDEJ Guérin-Kouka', 'ville' => 'Guérin-Kouka', 'region' => 'Kara-Sav.', 'cluster' => 'Binah'],
            ['nom' => 'CDEJ Kpéssi-Binah', 'ville' => 'Kpéssi', 'region' => 'Kara-Sav.', 'cluster' => 'Binah'],
            ['nom' => 'CDEJ Pagala', 'ville' => 'Pagala', 'region' => 'Kara-Sav.', 'cluster' => 'Binah'],
            ['nom' => 'CDEJ Tchamba-Binah', 'ville' => 'Tchamba', 'region' => 'Kara-Sav.', 'cluster' => 'Binah'],
            ['nom' => 'CDEJ Wassangbo', 'ville' => 'Wassangbo', 'region' => 'Kara-Sav.', 'cluster' => 'Binah'],

            // Doufelgou - 6 CDEJ
            ['nom' => 'CDEJ Dougou', 'ville' => 'Dougou', 'region' => 'Kara-Sav.', 'cluster' => 'Doufelgou'],
            ['nom' => 'CDEJ Doufelgou-Centre', 'ville' => 'Doufelgou', 'region' => 'Kara-Sav.', 'cluster' => 'Doufelgou'],
            ['nom' => 'CDEJ Kouka', 'ville' => 'Kouka', 'region' => 'Kara-Sav.', 'cluster' => 'Doufelgou'],
            ['nom' => 'CDEJ Niamtougou', 'ville' => 'Niamtougou', 'region' => 'Kara-Sav.', 'cluster' => 'Doufelgou'],
            ['nom' => 'CDEJ Pya', 'ville' => 'Pya', 'region' => 'Kara-Sav.', 'cluster' => 'Doufelgou'],
            ['nom' => 'CDEJ Tchéré', 'ville' => 'Tchéré', 'region' => 'Kara-Sav.', 'cluster' => 'Doufelgou'],

            // Kozah - 6 CDEJ
            ['nom' => 'CDEJ Kara-Centre', 'ville' => 'Kara', 'region' => 'Kara-Sav.', 'cluster' => 'Kozah'],
            ['nom' => 'CDEJ Kétao-Kara', 'ville' => 'Kétao', 'region' => 'Kara-Sav.', 'cluster' => 'Kozah'],
            ['nom' => 'CDEJ Kozo', 'ville' => 'Kozo', 'region' => 'Kara-Sav.', 'cluster' => 'Kozah'],
            ['nom' => 'CDEJ Kozah-Est', 'ville' => 'Kozah', 'region' => 'Kara-Sav.', 'cluster' => 'Kozah'],
            ['nom' => 'CDEJ Kozah-Ouest', 'ville' => 'Kozah', 'region' => 'Kara-Sav.', 'cluster' => 'Kozah'],
            ['nom' => 'CDEJ Tcharé', 'ville' => 'Tcharé', 'region' => 'Kara-Sav.', 'cluster' => 'Kozah'],
        ];

        foreach ($cdejs as $cdej) {
            $clusterNom = $cdej['cluster'];
            unset($cdej['cluster']);
            $cdej['cluster_id'] = $clusters[$clusterNom]?->id;
            Cdej::create($cdej);
        }
    }
}
