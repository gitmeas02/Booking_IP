import { defineStore } from 'pinia';

export const useSettingInformationStore = defineStore('settingInformation', {
  state: () => ({
    personalInfo: {
      1: {
        name: "John Doe",
        displayName: "Johnny",
        email: "john@example.com",
        phone: "+1234567890",
        dob: "1990-01-01",
        nationality: "USA",
        gender: "Male",
        address: "123 Main St, City",
        passport: "123456789"
      }
    }
  }),
  actions: {
    fetchPersonalInfo() {
      // Normally fetch from API
    },
    getPersonalInfoById(id) {
      return this.personalInfo[id];
    },
    updatePersonalInfo(id, data) {
      Object.assign(this.personalInfo[id], data);
    }
  }
});
