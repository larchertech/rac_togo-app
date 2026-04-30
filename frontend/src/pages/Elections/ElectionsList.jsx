import React, { useState } from 'react'
import { Link } from 'react-router-dom'
import { useElections } from '../../hooks/useElection'
import { Card, CardContent, CardHeader, CardTitle } from '../../components/ui/Card'
import { Badge } from '../../components/ui/Badge'
import { Button } from '../../components/ui/Button'
import { Loader2, Vote, Calendar, ChevronRight } from 'lucide-react'

const statutColors = {
  brouillon: 'default',
  preparation: 'warning',
  candidatures: 'warning',
  campagne: 'info',
  vote: 'success',
  depouillement: 'info',
  proclame: 'success',
  archive: 'default',
}

const typeLabels = {
  bla: 'Bureau Local Alumni',
  bca: 'Bureau Cluster Alumni',
  be: 'Bureau Exécutif National',
}

export default function ElectionsList() {
  const [filter, setFilter] = useState('')
  const { data, isLoading } = useElections({ type: filter || undefined })

  if (isLoading) {
    return (
      <div className="flex items-center justify-center h-96">
        <Loader2 className="animate-spin text-rac-gold" size={32} />
      </div>
    )
  }

  const elections = data?.data || []

  return (
    <div className="space-y-6">
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <h1 className="font-title text-2xl font-bold text-rac-dark">Élections</h1>
        <div className="flex gap-2">
          <Button
            variant={filter === '' ? 'primary' : 'outline'}
            size="sm"
            onClick={() => setFilter('')}
          >
            Toutes
          </Button>
          <Button
            variant={filter === 'bla' ? 'primary' : 'outline'}
            size="sm"
            onClick={() => setFilter('bla')}
          >
            BLA
          </Button>
          <Button
            variant={filter === 'bca' ? 'primary' : 'outline'}
            size="sm"
            onClick={() => setFilter('bca')}
          >
            BCA
          </Button>
          <Button
            variant={filter === 'be' ? 'primary' : 'outline'}
            size="sm"
            onClick={() => setFilter('be')}
          >
            BE
          </Button>
        </div>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        {elections.map((election) => (
          <Card key={election.id} className="hover:shadow-md transition-shadow">
            <CardHeader>
              <div className="flex items-center justify-between">
                <Badge variant={statutColors[election.statut] || 'default'}>
                  {election.statut}
                </Badge>
                <span className="text-xs font-mono text-gray-400">{election.type?.toUpperCase()}</span>
              </div>
              <CardTitle className="mt-2">{typeLabels[election.type]}</CardTitle>
            </CardHeader>
            <CardContent>
              <div className="space-y-2">
                <div className="flex items-center gap-2 text-sm text-gray-600">
                  <Calendar size={14} />
                  <span>Vote : {election.date_vote}</span>
                </div>
                <div className="flex items-center gap-2 text-sm text-gray-600">
                  <Vote size={14} />
                  <span>Mode : {election.mode_scrutin?.replace('_', ' ')}</span>
                </div>
                <div className="flex flex-wrap gap-1 mt-3">
                  {election.postes?.map((poste) => (
                    <span key={poste} className="text-xs px-2 py-1 bg-gray-100 rounded-md capitalize">
                      {poste.replace('_', ' ')}
                    </span>
                  ))}
                </div>
              </div>
              <div className="mt-4 pt-4 border-t border-gray-100 flex gap-2">
                <Link to={`/elections/${election.id}`} className="flex-1">
                  <Button variant="outline" size="sm" className="w-full">
                    Détails <ChevronRight size={14} />
                  </Button>
                </Link>
                {election.statut === 'vote' && (
                  <Link to={`/elections/${election.id}/vote`} className="flex-1">
                    <Button variant="primary" size="sm" className="w-full gap-1">
                      <Vote size={14} /> Voter
                    </Button>
                  </Link>
                )}
              </div>
            </CardContent>
          </Card>
        ))}
      </div>
    </div>
  )
}
