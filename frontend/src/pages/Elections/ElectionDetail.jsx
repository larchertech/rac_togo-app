import React from 'react'
import { useParams, Link } from 'react-router-dom'
import { useElection, useElectionCandidatures, useElectionResultats } from '../../hooks/useElection'
import { Card, CardContent, CardHeader, CardTitle } from '../../components/ui/Card'
import { Badge } from '../../components/ui/Badge'
import { Button } from '../../components/ui/Button'
import { Loader2, Vote, FileText, Users, Trophy } from 'lucide-react'

const typeLabels = {
  bla: 'Bureau Local Alumni',
  bca: 'Bureau Cluster Alumni',
  be: 'Bureau Exécutif National',
}

export default function ElectionDetail() {
  const { id } = useParams()
  const { data: election, isLoading } = useElection(id)
  const { data: candidatures } = useElectionCandidatures(id)
  const { data: resultatsData } = useElectionResultats(id)

  if (isLoading) {
    return (
      <div className="flex items-center justify-center h-96">
        <Loader2 className="animate-spin text-rac-gold" size={32} />
      </div>
    )
  }

  if (!election) {
    return <div className="text-center py-12 text-gray-500">Élection non trouvée</div>
  }

  const candidaturesValides = candidatures?.filter((c) => c.statut === 'valide') || []
  const candidaturesByPoste = {}
  candidaturesValides.forEach((c) => {
    if (!candidaturesByPoste[c.poste]) candidaturesByPoste[c.poste] = []
    candidaturesByPoste[c.poste].push(c)
  })

  return (
    <div className="max-w-4xl mx-auto space-y-6">
      <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <div className="flex items-center gap-3 mb-1">
            <h1 className="font-title text-2xl font-bold text-rac-dark">
              {typeLabels[election.type]}
            </h1>
            <Badge variant={election.statut === 'vote' ? 'success' : election.statut === 'proclame' ? 'info' : 'warning'}>
              {election.statut}
            </Badge>
          </div>
          <p className="text-sm text-gray-500">Niveau : {election.niveau}</p>
        </div>
        <div className="flex gap-2">
          {election.statut === 'candidatures' && (
            <Link to={`/elections/${id}/candidature`}>
              <Button variant="primary" className="gap-2">
                <FileText size={16} /> Déposer candidature
              </Button>
            </Link>
          )}
          {election.statut === 'vote' && (
            <Link to={`/elections/${id}/vote`}>
              <Button variant="primary" className="gap-2">
                <Vote size={16} /> Voter
              </Button>
            </Link>
          )}
        </div>
      </div>

      <Card>
        <CardHeader>
          <CardTitle>Informations</CardTitle>
        </CardHeader>
        <CardContent>
          <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
            <div>
              <span className="text-gray-500">Date du vote :</span>
              <p className="font-medium">{election.date_vote}</p>
            </div>
            <div>
              <span className="text-gray-500">Horaires :</span>
              <p className="font-medium">{election.heure_ouverture_vote} → {election.heure_cloture_vote}</p>
            </div>
            <div>
              <span className="text-gray-500">Mode de scrutin :</span>
              <p className="font-medium capitalize">{election.mode_scrutin?.replace('_', ' ')}</p>
            </div>
            <div>
              <span className="text-gray-500">Postes à élire :</span>
              <p className="font-medium">{election.postes?.join(', ').replace(/_/g, ' ')}</p>
            </div>
          </div>
        </CardContent>
      </Card>

      {election.statut === 'proclame' && resultatsData && (
        <Card>
          <CardHeader>
            <CardTitle className="flex items-center gap-2">
              <Trophy size={20} className="text-rac-gold" /> Résultats proclamés
            </CardTitle>
          </CardHeader>
          <CardContent>
            <div className="space-y-4">
              {Object.entries(resultatsData.resultats || {}).map(([poste, resultats]) => (
                <div key={poste}>
                  <h4 className="font-medium text-gray-700 capitalize mb-2">{poste.replace('_', ' ')}</h4>
                  <div className="space-y-2">
                    {resultats?.map((r, i) => (
                      <div key={i} className="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span className="font-medium">{r.candidature?.alumni?.nom_complet}</span>
                        <span className="font-mono font-bold text-rac-gold">{r.nb_voix} voix</span>
                      </div>
                    ))}
                  </div>
                </div>
              ))}
              <div className="pt-4 border-t border-gray-100">
                <div className="flex items-center justify-between text-sm">
                  <span className="text-gray-600">Participation :</span>
                  <span className="font-mono">
                    {resultatsData.participation?.votants} / {resultatsData.participation?.inscrits} ({resultatsData.participation?.taux}%)
                  </span>
                </div>
              </div>
            </div>
          </CardContent>
        </Card>
      )}

      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <Users size={20} /> Candidatures ({candidaturesValides.length})
          </CardTitle>
        </CardHeader>
        <CardContent>
          {Object.keys(candidaturesByPoste).length === 0 ? (
            <p className="text-gray-500 text-center py-8">Aucune candidature validée pour le moment.</p>
          ) : (
            <div className="space-y-6">
              {Object.entries(candidaturesByPoste).map(([poste, candidats]) => (
                <div key={poste}>
                  <h4 className="font-medium text-gray-700 uppercase text-sm mb-3">{poste.replace('_', ' ')}</h4>
                  <div className="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    {candidats.map((c) => (
                      <div key={c.id} className="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                        <div className="w-10 h-10 rounded-full bg-rac-gold/10 flex items-center justify-center text-rac-gold font-title font-bold">
                          {c.alumni?.prenom?.[0]}{c.alumni?.nom?.[0]}
                        </div>
                        <div>
                          <p className="font-medium text-sm">{c.alumni?.nom_complet}</p>
                          <p className="text-xs text-gray-500">{c.alumni?.cdej?.nom}</p>
                        </div>
                        <Badge variant={c.statut === 'valide' ? 'success' : 'warning'} className="ml-auto">
                          {c.statut}
                        </Badge>
                      </div>
                    ))}
                  </div>
                </div>
              ))}
            </div>
          )}
        </CardContent>
      </Card>
    </div>
  )
}
