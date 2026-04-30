import api from './axios';

export const fetchNotifications = () => api.get('/notifications').then((res) => res.data);

export const markAsRead = (id) => api.patch(`/notifications/${id}/read`).then((res) => res.data);
