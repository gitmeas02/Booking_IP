<template>
    <header class="flex justify-between items-center p-4 shadow bg-white">
        <img src="../../assets/logo.png" alt="Logo" class="h-10" />
        <nav class="flex items-center gap-6">
            <CircleDollarSign/>
            <CircleHelp/>
            <ShoppingCart/>
            <button class="bg-[#4B2B2B] text-white px-4 py-2 rounded">Your Property</button>
            <User/>
        </nav>
    </header>
</template>

<script setup>
import { User } from 'lucide-vue-next';
import { CircleDollarSign } from 'lucide-vue-next';
import { ShoppingCart } from 'lucide-vue-next';
import { CircleHelp } from 'lucide-vue-next';
</script>

<style scoped>
</style>