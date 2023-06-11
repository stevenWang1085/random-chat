import './axios'
import './websocket'
import {createApp} from 'vue'
import random from './random.vue'

const app = createApp(random);
app.mount("#random")

var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
})
