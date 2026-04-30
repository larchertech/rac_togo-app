import api from './axios'

export const cotisationApi = {
  statut: () => api.get('/cotisations/statut'),
  initier: (operateur, annee) => api.post('/cotisations/initier', { operateur, annee }),
  rapport: (annee) => api.get('/cotisations/rapport', { params: { annee } }),
  relances: () => api.post('/cotisations/relances'),
}
