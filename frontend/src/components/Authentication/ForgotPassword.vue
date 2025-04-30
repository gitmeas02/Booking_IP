<template>
    <div class="forgot-password-container">
        <!-- Top Section -->
        <div class="top-bar">
            <img src="../../assets/images/pteaskhmer.png" alt="Logo" class="logo" />
            <div class="register-link">
                <span>Don't have an account?</span>
                <a href="#">Register Now</a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="content">
            <img src="../../assets/images/forgotpassword.png" alt="Illustration" class="illustration" />

            <h2 class="title">Forgot your password?</h2>
            <p class="instruction">Enter your email so that we can send you password reset link</p>

            <form @submit.prevent="handleReset" class="form">
                <label>Email</label>
                <input type="email" placeholder="e.g. username@blabla.com" v-model="email" required />
                <button type="submit" class="submit-btn">Send Email</button>
            </form>

            <button class="back-btn" @click="$emit('switch', 'signIn')">
                <Icon icon="mdi:arrow-back" width="24" height="24" /> Back to Login
            </button>
        </div>
    </div>
</template>

<script>
import { Icon } from '@iconify/vue'

export default {
    name: 'ForgotPassword',
    components: {
        Icon,
    },
    data() {
        return {
            email: '',
        };
    },
    methods: {
        handleReset() {
            console.log('Reset link sent to:', this.email);
        },
    },
};
</script>

<style scoped>
.forgot-password-container {
    min-height: 100vh;
    background-color: #fff;
    font-family: 'Poppins', sans-serif;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 0 1rem;
    position: relative;
}

.top-bar {
    width: 100%;
    display: flex;
    justify-content: space-between;
    padding: 1.5rem 2rem;
    align-items: center;
    position: absolute;
    top: 0;
    left: 0;
}

.logo {
    height: 40px;
}

.register-link {
    font-size: 14px;
}

.register-link a {
    font-weight: bold;
    color: #000;
    margin-left: 6px;
    text-decoration: none;
}

.content {
    margin-top: 120px;
    max-width: 400px;
    text-align: center;
    width: 100%;
}

.illustration {
    width: 200px;
    margin: 0 auto 2rem auto;
}

.title {
    font-size: 24px;
    font-weight: bold;
    color: #2d2d2d;
    margin-bottom: 0.5rem;
}

.instruction {
    color: #666;
    font-size: 14px;
    margin-bottom: 2rem;
}

.form {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.form label {
    display: block;
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 8px;
    color: #333;
    text-align: left;
    width: 100%;
    max-width: 300px;
}

.form input {
    width: 100%;
    max-width: 300px;
    padding: 14px 20px;
    font-size: 14px;
    border: 1px solid #ccc;
    border-radius: 9999px;
    margin-bottom: 1.5rem;
    box-sizing: border-box;
}

.submit-btn {
    width: 100%;
    max-width: 300px;
    padding: 14px;
    background-color: #2c2221;
    color: white;
    border: none;
    font-weight: 600;
    border-radius: 9999px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s ease;
}

.submit-btn:hover {
    background-color: #1e1a19;
}

.back-btn {
    margin-top: 1.5rem;
    background: none;
    border: none;
    color: #000;
    font-size: 14px;
    cursor: pointer;
    text-decoration: underline;

    display: inline-flex;
    align-items: center;
    gap: 6px;
}

@media (max-width: 768px) {
    .top-bar {
        padding: 1rem;
        flex-direction: column;
        align-items: center;
    }

    .logo {
        height: 30px;
    }

    .register-link {
        margin-top: 1rem;
        text-align: center;
    }

    .content {
        margin-top: 90px;
        padding: 0 1rem;
        max-width: 90%;
    }

    .illustration {
        width: 150px;
    }

    .title {
        font-size: 20px;
    }

    .instruction {
        font-size: 12px;
    }

    .form label {
        font-size: 12px;
    }

    .form input {
        padding: 12px;
        font-size: 12px;
    }

    .submit-btn {
        padding: 12px;
        font-size: 14px;
    }

    .back-btn {
        font-size: 12px;
    }
}

@media (max-width: 480px) {
    .top-bar {
        padding: 0.5rem;
    }

    .logo {
        height: 25px;
    }

    .register-link {
        font-size: 12px;
        text-align: center;
    }

    .content {
        margin-top: 60px;
        max-width: 100%;
    }

    .illustration {
        width: 120px;
    }

    .title {
        font-size: 18px;
    }

    .instruction {
        font-size: 12px;
    }

    .form input {
        padding: 10px;
        font-size: 12px;
    }

    .submit-btn {
        padding: 12px;
        font-size: 14px;
    }

    .back-btn {
        font-size: 12px;
    }
}
</style>