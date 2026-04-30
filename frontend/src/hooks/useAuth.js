import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query'
import { authApi } from '../api/auth.api'
import { alumniApi } from '../api/alumni.api'
import { useAuthStore } from '../store/authStore'

export function useAuth() {
  const queryClient = useQueryClient()

  const loginMutation = useMutation({
    mutationFn: ({ phone, code }) => authApi.verifyOtp(phone, code),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['me'] })
    },
  })

  return {
    login: loginMutation.mutateAsync,
    isLoggingIn: loginMutation.isPending,
    loginError: loginMutation.error,
  }
}

export function useMe() {
  return useQuery({
    queryKey: ['me'],
    queryFn: () => alumniApi.me().then((res) => res.data.data),
    enabled: useAuthStore.getState().isAuthenticated,
  })
}
