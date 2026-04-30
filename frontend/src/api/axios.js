import axios from 'axios'

const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL + '/api/v1',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
})

// Intercepteur request
api.interceptors.request.use(
  (config) => {
    const token = sessionStorage.getItem('rac_token') || localStorage.getItem('rac_token')
    if (token) {
      config.headers.Authorization = `Bearer ${token}`
    }
    return config
  },
  (error) => Promise.reject(error)
)

// Intercepteur response
api.interceptors.response.use(
  (response) => response,
  async (error) => {
    const originalRequest = error.config

    if (error.response?.status === 401 && !originalRequest._retry) {
      originalRequest._retry = true
      try {
        const refreshToken = localStorage.getItem('rac_refresh_token')
        if (refreshToken) {
          const response = await axios.post(
            import.meta.env.VITE_API_URL + '/api/v1/auth/refresh',
            {},
            { headers: { Authorization: `Bearer ${refreshToken}` } }
          )
          const { token } = response.data.data
          localStorage.setItem('rac_token', token)
          originalRequest.headers.Authorization = `Bearer ${token}`
          return api(originalRequest)
        }
      } catch (refreshError) {
        localStorage.removeItem('rac_token')
        localStorage.removeItem('rac_refresh_token')
        sessionStorage.removeItem('rac_token')
        window.location.href = '/login'
        return Promise.reject(refreshError)
      }
    }

    if (error.response?.status === 422) {
      const errors = error.response.data.errors || {}
      const firstError = Object.values(errors).flat()[0]
      console.error('Validation error:', firstError)
    }

    return Promise.reject(error)
  }
)

export default api
