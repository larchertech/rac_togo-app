import api from './axios'

export const alumniApi = {
  list: (params) => api.get('/alumni', { params }),
  me: () => api.get('/alumni/moi'),
  show: (id) => api.get(`/alumni/${id}`),
  create: (data) => api.post('/alumni', data),
  update: (id, data) => api.put(`/alumni/${id}`, data),
  uploadDocument: (id, formData) => api.post(`/alumni/${id}/documents`, formData, {
    headers: { 'Content-Type': 'multipart/form-data' }
  }),
  changeStatus: (id, statut) => api.put(`/alumni/${id}/statut`, { statut }),
  generateCard: (id) => api.get(`/alumni/${id}/carte`),
}
