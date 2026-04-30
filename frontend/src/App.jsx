import React from 'react'
import { Routes, Route, Navigate } from 'react-router-dom'
import { useAuthStore } from './store/authStore'
import AppLayout from './components/layout/AppLayout'
import Login from './pages/Auth/Login'
import Inscription from './pages/Onboarding/Inscription'
import Dashboard from './pages/Dashboard/Dashboard'
import ElectionsList from './pages/Elections/ElectionsList'
import ElectionDetail from './pages/Elections/ElectionDetail'
import DepotCandidature from './pages/Elections/DepotCandidature'
import BureauDeVote from './pages/Elections/BureauDeVote'
import Profil from './pages/Alumni/Profil'
import Annuaire from './pages/Alumni/Annuaire'
import Cotisations from './pages/Cotisations/Cotisations'
import Organigramme from './pages/Organigramme/Organigramme'
import CommissionDashboard from './pages/Admin/CommissionDashboard'
import ValidationCandidatures from './pages/Admin/ValidationCandidatures'
import Proclamation from './pages/Admin/Proclamation'

function PrivateRoute({ children }) {
  const isAuthenticated = useAuthStore((state) => state.isAuthenticated)
  return isAuthenticated ? children : <Navigate to="/login" replace />
}

function App() {
  return (
    <Routes>
      <Route path="/login" element={<Login />} />
      <Route path="/inscription" element={<Inscription />} />
      <Route path="/" element={
        <PrivateRoute>
          <AppLayout />
        </PrivateRoute>
      }>
        <Route index element={<Dashboard />} />
        <Route path="elections" element={<ElectionsList />} />
        <Route path="elections/:id" element={<ElectionDetail />} />
        <Route path="elections/:id/candidature" element={<DepotCandidature />} />
        <Route path="elections/:id/vote" element={<BureauDeVote />} />
        <Route path="profil" element={<Profil />} />
        <Route path="annuaire" element={<Annuaire />} />
        <Route path="cotisations" element={<Cotisations />} />
        <Route path="organigramme" element={<Organigramme />} />
        <Route path="admin/commission" element={<CommissionDashboard />} />
        <Route path="admin/validation" element={<ValidationCandidatures />} />
        <Route path="admin/proclamation" element={<Proclamation />} />
      </Route>
    </Routes>
  )
}

export default App
