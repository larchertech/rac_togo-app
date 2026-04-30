import React from 'react'
import { useMe } from '../../hooks/useAuth'
import { useCotisationStatut } from '../../hooks/useCotisation'
import { Card, CardContent, CardHeader, CardTitle } from '../../components/ui/Card'
import { Badge } from '../../components/ui/Badge'
import { Button } from '../../components/ui/Button'
import { QRCodeSVG } from 'qrcode.react'
import { User, CreditCard, FileText, CheckCircle, XCircle, Clock, Download } from 'lucide-react'

const statutColors = {
  valide: 'success',
  en_attente: 'warning',
  rejete: 'danger',
}

const cotisationColors = {
  paye: 'success',
  en_attente: 'warning',
  en_retard: 'danger',
}

export default function Profil() {
  const { data: me, isLoading } = useMe()
  const { data: cotisation } = useCotisationStatut()

  if (isLoading) {
    return (
      <div className="flex items-center justify-center h-96">
        <div className="animate-spin w-8 h-8 border-2 border-rac-gold border-t-transparent rounded-full" />
      </div>
    )
  }

  const alumni = me
  if (!alumni) {
    return (
      <div className="text-center py-12">
        <p className="text-gray-500">Profil non trouvé</p>
      </div>
    )
  }

  const qrData = JSON.stringify({
    id: alumni.id,
    nom: alumni.nom_complet,
    cdej: alumni.cdej?.nom,
    statut: alumni.statut_compte,
  })

  return (
    <div className="max-w-4xl mx-auto space-y-6">
      {/* Header Profile */}
      <Card>
        <CardContent className="p-6">
          <div className="flex flex-col md:flex-row items-start md:items-center gap-6">
            <div className="w-20 h-20 rounded-full bg-rac-gold/20 flex items-center justify-center text-rac-gold font-title text-2xl font-bold">
              {alumni.prenom?.[0]}{alumni.nom?.[0]}
            </div>
            <div className="flex-1">
              <h1 className="font-title text-2xl font-bold text-rac-dark">{alumni.nom_complet}</h1>
              <div className="flex flex-wrap items-center gap-2 mt-2">
                <span className="text-sm text-gray-600">{alumni.cdej?.nom}</span>
                <span className="text-gray-300">|</span>
                <span className="text-sm text-gray-600">{alumni.cdej?.cluster?.nom}</span>
                <Badge variant={statutColors[alumni.statut_compte] || 'default'}>
                  {alumni.statut_compte}
                </Badge>
              </div>
            </div>
            <Button variant="outline" size="sm" className="gap-2">
              <User size={14} /> Modifier
            </Button>
          </div>
        </CardContent>
      </Card>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {/* Carte membre */}
        <Card className="bg-rac-dark text-white overflow-hidden relative">
          <div className="absolute inset-0 kente-pattern opacity-5" />
          <CardContent className="p-6 relative">
            <div className="flex items-center justify-between mb-6">
              <div className="flex items-center gap-2">
                <div className="w-8 h-8 bg-rac-gold rounded-full flex items-center justify-center">
                  <span className="text-rac-dark font-title font-bold text-xs">RAC</span>
                </div>
                <span className="font-title text-rac-gold font-bold">RAC-TOGO</span>
              </div>
              <Badge variant="warning" className="bg-rac-gold/20 text-rac-gold border-rac-gold/30">
                MEMBRE
              </Badge>
            </div>
            <div className="space-y-3">
              <div>
                <p className="text-xs text-white/50 font-mono">N° MEMBRE</p>
                <p className="font-mono text-lg text-rac-gold">{alumni.numero_membre || 'En attente'}</p>
              </div>
              <div>
                <p className="text-xs text-white/50">NOM</p>
                <p className="font-medium">{alumni.nom_complet}</p>
              </div>
              <div className="flex justify-between">
                <div>
                  <p className="text-xs text-white/50">CDEJ</p>
                  <p className="text-sm">{alumni.cdej?.nom}</p>
                </div>
                <div>
                  <p className="text-xs text-white/50">ADHÉSION</p>
                  <p className="text-sm">{alumni.created_at?.split('/')[2] || '2026'}</p>
                </div>
              </div>
            </div>
            <div className="mt-6 flex items-center justify-between">
              <QRCodeSVG value={qrData} size={80} level="M" bgColor="transparent" fgColor="#C8A45C" />
              <Button variant="outline" size="sm" className="border-white/30 text-white hover:bg-white/10 gap-2">
                <Download size={14} /> PDF
              </Button>
            </div>
          </CardContent>
        </Card>

        {/* Cotisation */}
        <Card>
          <CardHeader>
            <CardTitle className="flex items-center gap-2">
              <CreditCard size={18} /> Cotisation
            </CardTitle>
          </CardHeader>
          <CardContent>
            <div className="space-y-4">
              <div className="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div>
                  <p className="text-sm text-gray-600">Année {cotisation?.annee || new Date().getFullYear()}</p>
                  <p className="font-title text-2xl font-bold text-rac-dark">
                    {cotisation?.montant?.toLocaleString('fr-FR') || '5 000'} FCFA
                  </p>
                </div>
                <Badge variant={cotisationColors[cotisation?.statut] || 'warning'} size="lg">
                  {cotisation?.statut === 'paye' ? 'À JOUR ✓' : cotisation?.statut === 'en_retard' ? 'EN RETARD ✗' : 'EN ATTENTE'}
                </Badge>
              </div>
              {cotisation?.statut !== 'paye' && (
                <div className="flex gap-2">
                  <Button variant="primary" className="flex-1 bg-rac-orange hover:bg-rac-orange/90">
                    Payer Flooz
                  </Button>
                  <Button variant="info" className="flex-1">
                    Payer T-Money
                  </Button>
                </div>
              )}
              <div className="space-y-2">
                <p className="text-xs font-medium text-gray-500 uppercase">Historique</p>
                {cotisation?.historique?.map((h, i) => (
                  <div key={i} className="flex items-center justify-between text-sm p-2 bg-gray-50 rounded">
                    <span>{h.annee}</span>
                    <span className="font-mono">{h.montant?.toLocaleString('fr-FR')} FCFA</span>
                    <Badge variant={cotisationColors[h.statut] || 'default'}>{h.statut}</Badge>
                  </div>
                ))}
              </div>
            </div>
          </CardContent>
        </Card>
      </div>

      {/* Documents */}
      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <FileText size={18} /> Documents
          </CardTitle>
        </CardHeader>
        <CardContent>
          <div className="space-y-3">
            {(alumni.documents || []).length === 0 ? (
              <p className="text-gray-500 text-center py-4">Aucun document uploadé</p>
            ) : (
              alumni.documents.map((doc, i) => (
                <div key={i} className="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                  <div className="flex items-center gap-3">
                    <FileText size={18} className="text-rac-gold" />
                    <div>
                      <p className="text-sm font-medium">{doc.type}</p>
                      <p className="text-xs text-gray-500">{doc.uploaded_at}</p>
                    </div>
                  </div>
                  <Badge variant="success">Validé</Badge>
                </div>
              ))
            )}
            <Button variant="outline" size="sm" className="w-full gap-2">
              <Upload size={14} /> Ajouter un document
            </Button>
          </div>
        </CardContent>
      </Card>
    </div>
  )
}
