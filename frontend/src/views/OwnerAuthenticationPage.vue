<template>
    <div class="max-w-md mx-auto">
        <div v-if="isSignIn">
            <SignInPage @goToSignUp="switchToSignUp" />
        </div>
        <div v-else>
            <SignUpPage />
        </div>
    </div>
</template>

<script>
import SignInPage from './Owner/SignInPage.vue';
import SignUpPage from './Owner/SignUpPage.vue';

export default {
    components: {
        SignInPage,
        SignUpPage
    },
    data() {
        return {
            isSignIn: true, // Start at SignIn page by default
            step: 1,        // Step in SignUp
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
        switchToSignUp() {
            this.isSignIn = false;
        },
        nextStep() {
            this.step++;
        },
        submitForm() {
            if (this.form.password !== this.form.confirmPassword) {
                alert("Passwords do not match!");
                return;
            }
            console.log("Form Submitted:", this.form);
            // You can now send this data to your backend
        },
    },
};
</script>