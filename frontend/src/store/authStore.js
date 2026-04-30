import { create } from 'zustand'
import { persist } from 'zustand/middleware'
import { authApi } from '../api/auth.api'
import { alumniApi } from '../api/alumni.api'

export const useAuthStore = create(
  persist(
    (set, get) => ({
      user: null,
      token: null,
      isAuthenticated: false,
      isLoading: false,
      canal: 'whatsapp',

      sendOtp: async (phone, canal = 'whatsapp') => {
        set({ isLoading: true, canal })
        try {
          const response = await authApi.sendOtp(phone, canal)
          set({ isLoading: false })
          return { success: true, data: response.data.data }
        } catch (error) {
          set({ isLoading: false })
          return { success: false, message: error.response?.data?.message || 'Erreur lors de l\'envoi du code' }
        }
      },

      verifyOtp: async (phone, code) => {
        set({ isLoading: true })
        try {
          const response = await authApi.verifyOtp(phone, code)
          const { token, user } = response.data.data

          sessionStorage.setItem('rac_token', token)
          set({ token, user, isAuthenticated: true, isLoading: false })

          // Charger le profil alumni
          const profileRes = await alumniApi.me()
          if (profileRes.data.success) {
            set({ user: { ...user, alumni: profileRes.data.data } })
          }

          return { success: true }
        } catch (error) {
          set({ isLoading: false })
          return {
            success: false,
            message: error.response?.data?.message || 'Code incorrect',
            tentatives: error.response?.data?.tentatives,
          }
        }
      },

      logout: async () => {
        try {
          await authApi.logout()
        } catch (e) {
          // ignore
        }
        sessionStorage.removeItem('rac_token')
        localStorage.removeItem('rac_token')
        localStorage.removeItem('rac_refresh_token')
        set({ user: null, token: null, isAuthenticated: false })
        window.location.href = '/login'
      },

      refreshToken: async () => {
        try {
          const response = await authApi.refresh()
          const { token } = response.data.data
          sessionStorage.setItem('rac_token', token)
          set({ token })
          return { success: true }
        } catch (error) {
          get().logout()
          return { success: false }
        }
      },

      fetchMe: async () => {
        try {
          const [userRes, profileRes] = await Promise.all([
            authApi.refresh(),
            alumniApi.me(),
          ])
          const user = userRes.data.data?.user || get().user
          const alumni = profileRes.data.data
          set({ user: { ...user, alumni } })
        } catch (error) {
          console.error('fetchMe error', error)
        }
      },

      setCanal: (canal) => set({ canal }),
    }),
    {
      name: 'rac-auth',
      partialize: (state) => ({ user: state.user, isAuthenticated: state.isAuthenticated }),
    }
  )
)
