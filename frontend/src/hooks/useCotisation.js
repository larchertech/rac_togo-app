import { useQuery, useMutation } from '@tanstack/react-query'
import { cotisationApi } from '../api/cotisation.api'

export function useCotisationStatut() {
  return useQuery({
    queryKey: ['cotisation-statut'],
    queryFn: () => cotisationApi.statut().then((res) => res.data.data),
  })
}

export function useInitierPaiement() {
  return useMutation({
    mutationFn: ({ operateur, annee }) => cotisationApi.initier(operateur, annee),
  })
}

export function useCotisationRapport(annee) {
  return useQuery({
    queryKey: ['cotisation-rapport', annee],
    queryFn: () => cotisationApi.rapport(annee).then((res) => res.data.data),
    enabled: !!annee,
  })
}
