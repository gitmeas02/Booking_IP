<template>
    <div class="container">
        <div class="form-section">
            <img src="../../assets/images/pteaskhmer.png" alt="Logo" class="logo" />
            <h2>Sign In</h2>

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

            <p class="or-text">or use your email account</p>

            <form @submit.prevent="handleSignIn" class="form">
                <div class="input-box">
                    <span class="icon"><Icon icon="mdi:email" width="24" height="24" /></span>
                    <input type="email" placeholder="Email" v-model="email" required />
                </div>

                <div class="input-box">
                    <span class="icon"><Icon icon="mdi:lock" width="24" height="24" /></span>
                    <input type="password" placeholder="Password" v-model="password" required />
                </div>
                <div>
                    <p class="link">
                        <a href="#" @click.prevent="$emit('switch', 'forgotPassword')">Forgot your password?</a>
                    </p>

                </div>


                <button type="submit" class="submit-btn">Sign In</button>
            </form>
        </div>

        <div class="image-section">
            <h2>Hello!</h2>
            <p>To keep connected with us please<br />login with your personal info</p>
            <button class="ghost-btn" @click="$emit('switch', 'signUp')">Sign Up</button>
        </div>
    </div>
</template>

<script>
import { Icon } from '@iconify/vue'

export default {
    name: 'SignIn',
    components: {
        Icon, 
    },
    data() {
        return {
            email: '',
            password: ''
        }
    },
    methods: {
        handleSignIn() {
            // Add login logic here
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
}

.form-section {
    width: 50%;
    background: #f6f6f6;
    padding: 4rem 3rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.form{
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.image-section {
    width: 50%;
    background: url('../../assets/images/angkor.jpg') no-repeat center/cover;
    color: white;
    text-align: center;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 3rem;
}

.image-section h2 {
    font-size: 2rem;
    font-weight: bold;
}

.image-section p {
    margin: 1rem 0;
}

.logo {
    width: 80px;
    margin-bottom: 1rem;
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

.link {
    align-self: flex-start;
    max-width: 300px;
    margin-bottom: 1rem;
}

.link a {
    font-size: 14px;
    color: #666;
    text-decoration: none;
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

@media (max-width: 768px) {
  .container {
    flex-direction: column;
    height: auto;
  }

  .form-section,
  .image-section {
    width: 100%;
    padding: 2rem 1.5rem;
  }

  .image-section {
    order: -1; /* show image section on top for mobile */
    text-align: center;
  }

  .image-section h2 {
    font-size: 1.5rem;
  }

  .form-section h2 {
    font-size: 1.5rem;
  }

  .input-box {
    max-width: 100%;
  }

  .link,
  .submit-btn {
    max-width: 100%;
  }

  .social-buttons {
    justify-content: center;
  }
}

@media (max-width: 480px) {
  .logo {
    width: 60px;
  }

  .ghost-btn,
  .submit-btn {
    font-size: 14px;
    padding: 0.5rem 1.5rem;
  }

  .input-box {
    padding: 0.5rem;
  }
}

</style>