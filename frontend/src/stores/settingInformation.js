import { defineStore } from "pinia";
import axios from "axios";

export const useSettingInformationStore = defineStore("settingInformation", {
  state: () => ({
    personalInfo: []
  }),
  actions: {
    async fetchPersonalInfo() {
      const res = await axios.get("http://localhost:5000/personalInfo");
      this.personalInfo = res.data;
    },
    getPersonalInfoById(id) {
      // Use the already-fetched data from state for synchronous access
      return this.personalInfo.find(info => info.id === id);
    },
    async updatePersonalInfo(id, newData) {
      await axios.patch(`http://localhost:5000/personalInfo/${id}`, newData);
      await this.fetchPersonalInfo();
    }
  }
});