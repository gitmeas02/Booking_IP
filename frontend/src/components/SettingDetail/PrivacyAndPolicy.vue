<template>
    <div class="bg-white p-4 rounded-lg">
        <h2 class="text-2xl font-semibold mb-1">Privacy and Policy</h2>
        <p class="text-gray-600 text-sm mb-4">
            Exercise your privacy rights, control your data, or export your information.
        </p>

        <div class="border-t border-b py-4 flex justify-between items-start">
            <div class="flex flex-col">
                <span class="font-semibold text-lg mb-1">Privacy setting</span>
                <div class="flex flex-col">
                    <span class="text-lg font-semibold text-blue-600 mb-1">khunmeas@gmail.com</span>
                    <p class="text-sm text-gray-600 max-w-lg">
                        Select “Manage” to change your privacy setting and exercise your rights using our request form.
                    </p>
                </div>
            </div>
            <button class="text-blue-600 font-semibold text-sm hover:underline focus:outline-none"
                @click="managePrivacy">
                Manage
            </button>
        </div>
    </div>
</template>

<script setup>
function managePrivacy() {
    alert('Manage privacy clicked');
}
</script>

<style scoped>
/* Tailwind CSS utility classes already handle the styling, no need for additional custom CSS */
</style>