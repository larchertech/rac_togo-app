import { useMutation, useQuery } from '@tanstack/react-query'
import { voteApi } from '../api/vote.api'

export function useCastVote() {
  return useMutation({
    mutationFn: ({ electionId, votes }) => voteApi.cast(electionId, votes),
  })
}

export function useListeElectorale(electionId) {
  return useQuery({
    queryKey: ['liste-electorale', electionId],
    queryFn: () => voteApi.listeElectorale(electionId).then((res) => res.data.data),
    enabled: !!electionId,
  })
}
