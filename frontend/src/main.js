import './assets/main.css'

import { createApp } from 'vue'
import PrimeVue from 'primevue/config';
import Aura from '@primevue/themes/aura';
import App from './App.vue'
import router from './router'

const app = createApp(App);
app.use(PrimeVue,{
    theme: {
        // preset: Aura
    }
});

app.use(router)
app.mount('#app')
