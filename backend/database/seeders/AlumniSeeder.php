<?php

namespace Database\Seeders;

use App\Models\Alumni;
use App\Models\Cdej;
use App\Models\Cotisation;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AlumniSeeder extends Seeder
{
    public function run(): void
    {
        $cdejs = Cdej::all();
        $niveaux = ['cepe', 'bepc', 'bac', 'bts', 'licence', 'master', 'formation_pro'];
        $nomsFeminins = ['Afi', 'Abla', 'Adjo', 'Akouvi', 'Amevi', 'Ami', 'Atsou', 'Ayawa', 'Béatrice', 'Christine', 'Comfort', 'Dede', 'Edith', 'Esther', 'Eunice', 'Faith', 'Feli', 'Grace', 'Hope', 'Joy', 'Juliet', 'Kafui', 'Kemi', 'Linda', 'Love', 'Mawuena', 'Mercy', 'Miriam', 'Naomi', 'Patience', 'Peace', 'Precious', 'Queen', 'Rachel', 'Rejoice', 'Rose', 'Ruth', 'Sarah', 'Victory', 'Vida'];
        $nomsMasculins = ['Akou', 'Ameko', 'Amouzou', 'Atsu', 'Ayao', 'Blaise', 'Christian', 'Coffi', 'Daniel', 'David', 'Emmanuel', 'Eric', 'Etienne', 'Francis', 'Gilbert', 'Godwin', 'Herve', 'Israel', 'James', 'Jean', 'Joel', 'John', 'Joseph', 'Joshua', 'Kodjo', 'Komlan', 'Kossi', 'Kwami', 'Kwasi', 'Lambert', 'Martin', 'Michael', 'Moses', 'Noel', 'Patrick', 'Paul', 'Peter', 'Philip', 'Prosper', 'Samuel', 'Seth', 'Stephen', 'Theophilus', 'Thomas', 'Victor', 'Vincent', 'William', 'Yawo'];
        $nomsFamille = ['Abla', 'Adebayor', 'Adjoyi', 'Afanou', 'Agbodjan', 'Agyeman', 'Ahadji', 'Ahehehinnou', 'Akakpo', 'Akpalu', 'Amegan', 'Amenyoh', 'Amouzou', 'Amouzouvi', 'Asrafou', 'Assiongbon', 'Atakpame', 'Attiogbe', 'Avo', 'Ayena', 'Balogou', 'Bamouni', 'Bamvim', 'Banoukin', 'Batchassi', 'Bocco', 'Bodjona', 'Bossou', 'Dakpegan', 'Djobo', 'Dodji', 'Dohou', 'Dossou', 'Dovi', 'Dzidzinyo', 'Edoh', 'Egah', 'Eklu', 'Ekoue', 'Folly', 'Gbadamassi', 'Gbaguidi', 'Gbefam', 'Gbetotonou', 'Gnakou', 'Gnamey', 'Gnonlonfoun', 'Hodonou', 'Houenou', 'Hounkpe', 'Kangni', 'Kassa', 'Kiki', 'Kini', 'Kodjo', 'Koffi', 'Kokou', 'Komlan', 'Kossi', 'Kouassi', 'Kouwonou', 'Kpadonou', 'Kpanou', 'Kpatcha', 'Laleye', 'Lamadokou', 'Lawson', 'Lokko', 'Mawufemo', 'Menan', 'Mensa', 'Mewenou', 'Mihyo', 'Nanevi', 'Napo', 'Nassara', 'Nayo', 'Nimako', 'Noglo', 'Nyarko', 'Nyavor', 'Ofori', 'Okpah', 'Ouro-Bang', 'Ouro-Koura', 'Owusu', 'Segbefia', 'Sossou', 'Tachie-Mensons', 'Tamekloe', 'Tchalla', 'Tchamou', 'Tchangai', 'Tete', 'Tokpo', 'Tometi', 'Tona', 'Tossou', 'Vieira', 'Wonkyi', 'Wonyo', 'Yao', 'Yawovi', 'Yegbe', 'Zikpi'];

        // Comptes de test fixes
        $usersTest = [
            ['phone' => '+22890000001', 'role' => 'admin', 'nom' => 'Guénoukpati', 'prenom' => 'Alex'],
            ['phone' => '+22890000002', 'role' => 'cena', 'nom' => 'Komlan', 'prenom' => 'Président'],
            ['phone' => '+22890000003', 'role' => 'ben', 'nom' => 'Koffi', 'prenom' => 'Président'],
            ['phone' => '+22890000004', 'role' => 'alumni', 'nom' => 'Amouzou', 'prenom' => 'Test'],
            ['phone' => '+22890000005', 'role' => 'bla', 'nom' => 'Sossou', 'prenom' => 'Président'],
        ];

        foreach ($usersTest as $test) {
            $user = User::create([
                'phone_whatsapp' => $test['phone'],
                'role' => $test['role'],
                'statut' => 'actif',
                'canal_prefere' => 'whatsapp',
                'derniere_connexion' => now(),
            ]);

            $cdej = $cdejs->random();
            $anneeNaissance = random_int(1985, 2005);
            $annee = (int) date('Y');

            $alumni = Alumni::create([
                'user_id' => $user->id,
                'cdej_id' => $cdej->id,
                'nom' => $test['nom'],
                'prenom' => $test['prenom'],
                'date_naissance' => $anneeNaissance . '-' . str_pad((string) random_int(1, 12), 2, '0', STR_PAD_LEFT) . '-' . str_pad((string) random_int(1, 28), 2, '0', STR_PAD_LEFT),
                'niveau_diplome' => $niveaux[array_rand($niveaux)],
                'statut_compte' => 'valide',
            ]);

            // Cotisation
            Cotisation::create([
                'alumni_id' => $alumni->id,
                'annee' => $annee,
                'montant' => 5000,
                'statut' => 'paye',
                'canal_paiement' => 'flooz',
                'recu_numero' => 'REC-' . $annee . '-' . str_pad((string) random_int(1, 999999), 6, '0', STR_PAD_LEFT),
                'paid_at' => now(),
            ]);
        }

        // 200 alumni de test supplémentaires
        for ($i = 0; $i < 200; $i++) {
            $isFemme = random_int(0, 1) === 1;
            $prenom = $isFemme ? $nomsFeminins[array_rand($nomsFeminins)] : $nomsMasculins[array_rand($nomsMasculins)];
            $nom = $nomsFamille[array_rand($nomsFamille)];
            $phone = '+2289' . str_pad((string) random_int(1000006, 9999999), 7, '0', STR_PAD_LEFT);

            $user = User::create([
                'phone_whatsapp' => $phone,
                'role' => 'alumni',
                'statut' => 'actif',
                'canal_prefere' => 'whatsapp',
            ]);

            $cdej = $cdejs->random();
            $anneeNaissance = random_int(1985, 2005);
            $statutCompte = ['valide', 'valide', 'valide', 'valide', 'valide', 'en_attente', 'en_attente', 'rejete'][random_int(0, 7)];
            $annee = (int) date('Y');

            $alumni = Alumni::create([
                'user_id' => $user->id,
                'cdej_id' => $cdej->id,
                'nom' => $nom,
                'prenom' => $prenom,
                'date_naissance' => $anneeNaissance . '-' . str_pad((string) random_int(1, 12), 2, '0', STR_PAD_LEFT) . '-' . str_pad((string) random_int(1, 28), 2, '0', STR_PAD_LEFT),
                'niveau_diplome' => $niveaux[array_rand($niveaux)],
                'statut_compte' => $statutCompte,
            ]);

            // Cotisations : 60% à jour, 20% en retard, 20% exemptés
            $rand = random_int(1, 100);
            if ($rand <= 60) {
                Cotisation::create([
                    'alumni_id' => $alumni->id,
                    'annee' => $annee,
                    'montant' => 5000,
                    'statut' => 'paye',
                    'canal_paiement' => ['flooz', 'tmoney', 'cash'][random_int(0, 2)],
                    'recu_numero' => 'REC-' . $annee . '-' . str_pad((string) random_int(1, 999999), 6, '0', STR_PAD_LEFT),
                    'paid_at' => now()->subDays(random_int(1, 90)),
                ]);
            } elseif ($rand <= 80) {
                Cotisation::create([
                    'alumni_id' => $alumni->id,
                    'annee' => $annee,
                    'montant' => 5000,
                    'statut' => 'en_retard',
                    'canal_paiement' => null,
                ]);
            } else {
                Cotisation::create([
                    'alumni_id' => $alumni->id,
                    'annee' => $annee,
                    'montant' => 0,
                    'statut' => 'paye',
                    'exemption' => true,
                    'paid_at' => now(),
                ]);
            }
        }
    }
}
