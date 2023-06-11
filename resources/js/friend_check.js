import './axios'
import {createApp} from 'vue'
import friend_chat from './friend_check.vue'

const app = createApp(friend_chat);
app.mount("#friend_check")
