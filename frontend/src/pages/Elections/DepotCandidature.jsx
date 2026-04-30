import React, { useState } from 'react'
import { useParams, useNavigate } from 'react-router-dom'
import { useElection } from '../../hooks/useElection'
import { useSubmitCandidature } from '../../hooks/useElection'
import { useMe } from '../../hooks/useAuth'
import { Button } from '../../components/ui/Button'
import { Card, CardContent } from '../../components/ui/Card'
import { ProgressBar } from '../../components/ui/ProgressBar'
import { Badge } from '../../components/ui/Badge'
import { Loader2, CheckCircle, XCircle, Upload, FileText, Send } from 'lucide-react'

export default function DepotCandidature() {
  const { id } = useParams()
  const navigate = useNavigate()
  const { data: election } = useElection(id)
  const { data: me } = useMe()
  const submitCandidature = useSubmitCandidature()

  const [step, setStep] = useState(1)
  const [poste, setPoste] = useState('')
  const [lettre, setLettre] = useState('')
  const [documents, setDocuments] = useState([])
  const [loading, setLoading] = useState(false)

  const alumni = me
  const eligibility = {
    isAlumni: !!alumni?.cdej,
    compteValide: alumni?.statut_compte === 'valide',
    cotisationAJour: alumni?.cotisation_a_jour,
    diplomeSuffisant: ['bac', 'bts', 'licence', 'master', 'formation_pro'].includes(alumni?.niveau_diplome),
  }
  const allEligible = Object.values(eligibility).every(Boolean)

  const totalSteps = 5
  const stepLabels = ['Éligibilité', 'Poste', 'Documents', 'Motivation', 'Confirmation']

  const handleSubmit = async () => {
    setLoading(true)
    try {
      await submitCandidature.mutateAsync({
        electionId: id,
        data: { poste, lettre_motivation: lettre, documents },
      })
      navigate(`/elections/${id}`)
    } catch (err) {
      console.error(err)
    } finally {
      setLoading(false)
    }
  }

  return (
    <div className="max-w-3xl mx-auto space-y-6">
      <div>
        <h1 className="font-title text-2xl font-bold text-rac-dark">Déposer une candidature</h1>
        <p className="text-gray-500 text-sm mt-1">{election?.type?.toUpperCase()} — Niveau {election?.niveau}</p>
      </div>

      <ProgressBar current={step} total={totalSteps} label={`Étape ${step} sur ${totalSteps}`} />

      <div className="flex gap-2 overflow-x-auto pb-2">
        {stepLabels.map((label, i) => (
          <div key={i} className={`flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm whitespace-nowrap ${
            i + 1 === step ? 'bg-rac-gold/10 text-rac-gold-dark font-medium' : i + 1 < step ? 'text-rac-green' : 'text-gray-400'
          }`}>
            <span className={`w-5 h-5 rounded-full flex items-center justify-center text-xs ${
              i + 1 === step ? 'bg-rac-gold text-white' : i + 1 < step ? 'bg-rac-green text-white' : 'bg-gray-200'
            }`}>
              {i + 1 < step ? '✓' : i + 1}
            </span>
            {label}
          </div>
        ))}
      </div>

      {step === 1 && (
        <Card>
          <CardContent className="p-6 space-y-4">
            <h2 className="font-title text-lg font-semibold">Vérification de l'éligibilité</h2>
            <div className="space-y-3">
              {Object.entries(eligibility).map(([key, value]) => (
                <div key={key} className="flex items-center justify-between p-3 rounded-lg bg-gray-50">
                  <span className="text-sm text-gray-700 capitalize">{key.replace(/([A-Z])/g, ' $1').trim()}</span>
                  {value ? <CheckCircle size={18} className="text-rac-green" /> : <XCircle size={18} className="text-rac-red" />}
                </div>
              ))}
            </div>
            {!allEligible && (
              <div className="p-4 bg-rac-red/10 border border-rac-red/20 rounded-lg">
                <p className="text-sm text-rac-red">Vous ne remplissez pas tous les critères d'éligibilité. Veuillez régulariser votre situation avant de déposer une candidature.</p>
              </div>
            )}
            <div className="flex justify-end">
              <Button onClick={() => setStep(2)} disabled={!allEligible}>
                Continuer
              </Button>
            </div>
          </CardContent>
        </Card>
      )}

      {step === 2 && (
        <Card>
          <CardContent className="p-6 space-y-4">
            <h2 className="font-title text-lg font-semibold">Choix du poste</h2>
            <div className="grid grid-cols-1 sm:grid-cols-2 gap-3">
              {election?.postes?.map((p) => (
                <label key={p} className={`flex items-center gap-3 p-4 rounded-lg border-2 cursor-pointer transition-all ${
                  poste === p ? 'border-rac-gold bg-rac-gold/5' : 'border-gray-200 hover:border-gray-300'
                }`}>
                  <input type="radio" name="poste" value={p} checked={poste === p} onChange={() => setPoste(p)} className="w-4 h-4 text-rac-gold" />
                  <span className="font-medium capitalize">{p.replace('_', ' ')}</span>
                </label>
              ))}
            </div>
            <div className="flex justify-between">
              <Button variant="outline" onClick={() => setStep(1)}>Retour</Button>
              <Button onClick={() => setStep(3)} disabled={!poste}>Continuer</Button>
            </div>
          </CardContent>
        </Card>
      )}

      {step === 3 && (
        <Card>
          <CardContent className="p-6 space-y-4">
            <h2 className="font-title text-lg font-semibold">Documents</h2>
            <div className="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-rac-gold transition-colors">
              <Upload size={32} className="text-gray-400 mx-auto mb-3" />
              <p className="text-sm text-gray-600">Glissez-déposez vos documents ici</p>
              <p className="text-xs text-gray-400 mt-1">PDF, JPG, PNG — Max 5 Mo</p>
            </div>
            <div className="flex justify-between">
              <Button variant="outline" onClick={() => setStep(2)}>Retour</Button>
              <Button onClick={() => setStep(4)}>Continuer</Button>
            </div>
          </CardContent>
        </Card>
      )}

      {step === 4 && (
        <Card>
          <CardContent className="p-6 space-y-4">
            <h2 className="font-title text-lg font-semibold">Lettre de motivation</h2>
            <textarea
              value={lettre}
              onChange={(e) => setLettre(e.target.value)}
              placeholder="Décrivez vos motivations, votre vision et vos compétences pour ce poste..."
              className="w-full h-48 px-4 py-3 rounded-lg border border-gray-300 focus:border-rac-gold focus:ring-2 focus:ring-rac-gold/30 focus:outline-none resize-none"
            />
            <div className="flex items-center justify-between text-sm text-gray-500">
              <span>{lettre.length} / 2000 caractères</span>
              <span className={lettre.length < 200 ? 'text-rac-red' : 'text-rac-green'}>
                {lettre.length < 200 ? 'Minimum 200 caractères' : 'Longueur suffisante'}
              </span>
            </div>
            <div className="flex justify-between">
              <Button variant="outline" onClick={() => setStep(3)}>Retour</Button>
              <Button onClick={() => setStep(5)} disabled={lettre.length < 200}>Continuer</Button>
            </div>
          </CardContent>
        </Card>
      )}

      {step === 5 && (
        <Card>
          <CardContent className="p-6 space-y-4">
            <h2 className="font-title text-lg font-semibold">Confirmation</h2>
            <div className="space-y-3 bg-gray-50 p-4 rounded-lg">
              <div className="flex justify-between">
                <span className="text-gray-600">Poste :</span>
                <span className="font-medium capitalize">{poste.replace('_', ' ')}</span>
              </div>
              <div className="flex justify-between">
                <span className="text-gray-600">Lettre :</span>
                <span className="font-medium">{lettre.length} caractères</span>
              </div>
            </div>
            <label className="flex items-start gap-3">
              <input type="checkbox" className="mt-1 w-4 h-4 text-rac-gold rounded" required />
              <span className="text-sm text-gray-700">
                Je déclare sur l'honneur que les informations fournies sont exactes et que je remplis les conditions d'éligibilité requises.
              </span>
            </label>
            <div className="flex justify-between">
              <Button variant="outline" onClick={() => setStep(4)}>Retour</Button>
              <Button onClick={handleSubmit} disabled={loading} className="gap-2">
                {loading ? <Loader2 className="animate-spin" size={18} /> : <Send size={18} />}
                Déposer ma candidature
              </Button>
            </div>
          </CardContent>
        </Card>
      )}
    </div>
  )
}
