import React from 'react'
import { Card, CardContent, CardHeader, CardTitle } from '../../components/ui/Card'
import { Building2, Users, MapPin, Crown } from 'lucide-react'

export default function Organigramme() {
  const structure = {
    national: {
      title: 'Bureau Exécutif National (BEN)',
      members: [
        { role: 'Président National', name: 'À élire' },
        { role: 'Vice-Président', name: 'À élire' },
        { role: 'Secrétaire Général', name: 'À élire' },
        { role: 'Trésorier', name: 'À élire' },
        { role: 'Conseiller', name: 'À élire' },
      ],
    },
    commissions: [
      { title: 'CENA', fullName: 'Commission Électorale Nationale Autonome', role: 'Organisation des élections' },
      { title: 'CEC', fullName: 'Commission Électorale de Cluster', role: 'Supervision cluster' },
      { title: 'CEL', fullName: 'Commission Électorale Locale', role: 'Supervision CDEJ' },
    ],
  }

  return (
    <div className="max-w-4xl mx-auto space-y-6">
      <h1 className="font-title text-2xl font-bold text-rac-dark">Organigramme</h1>

      <Card className="bg-rac-dark text-white">
        <CardHeader>
          <CardTitle className="flex items-center gap-2 text-rac-gold">
            <Crown size={20} /> {structure.national.title}
          </CardTitle>
        </CardHeader>
        <CardContent>
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            {structure.national.members.map((m, i) => (
              <div key={i} className="p-4 bg-white/5 rounded-lg border border-white/10">
                <p className="text-xs text-rac-gold uppercase font-medium">{m.role}</p>
                <p className="text-lg font-title font-semibold mt-1">{m.name}</p>
              </div>
            ))}
          </div>
        </CardContent>
      </Card>

      <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
        {structure.commissions.map((c, i) => (
          <Card key={i}>
            <CardHeader>
              <CardTitle className="text-lg">{c.title}</CardTitle>
            </CardHeader>
            <CardContent>
              <p className="text-sm text-gray-600 mb-1">{c.fullName}</p>
              <p className="text-xs text-rac-gold">{c.role}</p>
            </CardContent>
          </Card>
        ))}
      </div>

      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <Building2 size={18} /> Structure territoriale
          </CardTitle>
        </CardHeader>
        <CardContent>
          <div className="space-y-4">
            <div className="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
              <div className="w-10 h-10 bg-rac-gold/10 rounded-full flex items-center justify-center">
                <MapPin size={18} className="text-rac-gold" />
              </div>
              <div>
                <p className="font-medium">19 Clusters</p>
                <p className="text-sm text-gray-500">Répartis sur 5 régions administratives</p>
              </div>
            </div>
            <div className="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
              <div className="w-10 h-10 bg-rac-green/10 rounded-full flex items-center justify-center">
                <Building2 size={18} className="text-rac-green" />
              </div>
              <div>
                <p className="font-medium">134 CDEJ</p>
                <p className="text-sm text-gray-500">Centres de Développement des Enfants et Jeunes</p>
              </div>
            </div>
            <div className="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
              <div className="w-10 h-10 bg-rac-blue/10 rounded-full flex items-center justify-center">
                <Users size={18} className="text-rac-blue" />
              </div>
              <div>
                <p className="font-medium">5 062 Alumni</p>
                <p className="text-sm text-gray-500">Membres du RAC-TOGO</p>
              </div>
            </div>
          </div>
        </CardContent>
      </Card>
    </div>
  )
}
