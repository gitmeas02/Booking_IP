import 'primeicons/primeicons.css'
import './assets/main.css'

import { createPinia } from 'pinia'
import { createApp } from 'vue'

import App from './App.vue'
import router from './router'
import PrimeVue from 'primevue/config'
import ToastService from 'primevue/toastservice'
import Toast from 'primevue/toast'

// import 'primevue/resources/themes/lara-light-indigo/theme.css'  // Theme
// import 'primevue/resources/primevue.min.css'                    // Core CSS
// import 'primeicons/primeicons.css'                              // Icons

const app = createApp(App)
app.use(PrimeVue)
app.use(ToastService)

app.component('Toast', Toast)
app.use(createPinia())
app.use(router)

app.mount('#app')