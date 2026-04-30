import React, { useState } from 'react'
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query'
import api from '../../api/axios'
import { Card, CardContent, CardHeader, CardTitle } from '../../components/ui/Card'
import { Badge } from '../../components/ui/Badge'
import { Button } from '../../components/ui/Button'
import { Modal } from '../../components/ui/Modal'
import { Input } from '../../components/ui/Input'
import { Loader2, CheckCircle, XCircle, FileText, Filter, Download } from 'lucide-react'

function useCandidaturesAdmin() {
  return useQuery({
    queryKey: ['candidatures', 'admin'],
    queryFn: () => api.get('/elections').then((res) => {
      // Récupérer toutes les candidatures de toutes les élections
      const elections = res.data.data?.data || []
      return elections
    }),
  })
}

export default function ValidationCandidatures() {
  const queryClient = useQueryClient()
  const [filter, setFilter] = useState('')
  const [selectedCandidature, setSelectedCandidature] = useState(null)
  const [showRejetModal, setShowRejetModal] = useState(false)
  const [motifRejet, setMotifRejet] = useState('')

  const { data: elections, isLoading } = useCandidaturesAdmin()

  const validerMutation = useMutation({
    mutationFn: (id) => api.put(`/candidatures/${id}/valider`),
    onSuccess: () => queryClient.invalidateQueries({ queryKey: ['candidatures'] }),
  })

  const rejeterMutation = useMutation({
    mutationFn: ({ id, motif }) => api.put(`/candidatures/${id}/rejeter`, { motif }),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['candidatures'] })
      setShowRejetModal(false)
      setMotifRejet('')
      setSelectedCandidature(null)
    },
  })

  if (isLoading) {
    return (
      <div className="flex items-center justify-center h-96">
        <Loader2 className="animate-spin text-rac-gold" size={32} />
      </div>
    )
  }

  // Extraire toutes les candidatures
  const allCandidatures = []
  elections?.forEach((e) => {
    if (e.candidatures) {
      e.candidatures.forEach((c) => allCandidatures.push({ ...c, election: e }))
    }
  })

  const filtered = filter
    ? allCandidatures.filter((c) => c.statut === filter)
    : allCandidatures

  return (
    <div className="space-y-6">
      <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <h1 className="font-title text-2xl font-bold text-rac-dark">Validation des candidatures</h1>
        <div className="flex gap-2">
          <Button variant={filter === '' ? 'primary' : 'outline'} size="sm" onClick={() => setFilter('')}>
            Toutes
          </Button>
          <Button variant={filter === 'soumis' ? 'primary' : 'outline'} size="sm" onClick={() => setFilter('soumis')}>
            Soumis
          </Button>
          <Button variant={filter === 'en_examen' ? 'primary' : 'outline'} size="sm" onClick={() => setFilter('en_examen')}>
            En examen
          </Button>
          <Button variant={filter === 'valide' ? 'primary' : 'outline'} size="sm" onClick={() => setFilter('valide')}>
            Validées
          </Button>
          <Button variant={filter === 'rejete' ? 'primary' : 'outline'} size="sm" onClick={() => setFilter('rejete')}>
            Rejetées
          </Button>
        </div>
      </div>

      <Card>
        <CardContent className="p-0">
          <div className="overflow-x-auto">
            <table className="w-full text-sm">
              <thead className="bg-gray-50 border-b border-gray-200">
                <tr>
                  <th className="px-4 py-3 text-left font-medium text-gray-700">Dossier</th>
                  <th className="px-4 py-3 text-left font-medium text-gray-700">Candidat</th>
                  <th className="px-4 py-3 text-left font-medium text-gray-700">CDEJ</th>
                  <th className="px-4 py-3 text-left font-medium text-gray-700">Poste</th>
                  <th className="px-4 py-3 text-left font-medium text-gray-700">Élection</th>
                  <th className="px-4 py-3 text-left font-medium text-gray-700">Statut</th>
                  <th className="px-4 py-3 text-left font-medium text-gray-700">Actions</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-gray-100">
                {filtered.map((c) => (
                  <tr key={c.id} className="hover:bg-gray-50 transition-colors">
                    <td className="px-4 py-3 font-mono text-xs text-rac-gold-dark">{c.numero_dossier}</td>
                    <td className="px-4 py-3 font-medium">{c.alumni?.nom_complet}</td>
                    <td className="px-4 py-3 text-gray-600">{c.alumni?.cdej?.nom}</td>
                    <td className="px-4 py-3 capitalize">{c.poste?.replace('_', ' ')}</td>
                    <td className="px-4 py-3 text-gray-600 uppercase">{c.election?.type}</td>
                    <td className="px-4 py-3">
                      <Badge variant={
                        c.statut === 'valide' ? 'success' :
                        c.statut === 'rejete' ? 'danger' :
                        c.statut === 'en_examen' ? 'warning' : 'default'
                      }>
                        {c.statut}
                      </Badge>
                    </td>
                    <td className="px-4 py-3">
                      <div className="flex gap-1">
                        {c.statut === 'soumis' || c.statut === 'en_examen' ? (
                          <>
                            <Button
                              variant="success"
                              size="sm"
                              className="gap-1 px-2"
                              onClick={() => validerMutation.mutate(c.id)}
                              disabled={validerMutation.isPending}
                            >
                              <CheckCircle size={14} />
                            </Button>
                            <Button
                              variant="danger"
                              size="sm"
                              className="gap-1 px-2"
                              onClick={() => {
                                setSelectedCandidature(c)
                                setShowRejetModal(true)
                              }}
                            >
                              <XCircle size={14} />
                            </Button>
                          </>
                        ) : (
                          <span className="text-xs text-gray-400">—</span>
                        )}
                      </div>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </CardContent>
      </Card>

      <Modal
        isOpen={showRejetModal}
        onClose={() => setShowRejetModal(false)}
        title="Rejeter la candidature"
        size="md"
      >
        <div className="space-y-4">
          <p className="text-sm text-gray-600">
            Candidat : <strong>{selectedCandidature?.alumni?.nom_complet}</strong>
          </p>
          <Input
            label="Motif du rejet (obligatoire)"
            placeholder="Précisez la raison du rejet..."
            value={motifRejet}
            onChange={(e) => setMotifRejet(e.target.value)}
          />
          <div className="flex gap-3">
            <Button variant="outline" className="flex-1" onClick={() => setShowRejetModal(false)}>
              Annuler
            </Button>
            <Button
              variant="danger"
              className="flex-1"
              disabled={!motifRejet || rejeterMutation.isPending}
              onClick={() => rejeterMutation.mutate({ id: selectedCandidature?.id, motif: motifRejet })}
            >
              {rejeterMutation.isPending ? <Loader2 className="animate-spin" size={18} /> : 'Confirmer le rejet'}
            </Button>
          </div>
        </div>
      </Modal>
    </div>
  )
}
