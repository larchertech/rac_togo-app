import api from './axios'

export const voteApi = {
  cast: (electionId, votes) => api.post(`/elections/${electionId}/vote`, { votes }),
  listeElectorale: (electionId) => api.get(`/elections/${electionId}/electeurs`),
}
