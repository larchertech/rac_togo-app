import React, { useState } from 'react'
import { useNavigate } from 'react-router-dom'
import { alumniApi } from '../../api/alumni.api'
import { Button } from '../../components/ui/Button'
import { Input } from '../../components/ui/Input'
import { Select } from '../../components/ui/Select'
import { ProgressBar } from '../../components/ui/ProgressBar'
import { Card, CardContent } from '../../components/ui/Card'
import { Loader2, CheckCircle, Upload, FileText } from 'lucide-react'

const diplomeOptions = [
  { value: 'cepe', label: 'CEPE' },
  { value: 'bepc', label: 'BEPC' },
  { value: 'bac', label: 'BAC' },
  { value: 'bts', label: 'BTS' },
  { value: 'licence', label: 'Licence' },
  { value: 'master', label: 'Master' },
  { value: 'formation_pro', label: 'Formation Professionnelle' },
]

export default function Inscription() {
  const navigate = useNavigate()
  const [step, setStep] = useState(1)
  const [loading, setLoading] = useState(false)
  const [formData, setFormData] = useState({
    nom: '',
    prenom: '',
    date_naissance: '',
    cdej_id: '',
    niveau_diplome: '',
    documents: [],
    statuts_acceptes: false,
    reglement_accepte: false,
    declaration: false,
  })

  const totalSteps = 5

  const handleChange = (field, value) => {
    setFormData((prev) => ({ ...prev, [field]: value }))
  }

  const handleSubmit = async () => {
    setLoading(true)
    try {
      await alumniApi.create(formData)
      navigate('/login')
    } catch (err) {
      console.error(err)
    } finally {
      setLoading(false)
    }
  }

  return (
    <div className="min-h-screen bg-rac-dark kente-pattern flex items-center justify-center p-4">
      <div className="w-full max-w-2xl">
        <div className="text-center mb-6">
          <h1 className="font-title text-3xl font-bold text-rac-gold">Inscription RAC-TOGO</h1>
          <p className="text-white/60 text-sm mt-2">Rejoignez les 5062 alumni de Compassion Togo</p>
        </div>

        <Card className="bg-white/95 backdrop-blur-sm">
          <CardContent className="p-6 md:p-8">
            <ProgressBar current={step} total={totalSteps} label={`Étape ${step} sur ${totalSteps}`} />

            {step === 1 && (
              <div className="space-y-4 mt-4">
                <h2 className="font-title text-lg font-semibold">Identité</h2>
                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <Input label="Nom" value={formData.nom} onChange={(e) => handleChange('nom', e.target.value)} required />
                  <Input label="Prénom" value={formData.prenom} onChange={(e) => handleChange('prenom', e.target.value)} required />
                </div>
                <Input type="date" label="Date de naissance" value={formData.date_naissance} onChange={(e) => handleChange('date_naissance', e.target.value)} required />
                <Select label="CDEJ" value={formData.cdej_id} onChange={(e) => handleChange('cdej_id', e.target.value)} options={[]} required />
                <div className="flex justify-end">
                  <Button onClick={() => setStep(2)} disabled={!formData.nom || !formData.prenom || !formData.date_naissance}>Continuer</Button>
                </div>
              </div>
            )}

            {step === 2 && (
              <div className="space-y-4 mt-4">
                <h2 className="font-title text-lg font-semibold">Diplôme</h2>
                <Select label="Niveau de diplôme" value={formData.niveau_diplome} onChange={(e) => handleChange('niveau_diplome', e.target.value)} options={diplomeOptions} />
                <div className="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                  <Upload size={24} className="text-gray-400 mx-auto mb-2" />
                  <p className="text-sm text-gray-600">Upload diplôme (max 5 Mo)</p>
                </div>
                <div className="flex justify-between">
                  <Button variant="outline" onClick={() => setStep(1)}>Retour</Button>
                  <Button onClick={() => setStep(3)}>Continuer</Button>
                </div>
              </div>
            )}

            {step === 3 && (
              <div className="space-y-4 mt-4">
                <h2 className="font-title text-lg font-semibold">Documents CDEJ</h2>
                <div className="space-y-3">
                  <div className="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center">
                    <FileText size={20} className="text-gray-400 mx-auto mb-2" />
                    <p className="text-sm text-gray-600">Attestation de participation CDEJ</p>
                  </div>
                  <div className="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center">
                    <FileText size={20} className="text-gray-400 mx-auto mb-2" />
                    <p className="text-sm text-gray-600">Lettre de recommandation CDEJ</p>
                  </div>
                </div>
                <div className="flex justify-between">
                  <Button variant="outline" onClick={() => setStep(2)}>Retour</Button>
                  <Button onClick={() => setStep(4)}>Continuer</Button>
                </div>
              </div>
            )}

            {step === 4 && (
              <div className="space-y-4 mt-4">
                <h2 className="font-title text-lg font-semibold">Engagement</h2>
                <label className="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                  <input type="checkbox" className="mt-1 w-4 h-4 text-rac-gold rounded" checked={formData.statuts_acceptes} onChange={(e) => handleChange('statuts_acceptes', e.target.checked)} />
                  <span className="text-sm text-gray-700">J'ai lu et j'accepte les Statuts du RAC-TOGO</span>
                </label>
                <label className="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                  <input type="checkbox" className="mt-1 w-4 h-4 text-rac-gold rounded" checked={formData.reglement_accepte} onChange={(e) => handleChange('reglement_accepte', e.target.checked)} />
                  <span className="text-sm text-gray-700">J'ai lu et j'accepte le Règlement Intérieur</span>
                </label>
                <div className="flex justify-between">
                  <Button variant="outline" onClick={() => setStep(3)}>Retour</Button>
                  <Button onClick={() => setStep(5)} disabled={!formData.statuts_acceptes || !formData.reglement_accepte}>Continuer</Button>
                </div>
              </div>
            )}

            {step === 5 && (
              <div className="space-y-4 mt-4">
                <h2 className="font-title text-lg font-semibold">Récapitulatif</h2>
                <div className="bg-gray-50 p-4 rounded-lg space-y-2 text-sm">
                  <div className="flex justify-between"><span className="text-gray-600">Nom :</span><span className="font-medium">{formData.prenom} {formData.nom}</span></div>
                  <div className="flex justify-between"><span className="text-gray-600">Naissance :</span><span className="font-medium">{formData.date_naissance}</span></div>
                  <div className="flex justify-between"><span className="text-gray-600">Diplôme :</span><span className="font-medium">{formData.niveau_diplome}</span></div>
                </div>
                <label className="flex items-start gap-3">
                  <input type="checkbox" className="mt-1 w-4 h-4 text-rac-gold rounded" checked={formData.declaration} onChange={(e) => handleChange('declaration', e.target.checked)} />
                  <span className="text-sm text-gray-700">Je confirme l'exactitude des informations fournies.</span>
                </label>
                <div className="flex justify-between">
                  <Button variant="outline" onClick={() => setStep(4)}>Retour</Button>
                  <Button onClick={handleSubmit} disabled={loading || !formData.declaration} className="gap-2">
                    {loading ? <Loader2 className="animate-spin" size={18} /> : <CheckCircle size={18} />}
                    Soumettre mon dossier
                  </Button>
                </div>
              </div>
            )}
          </CardContent>
        </Card>
      </div>
    </div>
  )
}
