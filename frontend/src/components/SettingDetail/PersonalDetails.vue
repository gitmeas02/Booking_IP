<template>
    <div>
        <div class="flex justify-between items-center mb-8 gap-4 flex-wrap">
            <div>
                <h2 class="text-2xl font-semibold mb-1">Personal details</h2>
                <p class="text-gray-600 text-sm">Update your info and find out how it’s used</p>
            </div>
            <img class="w-12 h-12 rounded-full object-cover" src="../../assets/images/4x6.JPG" alt="Profile" />
        </div>

        <DetailRow
            label="Name"
            :value="fields.name"
            :editing="editingField === 'name'"
            :editValue="editValues.name"
            @edit="startEdit('name')"
            @update="val => editValues.name = val"
            @save="saveEdit('name')"
        />
        <DetailRow
            label="Display name"
            :value="fields.displayName"
            :editing="editingField === 'displayName'"
            :editValue="editValues.displayName"
            @edit="startEdit('displayName')"
            @update="val => editValues.displayName = val"
            @save="saveEdit('displayName')"
        />
        <DetailRow
            label="Email address"
            :value="fields.email"
            :editing="editingField === 'email'"
            :editValue="editValues.email"
            note="This is the email address you use to sign in. It’s also where we send your booking confirmations."
            @edit="startEdit('email')"
            @update="val => editValues.email = val"
            @save="saveEdit('email')"
        />
        <DetailRow
            label="Phone number"
            :value="fields.phone"
            :editing="editingField === 'phone'"
            :editValue="editValues.phone"
            note="Properties or attractions you book will use this number if they need to contact you."
            @edit="startEdit('phone')"
            @update="val => editValues.phone = val"
            @save="saveEdit('phone')"
        />
        <DetailRow
            label="Date of birth"
            :value="fields.dob"
            :editing="editingField === 'dob'"
            :editValue="editValues.dob"
            @edit="startEdit('dob')"
            @update="val => editValues.dob = val"
            @save="saveEdit('dob')"
        />
        <DetailRow
            label="Nationality"
            :value="fields.nationality"
            :editing="editingField === 'nationality'"
            :editValue="editValues.nationality"
            @edit="startEdit('nationality')"
            @update="val => editValues.nationality = val"
            @save="saveEdit('nationality')"
        />
        <DetailRow
            label="Gender"
            :value="fields.gender"
            :editing="editingField === 'gender'"
            :editValue="editValues.gender"
            @edit="startEdit('gender')"
            @update="val => editValues.gender = val"
            @save="saveEdit('gender')"
        />
        <DetailRow
            label="Address"
            :value="fields.address"
            :editing="editingField === 'address'"
            :editValue="editValues.address"
            @edit="startEdit('address')"
            @update="val => editValues.address = val"
            @save="saveEdit('address')"
        />
        <DetailRow
            label="Passport details"
            :value="fields.passport"
            :editing="editingField === 'passport'"
            :editValue="editValues.passport"
            @edit="startEdit('passport')"
            @update="val => editValues.passport = val"
            @save="saveEdit('passport')"
        />
    </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import DetailRow from './DetailRow.vue';
import { useSettingInformationStore } from "@/stores/settingInformation";
const settingStore = useSettingInformationStore();

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
});

const editValues = reactive({ ...fields });
const editingField = ref('');

// Fetch and set user data on mount
onMounted(async () => {
    await settingStore.fetchPersonalInfo();
    // getPersonalInfoById is synchronous
    const user = settingStore.getPersonalInfoById(1);
    if (user) {
        Object.assign(fields, user);
        Object.assign(editValues, user);
    }
});

function startEdit(field) {
    editingField.value = field;
    editValues[field] = fields[field];
    editingField.value = '';
    // Update the store if needed
    settingStore.updatePersonalInfo(1, { [field]: fields[field] });
}


// function saveEdit(field) {
//     fields[field] = editValues[field];
//     editingField.value = '';
//     // Update the store if needed
//     settingStore.updatePersonalInfo(1, { [field]: fields[field] });
// }
</script>

<style scoped>
/* Tailwind already covers styles, so no additional custom styles needed */
</style>