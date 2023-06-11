import './axios'
import './bootstrap'
import {createApp} from 'vue'
import Part from './register.vue'

const app = createApp(Part);
app.mount("#register")
