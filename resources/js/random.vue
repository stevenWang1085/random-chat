<template>
<home></home>
    <div v-if="match_user_alert" :class="match_user_alert_class" role="alert">
        <div>
            {{ match_user_alert_message }}
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <!--    聊天區塊-->
    <section v-if="chat_div" class="gradient-custom ttt">
        <div class="container py-5">
            <div class="row">
                <div class="scrollspy-example">
                    <ul v-for="(value, index) in random_message" :key="index" class="list-unstyled">
                        <div  v-if="value.from_user_id == user_id" class="">
                            <li class="d-flex justify-content-between mb-lg-4">
                                <div class="card w-50 start-50 rounded-3" style="background: honeydew">
                                    <div class="card-body ">
                                        <p class="mb-2">
                                            {{value.message}}
                                        </p>
                                        <p class="text-muted small mb-0 text-sm-end">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock" viewBox="0 0 16 16">
                                                <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"/>
                                                <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z"/>
                                            </svg>
                                            {{value.created_at}}
                                        </p>
                                    </div>
                                </div>
                            </li>
                        </div>
                        <div  v-if="value.from_user_id != user_id" class="">
                            <li class="d-flex justify-content-between mb-lg-4">
                                <div class="card w-50 start-0 rounded-3" style="background: white">
                                    <div class="card-header small d-flex justify-content-between p-3"
                                         style="border-bottom: 1px solid rgba(255,255,255,.3);">
                                        <p class="fw-bold mb-0">{{match_username}}</p>
                                    </div>
                                    <div class="card-body">
                                        <p class="mb-2">
                                            {{value.message}}
                                        </p>
                                        <p class="text-muted small mb-0 text-sm-end">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock" viewBox="0 0 16 16">
                                                <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"/>
                                                <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z"/>
                                            </svg>
                                            {{value.created_at}}
                                        </p>
                                    </div>
                                </div>
                            </li>
                        </div>
                    </ul>
                    <ul class="list-unstyled">
                        <div v-if="typing && typing_data.user_id == user_id" class="">
                            <li class="d-flex justify-content-between mb-lg-4">
                                <div class="card w-50 start-50" style="background: honeydew">
                                    <div class="card-body">
                                        <p class="mb-0">
                                            {{typing_data.message}}
                                        </p>
                                    </div>
                                </div>
                            </li>
                        </div>
                        <div v-if="typing && typing_data.user_id != user_id" class="">
                            <li class="d-flex justify-content-between mb-lg-4">
                                <div class="card w-50 start-0" style="background: white">
                                    <div class="card-header small d-flex justify-content-between p-3"
                                         style="border-bottom: 1px solid rgba(255,255,255,.3);">
                                        <p class="fw-bold mb-0">{{match_username}}</p>
                                    </div>
                                    <div class="card-body">
                                        <p class="mb-0">
                                            {{typing_data.message}}
                                        </p>
                                    </div>
                                </div>
                            </li>
                        </div>
                    </ul>

                </div>
            </div>
        </div>

        <div class="input-group fixed-bottom">
            <div class="input-group input-group-lg">
                <div v-if="function_show" class="btn-group dropup">
                    <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        功能
                    </button>
                    <ul class="dropdown-menu">
                        <li @click="leaveChannel(false)"><a class="dropdown-item" href="javascript:void(0);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-bar-left" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M12.5 15a.5.5 0 0 1-.5-.5v-13a.5.5 0 0 1 1 0v13a.5.5 0 0 1-.5.5ZM10 8a.5.5 0 0 1-.5.5H3.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L3.707 7.5H9.5a.5.5 0 0 1 .5.5Z"/>
                            </svg>
                            離開
                        </a></li>
                        <li @click="inviteFriend"><a class="dropdown-item" href="javascript:void(0);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-add" viewBox="0 0 16 16">
                                <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Zm.5-5v1h1a.5.5 0 0 1 0 1h-1v1a.5.5 0 0 1-1 0v-1h-1a.5.5 0 0 1 0-1h1v-1a.5.5 0 0 1 1 0Zm-2-6a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z"/>
                                <path d="M8.256 14a4.474 4.474 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10c.26 0 .507.009.74.025.226-.341.496-.65.804-.918C9.077 9.038 8.564 9 8 9c-5 0-6 3-6 4s1 1 1 1h5.256Z"/>
                            </svg>
                            申請好友
                        </a></li>
                    </ul>
                </div>
                <button v-if="leave_show_status" @click="leaveChannel(true)" class="btn btn-danger" type="button">離開</button>
                <input v-model="chat_message"
                       :disabled="input_disabled"
                       v-if="input_status"
                       v-on:keypress.enter="sendMessage"
                       v-on:keypress.NumpadEnter="sendMessage"
                       @keyup="getKeyUpForWhisper"
                       type="text"
                       class="form-control fixed-bottom input-text"
                       placeholder="Say something">
                <button v-if="input_status" @click="sendMessage" :disabled="input_disabled" class="btn btn-danger btn-lg btn-secondary" type="button">輸入</button>
            </div>
        </div>
    </section>


    <!--    隨機聊天選項區塊-->
    <div v-if="random_select" class="btn-div" style="width: 18em">
        <div class="card text-center">
            <div class="card-header">
                匹配條件
            </div>
            <div class="card-body">
                <div class="form-floating">
                    <select v-model="gender" class="form-select" id="floatingSelect" aria-label="Floating label select example"
                            v-bind:disabled="start_random_status">
                        <option value="all" :selected="gender">不限</option>
                        <option value="male" :selected="gender">男</option>
                        <option value="female" :selected="gender">女</option>
                    </select>
                    <label for="floatingSelect">性別</label>
                </div>
            </div>
            <div class="card-footer text-muted">
            </div>
            <div class="btn-group" role="group" aria-label="Basic example">
                <button @click="startRandom" v-bind:disabled="start_random_status" type="button"
                        class="btn btn-outline-success ">
                <span v-show="start_random_status" class="spinner-border spinner-border-sm" role="status"
                      aria-hidden="true"></span>
                    {{ start_random_show }}
                </button>
                <button @click="cancelRandom" v-show="start_random_status" type="button" class="btn btn-outline-danger">
                    取消
                </button>
            </div>


        </div>
    </div>


</template>

<script>
import Home from "./home.vue";
import {onMounted, reactive, ref, watch, nextTick} from "vue";
export default {
    name: "random",
    components: {Home},
    setup () {
        const user_id = localStorage.getItem('user_id');
        let to_user_id = ref(null);
        let chat_div = ref(false);
        let start_random_status = ref(false);
        let cancel_random_status = ref(false);
        let match_user_alert = ref(false);
        let random_select = ref(true);
        let start_random_show = ref('開始聊天');
        let personal_channel_name = 'personal-room-' + localStorage.getItem('user_id');
        let match_username = ref('');
        let random_room_id = ref(null);
        let random_message = ref([]);
        let chat_message = ref('');
        let typing = ref(false);
        let typing_data = ref({});
        let match_user_alert_class = ref('');
        let match_user_alert_message = ref('');
        let input_status = ref(true);
        let random_chat_room_channel_name = '';
        let button_class = ref('btn btn-danger');
        let input_disabled = ref(false);
        let leave_show_status = ref(false);
        let function_show = ref(true);
        let gender = ref('all');


        onMounted(function () {
            console.log(personal_channel_name);
            checkRandomChat();
            Echo.private(personal_channel_name)
                .listen('.success-get-random-user', function (e) {
                    console.log(e);
                    match_user_alert_class.value = 'alert alert-success alert-dismissible fade show';
                    match_user_alert.value = true;
                    match_username.value = e.match_username;
                    match_user_alert_message.value = '恭喜配對到：'+ match_username.value;
                    to_user_id.value = e.match_user_id;
                    chat_div.value = true;
                    random_select.value = false;
                    random_room_id.value = e.room_id;
                })
                .listen('.leave-random-room', function (e) {
                    console.log('leave-random-room');
                    input_disabled.value = true;
                    match_user_alert.value = true;
                    match_user_alert_class.value = 'alert alert-danger alert-dismissible fade show';
                    match_user_alert_message.value = '對方已經離開，請點選離開。'
                    nextTick(function () {
                        let scroll = document.querySelector('.scrollspy-example')
                        scroll.scrollHeight = scroll.scrollTop;
                    })
                    leave_show_status.value = true;
                    function_show.value = false;
                    Echo.leave(random_chat_room_channel_name);
                });
        })

        watch(random_room_id, (new_value, old_value) => {
            console.log('room_id  -> ' + new_value);
            random_chat_room_channel_name = 'random-chat-room-'+new_value;
            Echo.private(random_chat_room_channel_name)
                .listen('.random-chat-room', function (e) {
                    random_message.value.push(e);
                    nextTick(function () {
                        let scroll = document.querySelector('.scrollspy-example')
                        scroll.scrollTop = scroll.scrollHeight;
                    })
                })
                .listenForWhisper('typing', function (e) {
                    typing.value = true;
                    typing_data.value = {
                        user_id: e.user_id,
                        message: e.message
                    };
                    nextTick(function () {
                        let scroll = document.querySelector('.scrollspy-example')
                        scroll.scrollTop = scroll.scrollHeight;
                    })
                    setTimeout( () => {
                        typing.value = false;
                    }, 1000)
                });
        })

        function inviteFriend() {
            axios.post('api/v1/friend/invite', {
                to_user_id: to_user_id.value,
                status: 'pending'
            }).then((response) => {
                    alert('送出成功');
                    console.log(response);
                }
            ).catch((error) => {
                alert(error.response.data.status_message);
                console.log(error)
            });
        }

        function getKeyUpForWhisper(e) {
            if (e.code !== 'Enter' && e.code !== 'NumpadEnter') {
                setTimeout(function () {
                    Echo.private(random_chat_room_channel_name)
                        .whisper('typing', {
                            message:'對方正在輸入中...',
                            user_id: user_id
                        });
                }, 100);
            }
        }

        function sendMessage() {
            axios.post('api/v1/message/send', {
                room_id: random_room_id.value,
                from_user_id: user_id,
                to_user_id: to_user_id.value,
                message: chat_message.value,
                room_type: 'random'
            }).then((response) => {
                console.log(response);
                chat_message.value = '';
                }
            ).catch((error) => {
                console.log(error)
            });
        }

        function checkRandomChat() {
            // 檢查是否已在配對
            axios.post('api/v1/random/check', {}).then((response) => {
                    console.log(response);
                    if (response.data.code === '1205') {
                        //已配對
                        const result = response.data.return_data;
                        random_room_id.value = result.room_id;
                        to_user_id.value = result.to_user_id;
                        getRoomChatData(random_room_id.value);
                        chat_div.value = true;
                        random_select.value = false;
                        match_username.value = result.to_user_name
                    } else if (response.data.code === '1206') {
                        //尚未配對
                    } else if (response.data.code === '1204') {
                        //配對中
                        start_random_status.value = true;
                        start_random_show.value = '配對中';
                        cancel_random_status.value = true;
                        gender.value = localStorage.getItem('select_gender');

                    }
                }
            ).catch((error) => {
                console.log(error)
            });
        }

        function leaveChannel(leave_status) {
            if (leave_status === true) {
                setTimeout(function () {
                    location.reload();
                }, 200)
            } else {
                if (confirm('確定要離開嗎？')) {
                    chat_div.value = false;
                    random_select.value = true;
                    start_random_status.value = false;
                    match_user_alert.value = false;
                    start_random_show.value = '開始聊天';
                    axios.post('api/v1/random/leave', {
                        to_user_id: to_user_id.value,
                        room_id: random_room_id.value
                    })
                        .then((response) => {
                            console.log(response);
                            console.log('leave:' + random_chat_room_channel_name)
                            Echo.leave(random_chat_room_channel_name);
                            setTimeout(function () {
                                location.reload();
                            }, 200)
                        })
                        .catch((error) => {
                            console.log(error)
                        });
                }
            }
        }

        async function getRoomChatData(room_id) {
            await axios.get('api/v1/message/room/' + room_id, {
                params: {
                    room_type: "random"
                }
            }).then((response) => {
                    console.log(response);
                    if (response.data.return_data.length > 0) {
                        random_message.value = response.data.return_data;
                    }
                }
            ).catch((error) => {
                console.log(error)
            });
            setTimeout(function () {
                nextTick(function () {
                    let scroll = document.querySelector('.scrollspy-example')
                    scroll.scrollTop = scroll.scrollHeight;
                })
            }, 500)
        }

        function startRandom() {
            cancel_random_status.value = true;
            start_random_status.value = true;
            start_random_show.value = '配對中';
            localStorage.setItem('select_gender', gender.value);
            axios.post('api/v1/random/start', {
                select_gender: gender.value
            }).then((response) => {
                    console.log(response);
                }
            ).catch((error) => {
                console.log(error)
            });
        }
        function cancelRandom() {
            start_random_status.value = false;
            gender.value = 'all';
            start_random_show.value = '開始聊天';
            axios.post('api/v1/random/cancel')
                .then((response) => {
                    console.log(response);
                }
            ).catch((error) => {
                console.log(error)
            });
        }

        return {
            chat_div,
            start_random_status,
            start_random_show,
            match_username,
            match_user_alert,
            random_select,
            random_room_id,
            random_message,
            user_id,
            chat_message,
            to_user_id,
            typing,
            typing_data,
            match_user_alert_class,
            match_user_alert_message,
            input_status,
            button_class,
            input_disabled,
            leave_show_status,
            function_show,
            gender,
            cancel_random_status,
            leaveChannel,
            startRandom,
            inviteFriend,
            sendMessage,
            getKeyUpForWhisper,
            cancelRandom
        }
    }
}
</script>

<style scoped>

.container {

}

.btn-div{
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);

}


.scrollspy-example {
    position: relative;
    height: 750px;
    overflow: auto;
}
</style>
