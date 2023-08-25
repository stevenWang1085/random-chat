import './axios'
import './websocket'
import {createApp} from 'vue'
import Part from './dashboard.vue'

const app = createApp(Part);
app.mount("#dashboard")
