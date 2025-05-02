<template>
    <div class="container">
        <div class="image-section">
            <h2>Welcome Back!</h2>
            <p>To keep connected with us please<br />login with your personal info</p>
            <button class="ghost-btn" @click="$emit('switch', 'signIn')">Sign In</button>
        </div>

        <div class="form-section">
            <img src="../../assets/images/pteaskhmer.png" alt="Logo" class="logo" />
            <h2>Create Account</h2>

            <div class="social-buttons">
                <button class="social-btn">
                    <Icon icon="mdi:facebook" width="24" height="24" />
                </button>
                <button class="social-btn">
                    <Icon icon="mdi:google" width="24" height="24" />
                </button>
                <button class="social-btn">
                    <Icon icon="mdi:linkedin" width="24" height="24" />
                </button>
            </div>

            <p class="or-text">or use your email for registration</p>

            <form @submit.prevent="handleSignUp" class="form">
                <div class="input-box">
                    <span class="icon">
                        <Icon icon="mdi:person" width="24" height="24" />
                    </span>
                    <input type="text" placeholder="Name" v-model="name" required />
                </div>

                <div class="input-box">
                    <span class="icon">
                        <Icon icon="mdi:email" width="24" height="24" />
                    </span>
                    <input type="email" placeholder="Email" v-model="email" required />
                </div>

                <div class="input-box">
                    <span class="icon">
                        <Icon icon="mdi:lock" width="24" height="24" />
                    </span>
                    <input type="password" placeholder="Password" v-model="password" required />
                </div>

                <button type="submit" class="submit-btn">Sign Up</button>
            </form>
        </div>
    </div>
</template>

<script>
import { Icon } from '@iconify/vue'

export default {
    name: 'SignUp',
    components: {
        Icon,
    },
    data() {
        return {
            name: '',
            email: '',
            password: ''
        }
    },
    methods: {
        handleSignUp() {
            // Add registration logic here
            console.log('Name:', this.name)
            console.log('Email:', this.email)
            console.log('Password:', this.password)
        }
    }
}
</script>

<style scoped>
.container {
    display: flex;
    height: 100vh;
    font-family: 'Arial', sans-serif;
    flex-wrap: wrap;
    /* Allow wrapping of content on smaller screens */
}

.image-section,
.form-section {
    width: 50%;
    padding: 4rem 3rem;
    box-sizing: border-box;
}

.image-section {
    background: url('../../assets/images/beach.jpg') no-repeat center/cover;
    color: white;
    text-align: center;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.form-section {
    background: #f6f6f6;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.form {
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
}

h2 {
    margin-bottom: 1rem;
    color: #2d2d2d;
}

.social-buttons {
    display: flex;
    gap: 1rem;
    margin: 1rem 0;
}

.social-btn {
    background: #eee;
    border: none;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    font-size: 16px;
    cursor: pointer;
}

.or-text {
    margin-bottom: 1rem;
    color: #555;
}

.input-box {
    display: flex;
    align-items: center;
    background: white;
    border: 1px solid #ccc;
    padding: 0.75rem;
    border-radius: 5px;
    margin-bottom: 1rem;
    width: 100%;
    max-width: 300px;
}

.input-box input {
    border: none;
    outline: none;
    margin-left: 10px;
    width: 100%;
}

.submit-btn {
    background: #2d2323;
    color: white;
    border: none;
    padding: 0.75rem 2rem;
    border-radius: 25px;
    font-size: 16px;
    cursor: pointer;
    max-width: 300px;
    width: 100%;
}

.ghost-btn {
    background: transparent;
    border: 2px solid white;
    color: white;
    padding: 0.75rem 2rem;
    border-radius: 25px;
    cursor: pointer;
    margin-top: 1rem;
}

.logo {
    width: 80px;
    margin-bottom: 1rem;
}

.image-section h2 {
    font-size: 2rem;
    font-weight: bold;
}

.image-section p {
    margin: 1rem 0;
}

@media (max-width: 768px) {

    .image-section,
    .form-section {
        width: 100%;
        /* Stack the sections on small screens */
        padding: 2rem 1.5rem;
    }

    .image-section h2 {
        font-size: 1.5rem;
    }

    .image-section p {
        font-size: 1rem;
    }

    .logo {
        width: 60px;
    }

    .social-btn {
        width: 35px;
        height: 35px;
    }

    .input-box,
    .submit-btn {
        max-width: 100%;
    }

    .submit-btn {
        padding: 0.75rem;
    }

    .ghost-btn {
        padding: 0.75rem;
    }
}

@media (max-width: 480px) {
    .image-section h2 {
        font-size: 1.25rem;
    }

    .image-section p {
        font-size: 0.9rem;
    }

    .input-box input {
        font-size: 14px;
    }

    .submit-btn {
        font-size: 14px;
    }

    .ghost-btn {
        font-size: 14px;
    }
}
</style>