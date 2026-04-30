import React from 'react'
import { useDashboardStats, useDashboardClusters, useDashboardElectoral, useDashboardActivite, useDashboardAlertes } from '../../hooks/useDashboard'
import { StatCard } from '../../components/ui/StatCard'
import { Card, CardContent, CardHeader, CardTitle } from '../../components/ui/Card'
import { Badge } from '../../components/ui/Badge'
import { BarChart, Bar, XAxis, YAxis, CartesianGrid, Tooltip, ResponsiveContainer, PieChart, Pie, Cell, Legend } from 'recharts'
import { Users, Building2, MapPin, FileText, AlertTriangle, CheckCircle, Info, Clock } from 'lucide-react'

const COLORS = ['#C8A45C', '#2D6A4F', '#0077B6', '#E85D04', '#C1121F', '#6B7280', '#8B5CF6', '#EC4899']

function ClusterBarChart({ data }) {
  const top8 = data?.slice(0, 8) || []
  return (
    <ResponsiveContainer width="100%" height={280}>
      <BarChart data={top8} margin={{ top: 10, right: 10, left: -20, bottom: 0 }}>
        <CartesianGrid strokeDasharray="3 3" stroke="#f0f0f0" />
        <XAxis dataKey="nom" tick={{ fontSize: 11 }} angle={-25} textAnchor="end" height={60} />
        <YAxis tick={{ fontSize: 11 }} />
        <Tooltip
          contentStyle={{ borderRadius: 8, border: 'none', boxShadow: '0 4px 12px rgba(0,0,0,0.1)' }}
          formatter={(value) => [value, 'Alumni']}
        />
        <Bar dataKey="total" radius={[4, 4, 0, 0]}>
          {top8.map((entry, index) => (
            <Cell key={`cell-${index}`} fill={entry.nom?.includes('Lomé') ? '#C8A45C' : '#2D6A4F'} />
          ))}
        </Bar>
      </BarChart>
    </ResponsiveContainer>
  )
}

function AlumniDonut({ data }) {
  const top5 = data?.slice(0, 5) || []
  const chartData = top5.map((c) => ({ name: c.nom, value: c.total }))

  return (
    <ResponsiveContainer width="100%" height={250}>
      <PieChart>
        <Pie
          data={chartData}
          cx="50%"
          cy="50%"
          innerRadius={60}
          outerRadius={90}
          paddingAngle={3}
          dataKey="value"
        >
          {chartData.map((entry, index) => (
            <Cell key={`cell-${index}`} fill={COLORS[index % COLORS.length]} />
          ))}
        </Pie>
        <Tooltip />
        <Legend verticalAlign="bottom" height={36} iconType="circle" />
      </PieChart>
    </ResponsiveContainer>
  )
}

function PhaseTimeline({ phases }) {
  return (
    <div className="space-y-4">
      {phases?.map((phase, index) => {
        const isActive = phase.statut === 'en_cours'
        const isDone = phase.statut === 'termine'
        return (
          <div key={index} className="flex items-center gap-4">
            <div className={`w-3 h-3 rounded-full shrink-0 ${
              isActive ? 'bg-rac-gold animate-pulse' : isDone ? 'bg-rac-green' : 'bg-gray-300'
            }`} />
            <div className="flex-1">
              <div className="flex items-center justify-between">
                <span className={`text-sm font-medium ${isActive ? 'text-rac-gold-dark' : 'text-gray-700'}`}>
                  {phase.nom}
                </span>
                <span className="text-xs text-gray-500 font-mono">
                  {phase.debut} → {phase.fin}
                </span>
              </div>
              <div className="mt-1.5 h-2 bg-gray-100 rounded-full overflow-hidden">
                <div
                  className={`h-full rounded-full transition-all ${
                    isActive ? 'bg-rac-gold' : isDone ? 'bg-rac-green' : 'bg-gray-300'
                  }`}
                  style={{ width: isDone ? '100%' : isActive ? '60%' : '0%' }}
                />
              </div>
            </div>
          </div>
        )
      })}
    </div>
  )
}

function ActivityFeed({ activities }) {
  const getIcon = (action) => {
    if (action?.includes('vote')) return <CheckCircle size={14} className="text-rac-green" />
    if (action?.includes('erreur') || action?.includes('rejet')) return <AlertTriangle size={14} className="text-rac-red" />
    if (action?.includes('candidature')) return <FileText size={14} className="text-rac-blue" />
    return <Info size={14} className="text-rac-gold" />
  }

  return (
    <div className="space-y-3">
      {activities?.slice(0, 6).map((activity, index) => (
        <div key={index} className="flex items-start gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors">
          <div className="mt-0.5">{getIcon(activity.action)}</div>
          <div className="flex-1 min-w-0">
            <p className="text-sm text-gray-800 truncate">{activity.action}</p>
            <p className="text-xs text-gray-500 mt-0.5">{activity.user} — {activity.entite}</p>
          </div>
          <span className="text-xs text-gray-400 font-mono shrink-0">{activity.date}</span>
        </div>
      ))}
    </div>
  )
}

export default function Dashboard() {
  const { data: stats } = useDashboardStats()
  const { data: clusters } = useDashboardClusters()
  const { data: electoral } = useDashboardElectoral()
  const { data: activite } = useDashboardActivite()
  const { data: alertes } = useDashboardAlertes()

  return (
    <div className="space-y-6 animate-fade-in">
      {/* Alertes */}
      {alertes && alertes.length > 0 && (
        <div className="space-y-2">
          {alertes.map((alerte, i) => (
            <div
              key={i}
              className={`flex items-center gap-3 p-3 rounded-lg ${
                alerte.type === 'danger'
                  ? 'bg-rac-red/10 border border-rac-red/20 text-rac-red'
                  : 'bg-rac-gold/10 border border-rac-gold/20 text-rac-gold-dark'
              }`}
            >
              <AlertTriangle size={16} />
              <span className="text-sm">{alerte.message}</span>
            </div>
          ))}
        </div>
      )}

      {/* Stat Cards */}
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <StatCard
          title="Alumni"
          value={stats?.alumni || 0}
          icon={Users}
          color="gold"
          badge="LIVE"
        />
        <StatCard
          title="CDEJ"
          value={stats?.cdej || 0}
          icon={Building2}
          color="green"
        />
        <StatCard
          title="Clusters"
          value={stats?.clusters || 0}
          icon={MapPin}
          color="blue"
        />
        <StatCard
          title="Candidatures"
          value={stats?.candidatures_recues || 0}
          icon={FileText}
          color="red"
          badge="REÇUES"
        />
      </div>

      {/* Charts */}
      <div className="grid grid-cols-1 lg:grid-cols-5 gap-6">
        <Card className="lg:col-span-3">
          <CardHeader>
            <CardTitle>Répartition par cluster</CardTitle>
          </CardHeader>
          <CardContent>
            <ClusterBarChart data={clusters} />
          </CardContent>
        </Card>
        <Card className="lg:col-span-2">
          <CardHeader>
            <CardTitle>Top 5 clusters</CardTitle>
          </CardHeader>
          <CardContent>
            <AlumniDonut data={clusters} />
          </CardContent>
        </Card>
      </div>

      {/* Timeline & Activity */}
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <Card>
          <CardHeader>
            <CardTitle>Phases électorales</CardTitle>
          </CardHeader>
          <CardContent>
            <PhaseTimeline phases={electoral?.phases} />
          </CardContent>
        </Card>
        <Card>
          <CardHeader>
            <CardTitle>Flux d'activité récent</CardTitle>
          </CardHeader>
          <CardContent>
            <ActivityFeed activities={activite} />
          </CardContent>
        </Card>
      </div>
    </div>
  )
}
