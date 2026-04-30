import api from './axios'

export const electionApi = {
  list: (params) => api.get('/elections', { params }),
  show: (id) => api.get(`/elections/${id}`),
  create: (data) => api.post('/elections', data),
  changeStatus: (id, statut) => api.put(`/elections/${id}/statut`, { statut }),
  candidatures: (id) => api.get(`/elections/${id}/candidatures`),
  submitCandidature: (id, data) => api.post(`/elections/${id}/candidatures`, data),
  electeurs: (id) => api.get(`/elections/${id}/electeurs`),
  participation: (id) => api.get(`/elections/${id}/participation`),
  proclamer: (id) => api.post(`/elections/${id}/proclamer`),
  resultats: (id) => api.get(`/elections/${id}/resultats`),
  pv: (id) => api.get(`/elections/${id}/pv`),
}
