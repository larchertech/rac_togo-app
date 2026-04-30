import React, { useState } from 'react'
import { useElections, useElectionResultats } from '../../hooks/useElection'
import { Card, CardContent, CardHeader, CardTitle } from '../../components/ui/Card'
import { Button } from '../../components/ui/Button'
import { Badge } from '../../components/ui/Badge'
import { Modal } from '../../components/ui/Modal'
import { Loader2, Trophy, AlertTriangle, CheckCircle, FileText, TrendingUp } from 'lucide-react'

export default function Proclamation() {
  const { data: electionsData } = useElections({ statut: 'depouillement' })
  const [selectedElection, setSelectedElection] = useState(null)
  const { data: resultats } = useElectionResultats(selectedElection?.id)
  const [showConfirm, setShowConfirm] = useState(false)

  const elections = electionsData?.data?.filter((e) => e.statut === 'depouillement') || []

  return (
    <div className="space-y-6">
      <h1 className="font-title text-2xl font-bold text-rac-dark">Proclamation officielle</h1>

      {elections.length === 0 ? (
        <Card>
          <CardContent className="p-12 text-center">
            <Trophy size={48} className="text-gray-300 mx-auto mb-4" />
            <p className="text-gray-500">Aucune élection en phase de dépouillement.</p>
          </CardContent>
        </Card>
      ) : (
        <div className="space-y-6">
          {elections.map((election) => (
            <Card key={election.id}>
              <CardHeader>
                <div className="flex items-center justify-between">
                  <CardTitle>{election.type?.toUpperCase()} — Niveau {election.niveau}</CardTitle>
                  <Badge variant="info">DÉPOUILLEMENT</Badge>
                </div>
              </CardHeader>
              <CardContent>
                <div className="flex items-center justify-between">
                  <div className="space-y-1">
                    <p className="text-sm text-gray-600">
                      Date de vote : {election.date_vote}
                    </p>
                    <p className="text-sm text-gray-600">
                      Mode : {election.mode_scrutin?.replace('_', ' ')}
                    </p>
                  </div>
                  <div className="flex gap-2">
                    <Button
                      variant="outline"
                      className="gap-2"
                      onClick={() => setSelectedElection(election)}
                    >
                      <TrendingUp size={16} /> Voir résultats
                    </Button>
                    <Button
                      variant="primary"
                      className="gap-2"
                      onClick={() => setShowConfirm(true)}
                    >
                      <Trophy size={16} /> Proclamer
                    </Button>
                  </div>
                </div>
              </CardContent>
            </Card>
          ))}
        </div>
      )}

      {/* Résultats Modal */}
      <Modal
        isOpen={!!selectedElection && !showConfirm}
        onClose={() => setSelectedElection(null)}
        title="Résultats du dépouillement"
        size="lg"
      >
        {resultats ? (
          <div className="space-y-6">
            <div className="grid grid-cols-3 gap-4">
              <div className="p-4 bg-gray-50 rounded-lg text-center">
                <p className="text-xs text-gray-500">Inscrits</p>
                <p className="font-title text-xl font-bold">{resultats.participation?.inscrits}</p>
              </div>
              <div className="p-4 bg-gray-50 rounded-lg text-center">
                <p className="text-xs text-gray-500">Votants</p>
                <p className="font-title text-xl font-bold">{resultats.participation?.votants}</p>
              </div>
              <div className="p-4 bg-gray-50 rounded-lg text-center">
                <p className="text-xs text-gray-500">Participation</p>
                <p className="font-title text-xl font-bold text-rac-gold">{resultats.participation?.taux}%</p>
              </div>
            </div>

            {Object.entries(resultats.resultats || {}).map(([poste, candidats]) => (
              <div key={poste}>
                <h4 className="font-medium text-gray-700 uppercase mb-3">{poste.replace('_', ' ')}</h4>
                <div className="space-y-2">
                  {candidats?.map((c, i) => (
                    <div key={i} className={`flex items-center justify-between p-3 rounded-lg ${
                      i === 0 ? 'bg-rac-gold/10 border border-rac-gold/20' : 'bg-gray-50'
                    }`}>
                      <div className="flex items-center gap-3">
                        {i === 0 && <Trophy size={16} className="text-rac-gold" />}
                        <span className="font-medium">{c.candidature?.alumni?.nom_complet}</span>
                      </div>
                      <span className="font-mono font-bold">{c.nb_voix} voix</span>
                    </div>
                  ))}
                </div>
              </div>
            ))}
          </div>
        ) : (
          <div className="flex items-center justify-center py-12">
            <Loader2 className="animate-spin text-rac-gold" size={32} />
          </div>
        )}
      </Modal>

      {/* Confirmation Proclamation */}
      <Modal
        isOpen={showConfirm}
        onClose={() => setShowConfirm(false)}
        title="Confirmer la proclamation"
        size="md"
      >
        <div className="space-y-4">
          <div className="flex items-center gap-3 p-4 bg-rac-red/5 border border-rac-red/20 rounded-lg">
            <AlertTriangle size={20} className="text-rac-red shrink-0" />
            <p className="text-sm text-rac-red">
              Cette action est <strong>irréversible</strong>. Les résultats seront rendus publics et le PV officiel sera généré.
            </p>
          </div>

          <div className="space-y-2 text-sm">
            <p><strong>Élection :</strong> {selectedElection?.type?.toUpperCase()}</p>
            <p><strong>Niveau :</strong> {selectedElection?.niveau}</p>
            <p><strong>Date :</strong> {selectedElection?.date_vote}</p>
          </div>

          <div className="flex gap-3 pt-2">
            <Button variant="outline" className="flex-1" onClick={() => setShowConfirm(false)}>
              Annuler
            </Button>
            <Button variant="primary" className="flex-1 gap-2">
              <CheckCircle size={16} /> Confirmer la proclamation
            </Button>
          </div>
        </div>
      </Modal>
    </div>
  )
}
