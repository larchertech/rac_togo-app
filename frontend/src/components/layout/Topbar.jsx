import React from 'react'
import { useLocation, Link } from 'react-router-dom'
import { useAppStore } from '../../store/appStore'
import { Menu, Bell, Calendar } from 'lucide-react'

const breadcrumbMap = {
  '/': 'Tableau de bord',
  '/elections': 'Élections',
  '/annuaire': 'Annuaire',
  '/cotisations': 'Cotisations',
  '/organigramme': 'Organigramme',
  '/profil': 'Mon profil',
  '/admin/commission': 'Commission',
  '/admin/validation': 'Validation',
  '/admin/proclamation': 'Proclamation',
}

export default function Topbar() {
  const location = useLocation()
  const { toggleSidebar, currentPhase } = useAppStore()

  const today = new Date().toLocaleDateString('fr-FR', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  })

  const title = breadcrumbMap[location.pathname] || 'RAC-TOGO'

  return (
    <header className="sticky top-0 z-30 bg-white border-b border-gray-200 shadow-sm">
      <div className="flex items-center justify-between px-4 py-3 lg:px-8">
        <div className="flex items-center gap-3">
          <button
            onClick={toggleSidebar}
            className="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors"
          >
            <Menu size={20} />
          </button>
          <div>
            <h1 className="font-title text-xl font-semibold text-rac-dark">{title}</h1>
            <div className="flex items-center gap-2 text-xs text-gray-500 mt-0.5">
              <Calendar size={12} />
              <span className="capitalize">{today}</span>
            </div>
          </div>
        </div>

        <div className="flex items-center gap-3">
          <div className="hidden md:flex items-center gap-2 px-3 py-1.5 bg-rac-green/10 rounded-full">
            <span className="w-2 h-2 bg-rac-green rounded-full animate-pulse" />
            <span className="text-xs font-mono font-medium text-rac-green uppercase">{currentPhase}</span>
          </div>
          <button className="relative p-2 rounded-lg hover:bg-gray-100 transition-colors">
            <Bell size={18} className="text-gray-600" />
            <span className="absolute top-1.5 right-1.5 w-2 h-2 bg-rac-red rounded-full" />
          </button>
        </div>
      </div>
    </header>
  )
}
