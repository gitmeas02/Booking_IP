import axios from "axios";
import { CreditCard } from "lucide-vue-next";
import { defineStore } from "pinia";
import { createRouter, createWebHistory } from "vue-router";

// Pinia Store: validationStore.js
export const useValidationStore = defineStore("validation", {
  state: () => ({
  property: {
    property_type: null,
    property_name: "",
    stars: null,

    location: {
      street: "",
      floor: "",
      country: "",
      city: "",
      zip_code: "",
      lat: null,
      lng: null,
    },

    amenities: [],
    breakfast: false,
    parking: false,

    houseRules: {
      checkin_from: "",
      checkin_to: "",
      checkout_from: "",
      checkout_to: "",
      allow_pet: false,
      childrenAllowed: false
    },

    photos: [],

    // FIX: Change from array to object
    paymentOptions: {
      online: true,
      at_property: false  // This should be a direct object, not an array
    },

    identity: {
      full_name: "",
      email: "",
      phone: "",
      country: "",
      address1: "",
      address2: "",
      first_name_id: "",
      middle_name_id: "",
      last_name_id: ""
    }
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
      console.log("setPropertyValue called with:", path, value);
    },
    
async submit() {
  this.isSubmitting = true;
  this.successMessage = "";
  this.errorMessage = "";
  const apiBaseUrl = import.meta.env.VITE_API_BASE_URL;
  
  try {
    const formData = new FormData();
    this.appendToFormData(formData, this.property);
    
    // DEBUG: Log all FormData entries
    console.log("=== FormData Contents ===");
    for (let [key, value] of formData.entries()) {
      if (value instanceof File) {
        console.log(key, `FILE: ${value.name} (${value.size} bytes)`);
      } else {
        console.log(key, value);
      }
    }
    console.log("========================");
    
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
    
    // Log detailed error information
    if (error.response) {
      console.error("Error Response Data:", error.response.data);
      console.error("Error Status:", error.response.status);
      console.error("Error Headers:", error.response.headers);
    }
    
    this.errorMessage = error.response?.data?.message || "Submission failed.";
  } finally {
    this.isSubmitting = false;
  }
},
appendToFormData(formData, obj, prefix = "") {
  for (const [key, value] of Object.entries(obj)) {
    const fieldKey = prefix ? `${prefix}[${key}]` : key;

    if (Array.isArray(value)) {
      // Handle file arrays (photos) and regular arrays (amenities)
      value.forEach((item, index) => {
        if (item instanceof File) {
          formData.append(`${fieldKey}[${index}]`, item);
        } else {
          formData.append(`${fieldKey}[${index}]`, item);
        }
      });
    } else if (typeof value === "object" && value !== null && !(value instanceof File)) {
      // Handle nested objects - include paymentOptions in the list
      if (["location", "identity", "houseRules", "paymentOptions"].includes(key)) {
        this.appendToFormData(formData, value, fieldKey);
      } else {
        // Flatten other objects
        for (const [innerKey, innerValue] of Object.entries(value)) {
          const nestedKey = `${fieldKey}[${innerKey}]`;
          if (typeof innerValue === 'boolean') {
            formData.append(nestedKey, innerValue ? '1' : '0');
          } else {
            formData.append(nestedKey, innerValue);
          }
        }
      }
    } else {
      // Handle primitive values
      let finalValue = value;
      
      // Convert boolean to string for FormData
      if (typeof value === 'boolean') {
        finalValue = value ? '1' : '0';
      }
      
      // Handle null values
      if (value === null || value === undefined) {
        finalValue = '';
      }
      
      formData.append(fieldKey, finalValue);
    }
  }
}
,

    resetForm() {
      this.$reset();
    },
  },
});