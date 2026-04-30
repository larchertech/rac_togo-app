import React from 'react'
import { useNavigate } from 'react-router-dom'
import { useQuery } from '@tanstack/react-query'
import api from '../../api/axios'
import { Card, CardContent, CardHeader, CardTitle } from '../../components/ui/Card'
import { StatCard } from '../../components/ui/StatCard'
import { Badge } from '../../components/ui/Badge'
import { Button } from '../../components/ui/Button'
import { Loader2, Shield, AlertTriangle, FileText, CheckCircle, Users, TrendingUp } from 'lucide-react'

function useCommissionDashboard() {
  return useQuery({
    queryKey: ['commission', 'dashboard'],
    queryFn: () => api.get('/commission/dashboard').then((res) => res.data.data),
  })
}

export default function CommissionDashboard() {
  const { data, isLoading } = useCommissionDashboard()
  const navigate = useNavigate()

  if (isLoading) {
    return (
      <div className="flex items-center justify-center h-96">
        <Loader2 className="animate-spin text-rac-gold" size={32} />
      </div>
    )
  }

  const c = data?.candidatures || {}

  return (
    <div className="space-y-6">
      <div className="flex items-center justify-between">
        <h1 className="font-title text-2xl font-bold text-rac-dark">Dashboard Commission</h1>
        <Badge variant="dark" className="gap-1">
          <Shield size={12} /> CENA / CEC / CEL
        </Badge>
      </div>

      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <StatCard title="Reçues" value={c.total || 0} icon={FileText} color="blue" />
        <StatCard title="Validées" value={c.valide || 0} icon={CheckCircle} color="green" />
        <StatCard title="Rejetées" value={c.rejete || 0} icon={AlertTriangle} color="red" />
        <StatCard title="En examen" value={c.en_examen || 0} icon={Users} color="gold" />
      </div>

      {data?.alertes?.length > 0 && (
        <div className="space-y-2">
          {data.alertes.map((alerte, i) => (
            <div key={i} className={`flex items-center gap-3 p-3 rounded-lg ${
              alerte.type === 'danger'
                ? 'bg-rac-red/10 border border-rac-red/20 text-rac-red'
                : 'bg-rac-gold/10 border border-rac-gold/20 text-rac-gold-dark'
            }`}>
              <AlertTriangle size={16} />
              <span className="text-sm">{alerte.message}</span>
            </div>
          ))}
        </div>
      )}

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <Card>
          <CardHeader>
            <CardTitle>Actions rapides</CardTitle>
          </CardHeader>
          <CardContent>
            <div className="grid grid-cols-1 sm:grid-cols-2 gap-3">
              <Button variant="outline" className="justify-start gap-2" onClick={() => navigate('/admin/validation')}>
                <FileText size={16} /> Valider candidatures
              </Button>
              <Button variant="outline" className="justify-start gap-2" onClick={() => navigate('/admin/proclamation')}>
                <TrendingUp size={16} /> Proclamer résultats
              </Button>
            </div>
          </CardContent>
        </Card>

        <Card>
          <CardHeader>
            <CardTitle>Élections en cours</CardTitle>
          </CardHeader>
          <CardContent>
            <div className="space-y-3">
              {(data?.elections_en_cours || 0) === 0 ? (
                <p className="text-gray-500 text-center py-4">Aucune élection active</p>
              ) : (
                <div className="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                  <span className="text-sm font-medium">{data.elections_en_cours} élection(s) en cours</span>
                  <Badge variant="success">ACTIVE</Badge>
                </div>
              )}
            </div>
          </CardContent>
        </Card>
      </div>
    </div>
  )
}
