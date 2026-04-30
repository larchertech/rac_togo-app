import React from 'react'
import { Link, useLocation, useNavigate } from 'react-router-dom'
import { useAuthStore } from '../../store/authStore'
import { useAppStore } from '../../store/appStore'
import {
  LayoutDashboard,
  Vote,
  Users,
  Wallet,
  Sitemap,
  Shield,
  LogOut,
  Menu,
  X,
  ChevronRight,
} from 'lucide-react'

const navigation = [
  { name: 'Tableau de bord', href: '/', icon: LayoutDashboard },
  { name: 'Élections', href: '/elections', icon: Vote },
  { name: 'Annuaire', href: '/annuaire', icon: Users },
  { name: 'Cotisations', href: '/cotisations', icon: Wallet },
  { name: 'Organigramme', href: '/organigramme', icon: Sitemap },
]

const adminNavigation = [
  { name: 'Commission', href: '/admin/commission', icon: Shield },
  { name: 'Validation', href: '/admin/validation', icon: Shield },
  { name: 'Proclamation', href: '/admin/proclamation', icon: Shield },
]

export default function Sidebar() {
  const location = useLocation()
  const navigate = useNavigate()
  const { user, logout } = useAuthStore()
  const { sidebarOpen, closeSidebar, currentPhase } = useAppStore()

  const isAdmin = user?.role === 'admin' || user?.role === 'cena' || user?.role === 'cec' || user?.role === 'cel'

  const handleLogout = () => {
    logout()
  }

  return (
    <>
      {/* Mobile overlay */}
      {sidebarOpen && (
        <div
          className="fixed inset-0 bg-black/50 z-40 lg:hidden"
          onClick={closeSidebar}
        />
      )}

      <aside
        className={`fixed top-0 left-0 z-50 h-full w-64 bg-rac-dark text-white transition-transform duration-300 lg:translate-x-0 ${
          sidebarOpen ? 'translate-x-0' : '-translate-x-full'
        }`}
      >
        <div className="flex flex-col h-full">
          {/* Header */}
          <div className="p-6 border-b border-white/10">
            <div className="flex items-center justify-between">
              <Link to="/" className="flex items-center gap-2" onClick={closeSidebar}>
                <div className="w-8 h-8 bg-rac-gold rounded-full flex items-center justify-center">
                  <span className="text-rac-dark font-title font-bold text-sm">RAC</span>
                </div>
                <span className="font-title text-lg font-bold text-rac-gold">RAC-TOGO</span>
              </Link>
              <button onClick={closeSidebar} className="lg:hidden text-white/70 hover:text-white">
                <X size={20} />
              </button>
            </div>
            <div className="mt-3 inline-flex items-center gap-2 px-2 py-1 bg-rac-green/20 rounded-full">
              <span className="w-2 h-2 bg-rac-green rounded-full animate-pulse" />
              <span className="text-xs font-mono text-rac-green-light uppercase">{currentPhase} en cours</span>
            </div>
          </div>

          {/* Navigation */}
          <nav className="flex-1 overflow-y-auto p-4 space-y-1">
            {navigation.map((item) => {
              const isActive = location.pathname === item.href || location.pathname.startsWith(item.href + '/')
              return (
                <Link
                  key={item.name}
                  to={item.href}
                  onClick={closeSidebar}
                  className={`flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors ${
                    isActive
                      ? 'bg-rac-gold/20 text-rac-gold'
                      : 'text-white/70 hover:bg-white/5 hover:text-white'
                  }`}
                >
                  <item.icon size={18} />
                  <span className="font-medium">{item.name}</span>
                  {isActive && <ChevronRight size={14} className="ml-auto" />}
                </Link>
              )
            })}

            {isAdmin && (
              <>
                <div className="pt-4 mt-4 border-t border-white/10">
                  <p className="px-3 text-xs font-mono text-white/40 uppercase mb-2">Administration</p>
                  {adminNavigation.map((item) => {
                    const isActive = location.pathname === item.href
                    return (
                      <Link
                        key={item.name}
                        to={item.href}
                        onClick={closeSidebar}
                        className={`flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors ${
                          isActive
                            ? 'bg-rac-gold/20 text-rac-gold'
                            : 'text-white/70 hover:bg-white/5 hover:text-white'
                        }`}
                      >
                        <item.icon size={18} />
                        <span className="font-medium">{item.name}</span>
                      </Link>
                    )
                  })}
                </div>
              </>
            )}
          </nav>

          {/* Profile */}
          <div className="p-4 border-t border-white/10">
            <Link
              to="/profil"
              onClick={closeSidebar}
              className="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/5 transition-colors"
            >
              <div className="w-9 h-9 rounded-full bg-rac-gold/20 flex items-center justify-center text-rac-gold font-title font-bold">
                {user?.alumni?.prenom?.[0] || user?.role?.[0] || '?'}
              </div>
              <div className="flex-1 min-w-0">
                <p className="text-sm font-medium truncate">
                  {user?.alumni?.prenom || 'Utilisateur'}
                </p>
                <p className="text-xs text-white/50 font-mono uppercase">{user?.role || 'alumni'}</p>
              </div>
            </Link>
            <button
              onClick={handleLogout}
              className="flex items-center gap-3 px-3 py-2 mt-2 w-full rounded-lg text-white/50 hover:text-rac-red hover:bg-rac-red/10 transition-colors text-sm"
            >
              <LogOut size={16} />
              <span>Déconnexion</span>
            </button>
          </div>
        </div>
      </aside>
    </>
  )
}
