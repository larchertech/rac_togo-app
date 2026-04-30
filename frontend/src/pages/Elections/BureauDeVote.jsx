import React, { useState } from 'react'
import { useParams, useNavigate } from 'react-router-dom'
import { useElection } from '../../hooks/useElection'
import { useElectionCandidatures } from '../../hooks/useElection'
import { useCastVote } from '../../hooks/useVote'
import { Button } from '../../components/ui/Button'
import { Card, CardContent } from '../../components/ui/Card'
import { Modal } from '../../components/ui/Modal'
import { ProgressBar } from '../../components/ui/ProgressBar'
import { Badge } from '../../components/ui/Badge'
import { Loader2, Clock, AlertTriangle, CheckCircle, Lock, Vote as VoteIcon } from 'lucide-react'

export default function BureauDeVote() {
  const { id } = useParams()
  const navigate = useNavigate()
  const { data: election, isLoading: electionLoading } = useElection(id)
  const { data: candidatures, isLoading: candidaturesLoading } = useElectionCandidatures(id)
  const castVote = useCastVote()

  const [selections, setSelections] = useState({})
  const [showConfirm, setShowConfirm] = useState(false)
  const [hasVoted, setHasVoted] = useState(false)
  const [error, setError] = useState('')

  if (electionLoading || candidaturesLoading) {
    return (
      <div className="flex items-center justify-center h-96">
        <Loader2 className="animate-spin text-rac-gold" size={32} />
      </div>
    )
  }

  if (!election) {
    return (
      <div className="text-center py-12">
        <AlertTriangle size={48} className="text-rac-red mx-auto mb-4" />
        <h2 className="text-xl font-title font-semibold text-gray-800">Élection non trouvée</h2>
      </div>
    )
  }

  const postes = election.postes || []
  const candidaturesByPoste = {}
  candidatures?.forEach((c) => {
    if (c.statut === 'valide') {
      if (!candidaturesByPoste[c.poste]) candidaturesByPoste[c.poste] = []
      candidaturesByPoste[c.poste].push(c)
    }
  })

  const nbSelectionnes = Object.keys(selections).length
  const nbPostes = postes.length

  const handleSelect = (poste, candidatureId) => {
    setSelections((prev) => ({ ...prev, [poste]: candidatureId }))
    setError('')
  }

  const handleConfirm = async () => {
    setError('')
    try {
      const votes = postes.map((poste) => selections[poste]).filter(Boolean)
      await castVote.mutateAsync({ electionId: id, votes })
      setHasVoted(true)
      setSelections({})
    } catch (err) {
      setError(err.response?.data?.message || 'Erreur lors du vote.')
    }
  }

  const getTimeRemaining = () => {
    if (!election.heure_cloture_vote) return null
    const now = new Date()
    const cloture = new Date(election.date_vote + 'T' + election.heure_cloture_vote)
    const diff = cloture - now
    if (diff <= 0) return 'Fermé'
    const hours = Math.floor(diff / 3600000)
    const mins = Math.floor((diff % 3600000) / 60000)
    return `${hours}h ${mins}min`
  }

  if (hasVoted) {
    return (
      <div className="max-w-lg mx-auto text-center py-16 animate-fade-in">
        <div className="w-20 h-20 bg-rac-green/10 rounded-full flex items-center justify-center mx-auto mb-6">
          <CheckCircle size={40} className="text-rac-green" />
        </div>
        <h2 className="font-title text-2xl font-bold text-rac-dark mb-3">
          Votre vote a été enregistré
        </h2>
        <p className="text-gray-600 mb-2">Merci pour votre participation démocratique.</p>
        <p className="text-sm text-gray-500 mb-8">
          Une confirmation a été envoyée sur votre canal de communication.
        </p>
        <Button onClick={() => navigate('/elections')} variant="primary">
          Retour aux élections
        </Button>
      </div>
    )
  }

  return (
    <div className="max-w-4xl mx-auto space-y-6">
      {/* Header */}
      <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <div className="flex items-center gap-3 mb-1">
            <h1 className="font-title text-2xl font-bold text-rac-dark">
              {election.type?.toUpperCase()} — Bureau de vote
            </h1>
            <Badge variant="success">VOTE EN COURS</Badge>
          </div>
          <p className="text-sm text-gray-500">Niveau : {election.niveau}</p>
        </div>
        <div className="flex items-center gap-4">
          <div className="flex items-center gap-2 px-3 py-1.5 bg-rac-gold/10 rounded-lg">
            <Clock size={16} className="text-rac-gold" />
            <span className="font-mono text-sm text-rac-gold-dark">{getTimeRemaining()}</span>
          </div>
        </div>
      </div>

      {/* Warning */}
      <div className="flex items-center gap-3 p-4 bg-rac-dark/5 border border-rac-dark/10 rounded-lg">
        <Lock size={18} className="text-rac-dark shrink-0" />
        <p className="text-sm text-rac-dark">
          <strong>Votre vote est secret et définitif.</strong> Une fois confirmé, vous ne pourrez plus modifier votre choix.
        </p>
      </div>

      {/* Progress */}
      <ProgressBar
        current={nbSelectionnes}
        total={nbPostes}
        label={`Postes sélectionnés (${nbSelectionnes}/${nbPostes})`}
      />

      {/* Vote sections */}
      <div className="space-y-6">
        {postes.map((poste) => (
          <Card key={poste}>
            <CardContent className="p-6">
              <h2 className="font-title text-lg font-bold text-rac-dark uppercase mb-4">
                {poste.replace('_', ' ')}
              </h2>
              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                {candidaturesByPoste[poste]?.map((candidature) => (
                  <label
                    key={candidature.id}
                    className={`flex items-start gap-4 p-4 rounded-lg border-2 cursor-pointer transition-all ${
                      selections[poste] === candidature.id
                        ? 'border-rac-gold bg-rac-gold/5'
                        : 'border-gray-200 hover:border-gray-300'
                    }`}
                  >
                    <input
                      type="radio"
                      name={poste}
                      value={candidature.id}
                      checked={selections[poste] === candidature.id}
                      onChange={() => handleSelect(poste, candidature.id)}
                      className="mt-1 w-4 h-4 text-rac-gold focus:ring-rac-gold"
                    />
                    <div className="flex-1">
                      <div className="flex items-center gap-3">
                        <div className="w-10 h-10 rounded-full bg-rac-gold/10 flex items-center justify-center text-rac-gold font-title font-bold">
                          {candidature.alumni?.prenom?.[0]}{candidature.alumni?.nom?.[0]}
                        </div>
                        <div>
                          <p className="font-medium text-gray-900">
                            {candidature.alumni?.nom_complet}
                          </p>
                          <p className="text-xs text-gray-500">
                            {candidature.alumni?.cdej?.nom} — {candidature.alumni?.niveau_diplome}
                          </p>
                        </div>
                      </div>
                      {candidature.lettre_motivation && (
                        <p className="text-sm text-gray-600 mt-2 line-clamp-2">
                          {candidature.lettre_motivation}
                        </p>
                      )}
                    </div>
                  </label>
                ))}
              </div>
            </CardContent>
          </Card>
        ))}
      </div>

      {error && (
        <div className="p-4 bg-rac-red/10 border border-rac-red/20 rounded-lg">
          <p className="text-sm text-rac-red">{error}</p>
        </div>
      )}

      <div className="flex justify-end">
        <Button
          onClick={() => setShowConfirm(true)}
          disabled={nbSelectionnes < nbPostes || castVote.isPending}
          variant="primary"
          size="lg"
          className="gap-2"
        >
          {castVote.isPending ? (
            <Loader2 className="animate-spin" size={18} />
          ) : (
            <VoteIcon size={18} />
          )}
          Confirmer mon vote
        </Button>
      </div>

      {/* Confirmation Modal */}
      <Modal
        isOpen={showConfirm}
        onClose={() => setShowConfirm(false)}
        title="Confirmer votre vote"
        size="md"
      >
        <div className="space-y-4">
          <div className="flex items-center gap-3 p-4 bg-rac-red/5 border border-rac-red/20 rounded-lg">
            <AlertTriangle size={20} className="text-rac-red shrink-0" />
            <p className="text-sm text-rac-red">
              Cette action est <strong>irréversible</strong>. Votre vote sera définitivement enregistré et ne pourra plus être modifié.
            </p>
          </div>

          <div className="space-y-3">
            <h4 className="font-medium text-gray-700">Récapitulatif de vos choix :</h4>
            {postes.map((poste) => {
              const candidature = candidaturesByPoste[poste]?.find(
                (c) => c.id === selections[poste]
              )
              return (
                <div key={poste} className="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                  <span className="text-sm font-medium text-gray-600 capitalize">
                    {poste.replace('_', ' ')}
                  </span>
                  <span className="text-sm font-semibold text-rac-dark">
                    {candidature?.alumni?.nom_complet || 'Non sélectionné'}
                  </span>
                </div>
              )
            })}
          </div>

          <div className="flex gap-3 pt-2">
            <Button
              variant="outline"
              className="flex-1"
              onClick={() => setShowConfirm(false)}
            >
              Annuler
            </Button>
            <Button
              variant="primary"
              className="flex-1 gap-2"
              onClick={handleConfirm}
              disabled={castVote.isPending}
            >
              {castVote.isPending ? (
                <Loader2 className="animate-spin" size={18} />
              ) : (
                <Lock size={18} />
              )}
              Confirmer définitivement
            </Button>
          </div>
        </div>
      </Modal>
    </div>
  )
}
