<template>
  <div>
    <div class="flex justify-between items-center mb-8 gap-4 flex-wrap">
      <div>
        <h2 class="text-2xl font-semibold mb-1">Personal details</h2>
        <p class="text-gray-600 text-sm">Update your info and find out how it’s used</p>
      </div>
      <img class="w-12 h-12 rounded-full object-cover" src="../../assets/images/4x6.JPG" alt="Profile" />
    </div>

    <DetailRow label="Name" :value="fields.name" :editing="editingField === 'name'" :editValue="editValues.name"
      @edit="startEdit('name')" @update="val => editValues.name = val" @save="saveEdit('name')" @cancel="cancelEdit" />

    <DetailRow label="Display Name" :value="fields.displayName" :editing="editingField === 'displayName'"
      :editValue="editValues.displayName" @edit="startEdit('displayName')" @update="val => editValues.displayName = val"
      @save="saveEdit('displayName')" @cancel="cancelEdit" />

    <DetailRow label="Email" :value="fields.email" :editing="editingField === 'email'" :editValue="editValues.email"
      @edit="startEdit('email')" @update="val => editValues.email = val" @save="saveEdit('email')"
      @cancel="cancelEdit" />

    <DetailRow label="Phone" :value="fields.phone" :editing="editingField === 'phone'" :editValue="editValues.phone"
      @edit="startEdit('phone')" @update="val => editValues.phone = val" @save="saveEdit('phone')"
      @cancel="cancelEdit" />

    <DetailRow label="Date of birth" :value="fields.dob" :editing="editingField === 'dob'" :editValue="editValues.dob"
      @edit="startEdit('dob')" @update="val => editValues.dob = val" @save="saveEdit('dob')" @cancel="cancelEdit" />

    <DetailRow label="Nationality" :value="fields.nationality" :editing="editingField === 'nationality'"
      :editValue="editValues.nationality" @edit="startEdit('nationality')" @update="val => editValues.nationality = val"
      @save="saveEdit('nationality')" @cancel="cancelEdit" />

    <DetailRow label="Gender" :value="fields.gender" :editing="editingField === 'gender'" :editValue="editValues.gender"
      @edit="startEdit('gender')" @update="val => editValues.gender = val" @save="saveEdit('gender')"
      @cancel="cancelEdit" />

    <DetailRow label="Address" :value="fields.address" :editing="editingField === 'address'"
      :editValue="editValues.address" @edit="startEdit('address')" @update="val => editValues.address = val"
      @save="saveEdit('address')" @cancel="cancelEdit" />

    <DetailRow label="Passport" :value="fields.passport" :editing="editingField === 'passport'"
      :editValue="editValues.passport" @edit="startEdit('passport')" @update="val => editValues.passport = val"
      @save="saveEdit('passport')" @cancel="cancelEdit" />
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import DetailRow from './DetailRow.vue'
import { useSettingInformationStore } from '@/stores/settingInformation'

const settingStore = useSettingInformationStore()

const fields = reactive({
  name: '',
  displayName: '',
  email: '',
  phone: '',
  dob: '',
  nationality: '',
  gender: '',
  address: '',
  passport: '',
})

const editValues = reactive({ ...fields })
const editingField = ref('')
const isSaving = ref(false)

onMounted(async () => {
  await settingStore.fetchPersonalInfo()
  const user = settingStore.getPersonalInfoById(settingStore.currentUserId)
  if (user) {
    Object.assign(fields, user)
    Object.assign(editValues, user)
  } else {
    console.warn('User not found')
  }
})

function startEdit(field) {
  editingField.value = field
  editValues[field] = fields[field]
}

function cancelEdit() {
  if (editingField.value) {
    const field = editingField.value
    editValues[field] = fields[field]
  }
  editingField.value = ''
}

async function saveEdit(field) {
  editingField.value = '';

  const payloadKey = field === 'displayName' ? 'display_name' : field;

  try {
    await settingStore.updatePersonalInfo(settingStore.currentUserId, {
      [payloadKey]: editValues[field]
    });

    const updated = settingStore.getPersonalInfoById(settingStore.currentUserId);
    Object.assign(fields, updated); // ✅ re-sync from store
  } catch (err) {
    console.error("Failed to save changes:", err);
  }
}

</script>
