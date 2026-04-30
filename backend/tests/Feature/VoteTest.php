<?php

namespace Tests\Feature;

use App\Models\Alumni;
use App\Models\Candidature;
use App\Models\Cdej;
use App\Models\Cluster;
use App\Models\Cotisation;
use App\Models\Election;
use App\Models\Emargement;
use App\Models\Resultat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class VoteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $cluster = Cluster::factory()->create();
        $cdej = Cdej::factory()->create(['cluster_id' => $cluster->id]);

        $this->electeur = User::factory()->create([
            'role' => 'alumni',
            'phone_whatsapp' => '+22890000010',
        ]);

        $this->alumni = Alumni::factory()->create([
            'user_id' => $this->electeur->id,
            'cdej_id' => $cdej->id,
            'statut_compte' => 'valide',
        ]);

        Cotisation::factory()->create([
            'alumni_id' => $this->alumni->id,
            'annee' => (int) date('Y'),
            'statut' => 'paye',
        ]);

        $this->election = Election::factory()->create([
            'type' => 'bla',
            'niveau' => (string) $cdej->id,
            'statut' => 'vote',
            'date_vote' => now(),
            'heure_ouverture_vote' => now()->subHour(),
            'heure_cloture_vote' => now()->addHour(),
            'postes' => ['president'],
            'mode_scrutin' => 'majoritaire_simple',
        ]);

        $this->candidat = User::factory()->create(['role' => 'alumni']);
        $alumniCandidat = Alumni::factory()->create([
            'user_id' => $this->candidat->id,
            'cdej_id' => $cdej->id,
            'statut_compte' => 'valide',
        ]);

        $this->candidature = Candidature::factory()->create([
            'election_id' => $this->election->id,
            'alumni_id' => $alumniCandidat->id,
            'poste' => 'president',
            'statut' => 'valide',
        ]);

        Resultat::factory()->create([
            'election_id' => $this->election->id,
            'candidature_id' => $this->candidature->id,
            'nb_voix' => 0,
        ]);
    }

    public function test_alumni_peut_voter_une_fois()
    {
        $response = $this->actingAs($this->electeur)->postJson(
            "/api/v1/elections/{$this->election->id}/vote",
            ['votes' => ['president' => $this->candidature->id]]
        );

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('emargement', [
            'election_id' => $this->election->id,
            'electeur_id' => $this->electeur->id,
        ]);

        $this->assertDatabaseHas('resultats', [
            'election_id' => $this->election->id,
            'candidature_id' => $this->candidature->id,
            'nb_voix' => 1,
        ]);
    }

    public function test_alumni_ne_peut_pas_voter_deux_fois()
    {
        // Premier vote
        $this->actingAs($this->electeur)->postJson(
            "/api/v1/elections/{$this->election->id}/vote",
            ['votes' => ['president' => $this->candidature->id]]
        );

        // Deuxième vote
        $response = $this->actingAs($this->electeur)->postJson(
            "/api/v1/elections/{$this->election->id}/vote",
            ['votes' => ['president' => $this->candidature->id]]
        );

        $response->assertStatus(403)
            ->assertJson(['success' => false]);
    }

    public function test_vote_ne_stocke_pas_le_choix_de_electeur()
    {
        $this->assertFalse(
            Schema::hasColumn('emargement', 'candidat_id'),
            'VIOLATION SECRET DU VOTE : emargement ne doit jamais avoir candidat_id'
        );

        $this->assertFalse(
            Schema::hasColumn('resultats', 'electeur_id'),
            'VIOLATION SECRET DU VOTE : resultats ne doit jamais avoir electeur_id'
        );
    }

    public function test_membre_commission_ne_peut_pas_voter()
    {
        $commission = \App\Models\Commission::factory()->create([
            'type' => 'cena',
            'niveau' => 'national',
            'membres' => [$this->electeur->id],
        ]);

        $this->election->update(['commission_id' => $commission->id]);

        $response = $this->actingAs($this->electeur)->postJson(
            "/api/v1/elections/{$this->election->id}/vote",
            ['votes' => ['president' => $this->candidature->id]]
        );

        $response->assertStatus(403);
    }

    public function test_cotisation_non_a_jour_bloque_vote()
    {
        Cotisation::where('alumni_id', $this->alumni->id)->update(['statut' => 'en_retard']);

        $response = $this->actingAs($this->electeur)->postJson(
            "/api/v1/elections/{$this->election->id}/vote",
            ['votes' => ['president' => $this->candidature->id]]
        );

        $response->assertStatus(403)
            ->assertJson(['message' => 'Votre cotisation n\'est pas à jour.']);
    }

    public function test_exemption_cotisation_permet_vote()
    {
        Cotisation::where('alumni_id', $this->alumni->id)->update(['statut' => 'en_retard']);

        $this->election->update([
            'config' => ['exemption_cotisation' => true],
        ]);

        $response = $this->actingAs($this->electeur)->postJson(
            "/api/v1/elections/{$this->election->id}/vote",
            ['votes' => ['president' => $this->candidature->id]]
        );

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }
}
