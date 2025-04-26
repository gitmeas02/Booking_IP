<template>
    <div>
        <SignUpStep1 v-if="step === 1" v-model:email="form.email" @next-step="nextStep" />
        <SignUpStep2 v-else-if="step === 2" v-model:firstName="form.firstName" v-model:lastName="form.lastName"
            v-model:phone="form.phone" @next-step="nextStep" />
        <SignUpStep3 v-else-if="step === 3" v-model:password="form.password"
            v-model:confirmPassword="form.confirmPassword" @submit-form="submitForm" />
    </div>
</template>

<script>
import SignUpStep1 from '@/components/Owner/SignUpStep1.vue';
import SignUpStep2 from '@/components/Owner/SignUpStep2.vue';
import SignUpStep3 from '@/components/Owner/SignUpStep3.vue';

export default {
    name: 'SignUpPage',
    components: {
        SignUpStep1,
        SignUpStep2,
        SignUpStep3,
    },
    data() {
        return {
            step: 1,
            form: {
                email: '',
                firstName: '',
                lastName: '',
                phone: '',
                password: '',
                confirmPassword: '',
            },
        };
    },
    methods: {
        nextStep() {
            if (this.step < 3) {
                this.step++;
            }
        },
        submitForm() {
            if (this.form.password !== this.form.confirmPassword) {
                alert("Passwords do not match!");
                return;
            }
            // Here you would normally send 'this.form' to your API
            console.log("Submitting form:", this.form);
            // Example: After successful signup, redirect to login or dashboard
            // this.$router.push('/login');
        },
    },
};
</script>