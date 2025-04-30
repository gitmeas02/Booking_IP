<template>
    <div class="step-container">
        <h2>Create Partner Account</h2>
        <p>Create an account to list and manage your property.</p>
        <div class="form-group">
            <label>Email address</label>
            <input type="email" v-model="localEmail" placeholder="example@blabla.com" />
        </div>
        <button @click="goNext">Continue</button>

        <hr class="divider" />
        <div class="footer">
            <p>
                Already have an account?
            </p>
            <router-link to="/signin">Sign In</router-link>
        </div>
        <div class="copyright">
            <small>All rights reserved.</small>
            <small>Copyright (2025-2025) - Pteas Khmer</small>
        </div>
    </div>
</template>

<script>
export default {
    name: "SignUpStep1",
    props: {
        email: String,
    },
    emits: ['update:email', 'next-step'],
    data() {
        return {
            localEmail: this.email,
        };
    },
    watch: {
        localEmail(newVal) {
            this.$emit('update:email', newVal);
        },
        email(newVal) {
            this.localEmail = newVal;
        }
    },
    methods: {
        goNext() {
            this.$emit('next-step');
        },
    },
};
</script>

<style scoped>
.step-container {
    max-width: 400px;
    margin: 4rem auto;
    /* center on page with some top/bottom spacing */
    padding: 2.5rem 2rem;
    /* roomy padding */
    background: #fff;
    /* white card */
    border-radius: 12px;
    /* smooth corners */
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
    text-align: center;
    font-family: 'Arial', sans-serif;
}

.step-container h2 {
    font-size: 1.8rem;
    margin-bottom: 0.5rem;
    color: #2e2e2e;
}

.step-container p {
    color: #666;
    margin-bottom: 1.5rem;
    line-height: 1.4;
}

.form-group {
    margin-bottom: 1rem;
    text-align: left;
}

.form-group label {
    display: block;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

/* separator line */
.divider {
    border: none;
    border-top: 1px solid #e0e0e0;
    margin: 1.5rem 0;
}

/* full-width input styling */
input[type="email"] {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid #ccc;
    border-radius: 8px;
    box-sizing: border-box;
    font-size: 1rem;
    margin-bottom: 1.5rem;
}

button {
    display: block;
    width: 100%;
    padding: 0.85rem;
    background-color: #332c2b;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
}

/* primary “Continue” button */
.btn-primary {
    display: block;
    width: 100%;
    padding: 0.85rem;
    background-color: #332c2b;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
}

/* secondary “Sign In” link-button */
.btn-secondary {
    display: inline-block;
    width: 100%;
    padding: 0.75rem;
    margin-top: 0.5rem;
    color: #332c2b;
    background: none;
    border: 1px solid #332c2b;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 500;
    text-decoration: none;
    text-align: center;
}

/* smaller text under buttons */
.footer {
    margin-top: 1rem;
    text-align: center;
}

.footer p {
    margin-bottom: 1.5rem;
    /* Add space BELOW the "Don't have an account?" */

}

.copyright {
    margin-top: 1.5rem;
    font-size: 0.8rem;
    color: #9a9a9a;
}
</style>