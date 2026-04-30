import api from './axios'

export const authApi = {
  sendOtp: (phone, canal = 'whatsapp') => api.post('/auth/send-otp', { phone, canal }),
  verifyOtp: (phone, code) => api.post('/auth/verify-otp', { phone, code }),
  refresh: () => api.post('/auth/refresh'),
  logout: () => api.post('/auth/logout'),
}
