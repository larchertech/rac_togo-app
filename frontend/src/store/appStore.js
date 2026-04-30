import { create } from 'zustand'

export const useAppStore = create((set) => ({
  sidebarOpen: false,
  notifications: [],
  currentPhase: 'BLA',

  toggleSidebar: () => set((state) => ({ sidebarOpen: !state.sidebarOpen })),
  closeSidebar: () => set({ sidebarOpen: false }),
  addNotification: (notification) =>
    set((state) => ({ notifications: [notification, ...state.notifications] })),
  clearNotifications: () => set({ notifications: [] }),
  setCurrentPhase: (phase) => set({ currentPhase: phase }),
}))
