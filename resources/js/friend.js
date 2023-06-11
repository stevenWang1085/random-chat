import './axios'
import './websocket'
import {createApp} from 'vue'
import friend from './friend.vue'

const app = createApp(friend);
app.mount("#friend")
