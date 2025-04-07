<template>
    <div class="container">
      <SideNav v-model:selectedIndex="selectedIndex" />
      <div class="content">
        <component :is="CurrentSection" />
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, shallowRef, watch } from 'vue'
  
  // Import Side Navigation and content components
  import SideNav from '../components/SettingDetail/SideNav.vue'
  import PersonalDetails from '../components/SettingDetail/PersonalDetails.vue'
  import SecuritySettings from '../components/SettingDetail/SecuritySetting.vue'
  import OtherTravelers from '@/components/SettingDetail/OtherTravelers.vue'
  import CustomizationPreferences from '@/components/SettingDetail/CustomizationPreferences.vue'
  // You can add more imports here for other sections (e.g., OtherTravelers.vue)
  
  const selectedIndex = ref(0)
  
  const sections = shallowRef([
    { component: PersonalDetails },
    { component: SecuritySettings },
    { component: OtherTravelers},
    { component: CustomizationPreferences}
    // Add more components in order: OtherTravelers, CustomizationPreferences, etc.
  ])
  
  const CurrentSection = shallowRef(sections.value[0].component)
  
  watch(selectedIndex, (newIndex) => {
    CurrentSection.value = sections.value[newIndex]?.component || sections.value[0].component
  })
  </script>
  
  <style scoped>
  .container {
    display: flex;
    padding: 40px;
    gap: 40px;
    background: #f4f4f4;
    min-height: 100vh;
    font-family: sans-serif;
  }
  
  .content {
    flex: 1;
    background: #fff;
    border-radius: 12px;
    padding: 32px;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
  }
  </style>
  