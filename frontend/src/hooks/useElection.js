import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query'
import { electionApi } from '../api/election.api'

export function useElections(params = {}) {
  return useQuery({
    queryKey: ['elections', params],
    queryFn: () => electionApi.list(params).then((res) => res.data),
  })
}

export function useElection(id) {
  return useQuery({
    queryKey: ['election', id],
    queryFn: () => electionApi.show(id).then((res) => res.data.data),
    enabled: !!id,
  })
}

export function useElectionCandidatures(id) {
  return useQuery({
    queryKey: ['election', id, 'candidatures'],
    queryFn: () => electionApi.candidatures(id).then((res) => res.data.data),
    enabled: !!id,
  })
}

export function useSubmitCandidature() {
  const queryClient = useQueryClient()
  return useMutation({
    mutationFn: ({ electionId, data }) => electionApi.submitCandidature(electionId, data),
    onSuccess: (_, variables) => {
      queryClient.invalidateQueries({ queryKey: ['election', variables.electionId, 'candidatures'] })
    },
  })
}

export function useElectionResultats(id) {
  return useQuery({
    queryKey: ['election', id, 'resultats'],
    queryFn: () => electionApi.resultats(id).then((res) => res.data.data),
    enabled: !!id,
  })
}
