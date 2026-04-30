import React, { useState } from 'react'
import { useCotisationStatut, useInitierPaiement, useCotisationRapport } from '../../hooks/useCotisation'
import { useAuthStore } from '../../store/authStore'
import { Card, CardContent, CardHeader, CardTitle } from '../../components/ui/Card'
import { Button } from '../../components/ui/Button'
import { Badge } from '../../components/ui/Badge'
import { ProgressBar } from '../../components/ui/ProgressBar'
import { Loader2, CreditCard, CheckCircle, XCircle, Clock, TrendingUp, Smartphone } from 'lucide-react'

export default function Cotisations() {
  const { user } = useAuthStore()
  const { data: statut, isLoading } = useCotisationStatut()
  const initier = useInitierPaiement()
  const [annee, setAnnee] = useState(new Date().getFullYear())
  const { data: rapport } = useCotisationRapport(annee)

  const [paiementEnCours, setPaiementEnCours] = useState(false)
  const [operateur, setOperateur] = useState(null)

  const handlePaiement = async (op) => {
    setOperateur(op)
    setPaiementEnCours(true)
    try {
      const res = await initier.mutateAsync({ operateur: op, annee: new Date().getFullYear() })
      if (res.data?.data?.instructions) {
        alert(res.data.data.instructions)
      }
    } catch (err) {
      console.error(err)
    }
  }

  if (isLoading) {
    return (
      <div className="flex items-center justify-center h-96">
        <Loader2 className="animate-spin text-rac-gold" size={32} />
      </div>
    )
  }

  return (
    <div className="max-w-4xl mx-auto space-y-6">
      <h1 className="font-title text-2xl font-bold text-rac-dark">Cotisations</h1>

      <Card>
        <CardContent className="p-6">
          <div className="flex items-center justify-between mb-4">
            <div>
              <p className="text-sm text-gray-500">Statut {statut?.annee}</p>
              <p className="font-title text-3xl font-bold text-rac-dark mt-1">
                {statut?.montant?.toLocaleString('fr-FR') || '5 000'} FCFA
              </p>
            </div>
            <div className={`w-14 h-14 rounded-full flex items-center justify-center ${
              statut?.statut === 'paye' ? 'bg-rac-green/10' : statut?.statut === 'en_retard' ? 'bg-rac-red/10' : 'bg-rac-gold/10'
            }`}>
              {statut?.statut === 'paye' ? <CheckCircle size={28} className="text-rac-green" /> :
               statut?.statut === 'en_retard' ? <XCircle size={28} className="text-rac-red" /> :
               <Clock size={28} className="text-rac-gold" />}
            </div>
          </div>
          <Badge variant={statut?.statut === 'paye' ? 'success' : statut?.statut === 'en_retard' ? 'danger' : 'warning'} size="lg">
            {statut?.statut === 'paye' ? 'À JOUR ✓' : statut?.statut === 'en_retard' ? 'EN RETARD ✗' : 'EN ATTENTE'}
          </Badge>

          {statut?.statut !== 'paye' && !paiementEnCours && (
            <div className="mt-6 space-y-3">
              <p className="text-sm font-medium text-gray-700">Payer via :</p>
              <div className="flex gap-3">
                <Button onClick={() => handlePaiement('flooz')} className="flex-1 gap-2 bg-rac-orange hover:bg-rac-orange/90">
                  <Smartphone size={16} /> Flooz
                </Button>
                <Button onClick={() => handlePaiement('tmoney')} className="flex-1 gap-2 bg-rac-blue hover:bg-rac-blue/90">
                  <Smartphone size={16} /> T-Money
                </Button>
              </div>
              <p className="text-xs text-gray-500">
                Vous allez recevoir une demande de confirmation sur votre téléphone.
              </p>
            </div>
          )}

          {paiementEnCours && (
            <div className="mt-6 p-4 bg-rac-gold/10 border border-rac-gold/20 rounded-lg text-center">
              <Loader2 className="animate-spin mx-auto mb-2 text-rac-gold" size={24} />
              <p className="text-sm text-rac-gold-dark">
                Paiement {operateur} en cours de validation...
              </p>
              <p className="text-xs text-gray-500 mt-1">
                Vérifiez votre téléphone et confirmez la transaction.
              </p>
            </div>
          )}
        </CardContent>
      </Card>

      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <TrendingUp size={18} /> Historique
          </CardTitle>
        </CardHeader>
        <CardContent>
          <div className="space-y-2">
            {statut?.historique?.map((h, i) => (
              <div key={i} className="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div className="flex items-center gap-3">
                  <CreditCard size={16} className="text-gray-400" />
                  <div>
                    <p className="text-sm font-medium">{h.annee}</p>
                    <p className="text-xs text-gray-500">{h.canal_paiement || '—'}</p>
                  </div>
                </div>
                <div className="text-right">
                  <p className="font-mono text-sm">{h.montant?.toLocaleString('fr-FR')} FCFA</p>
                  <Badge variant={h.statut === 'paye' ? 'success' : 'warning'} size="sm">{h.statut}</Badge>
                </div>
              </div>
            ))}
          </div>
        </CardContent>
      </Card>

      {user?.role === 'ben' || user?.role === 'cena' || user?.role === 'admin' ? (
        <Card>
          <CardHeader>
            <CardTitle>Rapport global</CardTitle>
          </CardHeader>
          <CardContent>
            <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
              <div className="p-4 bg-gray-50 rounded-lg text-center">
                <p className="text-xs text-gray-500">Total alumni</p>
                <p className="font-title text-2xl font-bold">{rapport?.total_alumni}</p>
              </div>
              <div className="p-4 bg-gray-50 rounded-lg text-center">
                <p className="text-xs text-gray-500">Payés</p>
                <p className="font-title text-2xl font-bold text-rac-green">{rapport?.cotisations_payees}</p>
              </div>
              <div className="p-4 bg-gray-50 rounded-lg text-center">
                <p className="text-xs text-gray-500">Retard</p>
                <p className="font-title text-2xl font-bold text-rac-red">{rapport?.cotisations_retard}</p>
              </div>
              <div className="p-4 bg-gray-50 rounded-lg text-center">
                <p className="text-xs text-gray-500">Taux</p>
                <p className="font-title text-2xl font-bold text-rac-gold">{rapport?.taux_cotisation}%</p>
              </div>
            </div>
          </CardContent>
        </Card>
      ) : null}
    </div>
  )
}
