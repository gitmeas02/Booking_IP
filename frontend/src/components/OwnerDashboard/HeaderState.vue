<template>
    <div class="flex flex-col px-8 pt-3">
        <div class="flex text-2xl font-bold pl-1">
            Analysis Overview
        </div>
        <div class="flex justify-end py-1">
            <select v-model="selectedDays" class="flex border pl-4 py-2 rounded-lg">
                <option value="1">1 Days</option>
                <option value="7">7 Days</option>
                <option value="30">30 Days</option>
                <option value="60">60 Days</option>
                <option value="90">90 Days</option>
            </select>
        </div>
    </div>
</template>

<script setup>
import { ChevronDown } from 'lucide-vue-next';
</script>