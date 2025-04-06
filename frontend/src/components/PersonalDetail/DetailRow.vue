<template>
    <div class="detail-row">
        <div class="label">{{ label }}</div>
        <div class="value">
            <div>{{ value }}</div>
            <small v-if="note">{{ note }}</small>
        </div>
        <div class="edit">Edit</div>
    </div>
</template>

<script setup>
defineProps({
    label: String,
    value: String,
    note: String
})
</script>

<style scoped>
.detail-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 16px 0;
    border-bottom: 1px solid #ddd;
}

.label {
    width: 160px;
    font-weight: 500;
}

.value {
    flex: 1;
    color: #333;
}

small {
    color: #888;
    font-size: 12px;
}

.edit {
    color: #0071c2;
    cursor: pointer;
}
</style>