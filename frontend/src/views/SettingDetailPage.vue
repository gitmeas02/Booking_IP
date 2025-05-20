<template>
  <div class="flex flex-row gap-10 p-10 bg-gray-100 min-h-screen font-sans lg:flex-col lg:p-5 lg:gap-5">
    <SideNav v-model:selectedIndex="selectedIndex" />
    <div class="flex-1 bg-white rounded-xl p-8 shadow-md lg:p-6 sm:p-4">
      <component :is="CurrentSection" />
    </div>
  </div>
</template>

<script setup>
import { ref, shallowRef, watch } from 'vue'

import SideNav from '../components/SettingDetail/SideNav.vue'
import PersonalDetails from '../components/SettingDetail/PersonalDetails.vue'
import SecuritySettings from '../components/SettingDetail/SecuritySetting.vue'
import OtherTravelers from '@/components/SettingDetail/OtherTravelers.vue'
import CustomizationPreferences from '@/components/SettingDetail/CustomizationPreferences.vue'
import PaymentMethod from '@/components/SettingDetail/PaymentMethod.vue'
import PrivacyAndPolicy from '@/components/SettingDetail/PrivacyAndPolicy.vue'

const selectedIndex = ref(0)

const sections = shallowRef([
  { component: PersonalDetails },
  { component: SecuritySettings },
  { component: OtherTravelers },
  { component: CustomizationPreferences },
  { component: PaymentMethod },
  { component: PrivacyAndPolicy }
])

const CurrentSection = shallowRef(sections.value[0].component)

watch(selectedIndex, (newIndex) => {
  CurrentSection.value = sections.value[newIndex]?.component || sections.value[0].component
})
</script>
