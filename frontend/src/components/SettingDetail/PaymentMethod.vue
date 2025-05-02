<template>
    <div class="bg-white p-0 rounded-lg font-sans">
        <h2 class="text-xl font-semibold mb-1">Payment methods</h2>
        <p class="text-gray-500 text-sm mb-4">
            Securely add or remove payment methods to make it easier when you book.
        </p>

        <div class="border-t border-b py-4 flex justify-between items-center">
            <div class="flex flex-col">
                <span class="font-semibold text-lg">Payment card</span>
                <span class="text-gray-600 text-sm">Pay with new card</span>
            </div>
            <button @click="addCard" class="text-blue-600 font-semibold text-sm hover:underline">
                Add card
            </button>
        </div>
    </div>
</template>

<script setup>
function addCard() {
    alert('Add card clicked');
}
</script>