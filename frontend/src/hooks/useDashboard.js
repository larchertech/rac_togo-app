import { useQuery } from '@tanstack/react-query'
import api from '../api/axios'

export function useDashboardStats() {
  return useQuery({
    queryKey: ['dashboard', 'stats'],
    queryFn: () => api.get('/dashboard/stats').then((res) => res.data.data),
  })
}

export function useDashboardClusters() {
  return useQuery({
    queryKey: ['dashboard', 'clusters'],
    queryFn: () => api.get('/dashboard/clusters').then((res) => res.data.data),
  })
}

export function useDashboardActivite() {
  return useQuery({
    queryKey: ['dashboard', 'activite'],
    queryFn: () => api.get('/dashboard/activite').then((res) => res.data.data),
  })
}

export function useDashboardElectoral() {
  return useQuery({
    queryKey: ['dashboard', 'electoral'],
    queryFn: () => api.get('/dashboard/electoral').then((res) => res.data.data),
  })
}

export function useDashboardAlertes() {
  return useQuery({
    queryKey: ['dashboard', 'alertes'],
    queryFn: () => api.get('/dashboard/alertes').then((res) => res.data.data),
  })
}
