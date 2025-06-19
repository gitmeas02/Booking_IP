<template>
    <div class="flex flex-col pl-80 gap-2 mt-3">
        <h1 class="text-xl font-semibold">Room Status</h1>

        <div class="flex gap-25">
            <div v-for="(section, idx) in roomStatusSections" :key="idx" class="w-60">
                <div v-for="(item, i) in section" :key="i" class="flex justify-between">
                    {{ item.label }} <span>{{ item.value }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
const roomStatusSections = [
    [
        { label: 'Occupied Rooms', value: 104 },
        { label: 'Clean', value: 90 },
        { label: 'Dirty', value: 4 },
        { label: 'Inspected', value: 60 }
    ],
    [
        { label: 'Occupied Rooms', value: 104 },
        { label: 'Clean', value: 90 },
        { label: 'Dirty', value: 4 },
        { label: 'Inspected', value: 60 }
    ]
];
</script>