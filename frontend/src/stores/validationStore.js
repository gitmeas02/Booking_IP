import axios from "axios";
import { CreditCard } from "lucide-vue-next";
import { defineStore } from "pinia";
import { createRouter, createWebHistory } from "vue-router";

// Pinia Store: validationStore.js
export const useValidationStore = defineStore("validation", {
  state: () => ({
    property: {
      type: null,// page1
      location: { 
        street: "",
        apartmentFloor: "",
        country: "",
        city: "",
        postcode: "",
        lat: null,
        lng: null,
      },
      name: "",
      starRating: null,
      amenities: [],
      parking: false,
      breakfast: false,
      houseRules: {
        checkinFrom: "",
        checkinTo: "",
        checkoutFrom: "",
        checkoutTo: "",
        petsAllowed: false,
        childrenAllowed: false,
      },
      images: [],
      paymentOptions: [
        {
          online:true,
        creditCard_at_Property:false
        }
      ],
      owner: {
       fullName: "",
        email: "",
        phoneNumber: "",
        countryRegion: "",
        addressLine1: "",
        addressLine2: "",
        idFirstName: "",
        idMiddleName: "",
        idLastName: "",
      },
    },
    currentStep: 1,
    successMessage: "",
    errorMessage: "",
    isSubmitting: false,
  }),

  actions: {
    setPropertyValue(path, value) {
      const keys = path.split(".");
      let obj = this.property;
      for (let i = 0; i < keys.length - 1; i++) {
        obj = obj[keys[i]];
      }
      obj[keys[keys.length - 1]] = value;
    },
    
    async submit() {
      this.isSubmitting = true;
      this.successMessage = "";
      this.errorMessage = "";
      const apiBaseUrl = import.meta.env.VITE_API_BASE_URL
      console.log('Environment variables:', import.meta.env)
        console.log('API Base URL:', apiBaseUrl)
      try {
        const formData = new FormData();
        this.appendToFormData(formData, this.property);
        const token = localStorage.getItem("token");
      
        const response = await axios.post(`${apiBaseUrl}/owner/apply`, formData, {
          headers: {
            "Content-Type": "multipart/form-data",
            Authorization: `Bearer ${token}`,
          },
        });
        this.successMessage = "Application submitted successfully!";
        console.log("Success:", response.data);
      } catch (error) {
        console.error("Submission error:", error);
        this.errorMessage = error.response?.data?.message || "Submission failed.";
      } finally {
        this.isSubmitting = false;
      }
    },

    appendToFormData(formData, obj, prefix = "") {
      for (const [key, value] of Object.entries(obj)) {
        const fieldKey = prefix ? `${prefix}[${key}]` : key;
        if (typeof value === "object" && value !== null && !Array.isArray(value)) {
          this.appendToFormData(formData, value, fieldKey);
        } else if (Array.isArray(value)) {
          value.forEach((item, index) => {
            if (item instanceof File) {
              formData.append(`${fieldKey}[${index}]`, item);
            } else {
              formData.append(`${fieldKey}[${index}]`, item);
            }
          });
        } else {
          formData.append(fieldKey, value);
        }
      }
    },

    resetForm() {
      this.$reset();
    },
  },
});