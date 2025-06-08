import { defineStore } from 'pinia'
import axios from 'axios'

export const useSettingInformationStore = defineStore('settingInformation', {
  state: () => ({
    personalInfo: {},
    currentUserId: null
  }),
  actions: {
    async fetchPersonalInfo() {
      try {
        const token = localStorage.getItem('token')
        const response = await axios.get('/api/me', {
          headers: {
            Authorization: `Bearer ${token}`
          }
        })

        const user = response.data.user
        this.currentUserId = user.id
        this.personalInfo[user.id] = {
          name: user.name,
          displayName: user.display_name || '',
          email: user.email,
          phone: user.phone || '',
          dob: user.dob || '',
          nationality: user.nationality || '',
          gender: user.gender || '',
          address: user.address || '',
          passport: user.passport || ''
        }
      } catch (error) {
        console.error('Failed to fetch personal info:', error)
      }
    },
    getPersonalInfoById(id) {
      return this.personalInfo[id]
    },
    async updatePersonalInfo(id, data) {
      try {
        const token = localStorage.getItem('token');
        const response = await axios.put('/api/me', data, {
          headers: {
            Authorization: `Bearer ${token}`
          }
        });

        // Update store with new data from backend
        const updatedUser = response.data.user;
        this.personalInfo[id] = {
          name: updatedUser.name,
          displayName: updatedUser.display_name || '',
          email: updatedUser.email,
          phone: updatedUser.phone || '',
          dob: updatedUser.dob || '',
          nationality: updatedUser.nationality || '',
          gender: updatedUser.gender || '',
          address: updatedUser.address || '',
          passport: updatedUser.passport || ''
        };
      } catch (error) {
        console.error('Failed to update user info:', error.response?.data || error.message);
        throw error;
      }
    }

  }
})
