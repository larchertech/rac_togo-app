import React, { useState } from 'react'
import { Link } from 'react-router-dom'
import { useAlumni } from '../../hooks/useAlumni'
import { Card, CardContent } from '../../components/ui/Card'
import { Input } from '../../components/ui/Input'
import { Badge } from '../../components/ui/Badge'
import { Loader2, Search, Users, MapPin } from 'lucide-react'

export default function Annuaire() {
  const [search, setSearch] = useState('')
  const [clusterFilter, setClusterFilter] = useState('')
  const { data, isLoading } = useAlumni({ search, cluster_id: clusterFilter })

  const alumni = data?.data || []

  return (
    <div className="space-y-6">
      <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <h1 className="font-title text-2xl font-bold text-rac-dark">Annuaire des Alumni</h1>
        <div className="flex items-center gap-2 text-sm text-gray-500">
          <Users size={16} />
          <span>{alumni.length} résultats</span>
        </div>
      </div>

      <div className="flex flex-col sm:flex-row gap-3">
        <div className="relative flex-1">
          <Search size={16} className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
          <Input
            placeholder="Rechercher par nom, prénom, numéro..."
            value={search}
            onChange={(e) => setSearch(e.target.value)}
            className="pl-10"
          />
        </div>
        <select
          value={clusterFilter}
          onChange={(e) => setClusterFilter(e.target.value)}
          className="px-3 py-2.5 rounded-lg border border-gray-300 text-sm bg-white"
        >
          <option value="">Tous les clusters</option>
          <option value="1">Lomé Est</option>
          <option value="2">Lomé Ouest</option>
          <option value="3">Maritime</option>
        </select>
      </div>

      {isLoading ? (
        <div className="flex items-center justify-center h-96">
          <Loader2 className="animate-spin text-rac-gold" size={32} />
        </div>
      ) : (
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
          {alumni.map((a) => (
            <Card key={a.id} className="hover:shadow-md transition-shadow">
              <CardContent className="p-5">
                <div className="flex items-start gap-4">
                  <div className="w-12 h-12 rounded-full bg-rac-gold/10 flex items-center justify-center text-rac-gold font-title font-bold text-lg shrink-0">
                    {a.prenom?.[0]}{a.nom?.[0]}
                  </div>
                  <div className="flex-1 min-w-0">
                    <h3 className="font-medium text-gray-900 truncate">{a.nom_complet}</h3>
                    <div className="flex items-center gap-1 text-xs text-gray-500 mt-1">
                      <MapPin size={12} />
                      <span className="truncate">{a.cdej?.nom}, {a.cdej?.cluster?.nom}</span>
                    </div>
                    <div className="flex items-center gap-2 mt-2">
                      <Badge variant={a.statut_compte === 'valide' ? 'success' : 'warning'} size="sm">
                        {a.statut_compte}
                      </Badge>
                      <span className="text-xs text-gray-400 font-mono">{a.numero_membre}</span>
                    </div>
                  </div>
                </div>
              </CardContent>
            </Card>
          ))}
        </div>
      )}
    </div>
  )
}
