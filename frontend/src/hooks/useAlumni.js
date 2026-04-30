import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query'
import { alumniApi } from '../api/alumni.api'

export function useAlumni(params = {}) {
  return useQuery({
    queryKey: ['alumni', params],
    queryFn: () => alumniApi.list(params).then((res) => res.data),
  })
}

export function useAlumniProfile(id) {
  return useQuery({
    queryKey: ['alumni', id],
    queryFn: () => alumniApi.show(id).then((res) => res.data.data),
    enabled: !!id,
  })
}

export function useUpdateAlumni() {
  const queryClient = useQueryClient()
  return useMutation({
    mutationFn: ({ id, data }) => alumniApi.update(id, data),
    onSuccess: (_, variables) => {
      queryClient.invalidateQueries({ queryKey: ['alumni', variables.id] })
      queryClient.invalidateQueries({ queryKey: ['me'] })
    },
  })
}
