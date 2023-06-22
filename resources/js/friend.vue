<template>
<home></home>
    <section v-if="show_friend_list" class="gradient-custom">
        <div class="container py-5">
            <div class="row">
                <div class=" scrollspy-example">
                    <h5 class="font-weight-bold mb-3 text-center text-lg-start">好友清單</h5>
                    <div class="card">
                        <div class="card-body">
                            <ul v-if="friend_data.length === 0" class="list-unstyled mb-3">
                                <li class="p-2 border-bottom" style="background-color: #eee;">
                                   尚無好友
                                </li>
                            </ul>
                            <ul v-for="(value, index) in friend_data" :key="index" class="list-unstyled mb-3">
                                <li class="p-2 border-bottom" style="background-color: #eee;">
                                    <a @click="friendChat(value)" href="javascript:void(0);" class="d-flex justify-content-between">
                                        <div class="d-flex flex-row">
                                            <div class="pt-1">
                                                <p v-if="value.to_gender === 'male'" class="fw-bold mb-0">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gender-male" viewBox="0 0 16 16">
                                                        <path fill-rule="evenodd" d="M9.5 2a.5.5 0 0 1 0-1h5a.5.5 0 0 1 .5.5v5a.5.5 0 0 1-1 0V2.707L9.871 6.836a5 5 0 1 1-.707-.707L13.293 2H9.5zM6 6a4 4 0 1 0 0 8 4 4 0 0 0 0-8z"/>
                                                    </svg>
                                                    {{value.to_username}}
                                                </p>
                                                <p v-else class="fw-bold mb-0">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"  color="red" fill="currentColor" class="bi bi-gender-female" viewBox="0 0 16 16">
                                                        <path fill-rule="evenodd" d="M8 1a4 4 0 1 0 0 8 4 4 0 0 0 0-8zM3 5a5 5 0 1 1 5.5 4.975V12h2a.5.5 0 0 1 0 1h-2v2.5a.5.5 0 0 1-1 0V13h-2a.5.5 0 0 1 0-1h2V9.975A5 5 0 0 1 3 5z"/>
                                                    </svg>
                                                    {{value.to_username}}
                                                </p>

                                                <p class="small text-muted ">{{value.latest_message}}</p>
                                            </div>
                                        </div>
                                        <div class="pt-1">
                                            <p class="small text-muted mb-1">{{value.latest_send_at}}</p>
<!--                                            <span class="badge bg-danger float-end">1</span>-->
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section v-if="show_friend_chat" class="gradient-custom">
        <div class="container py-5">
            <div class="row">
                <div class="scrollspy-example">
                    <ul v-for="(value, index) in chat_data" :key="index" class="list-unstyled">
                        <div  v-if="value.from_user_id == user_id" class="">
                            <li class="d-flex justify-content-between mb-lg-4">
                                <div class="card w-50 start-50" style="background: honeydew">
                                    <div class="card-body" >
                                        <p class="mb-0">
                                            {{value.message}}
                                        </p>
                                        <p class="text-muted small mb-2 text-sm-end">
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
                        <div v-if="value.from_user_id != user_id" class="">
                            <li class="d-flex justify-content-between mb-lg-4">
                                <div class="card w-50 start-0" style="background: white">
                                    <div class="card-header small d-flex justify-content-between p-3"
                                         style="border-bottom: 1px solid rgba(255,255,255,.3);">
                                        <p class="fw-bold mb-0">{{chat_user_name}}</p>
                                    </div>
                                    <div class="card-body" >
                                        <p class="mb-0">
                                            {{value.message}}
                                        </p>
                                        <p class="text-muted small mb-2 text-sm-end">
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
                                        <p class="fw-bold mb-0">{{chat_user_name}}</p>
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
        <div  class="input-group fixed-bottom">

            <div class="input-group input-group-lg">
                <input v-model="chat_message"
                       v-on:keypress.enter="sendMessage"
                       v-on:keypress.NumpadEnter="sendMessage"
                       @keyup="getKeyUpForWhisper"
                       type="text"
                       class="form-control fixed-bottom input-text"
                       placeholder="Say something">
                <button @click="sendMessage" class="btn btn-secondary btn-lg btn-danger" type="button">輸入</button>
            </div>

        </div>
    </section>
</template>

<script>
import Home from "./home.vue";
import {nextTick, onMounted, ref, watch} from "vue";
export default {
    name: "friend",
    components: {Home},
    setup () {
        const user_id = localStorage.getItem('user_id');
        let show_friend_list = ref(true);
        let show_friend_chat = ref(false);
        let friend_data = ref([]);
        let chat_data = ref([]);
        let chat_message = ref(null);
        let chat_user_name = ref(null);
        let typing = ref(false);
        let typing_data = ref({});
        let personal_room_channel_name = '';
        let send_chat_data = {
            room_id: null,
            from_user_id: null,
            to_user_id: null,
        };


        function friendChat(friend_detail) {
            show_friend_list.value = false;
            show_friend_chat.value = true;
            send_chat_data.room_id = friend_detail.room_id;
            send_chat_data.from_user_id = friend_detail.from_user_id;
            send_chat_data.to_user_id = friend_detail.to_user_id;
            sessionStorage.setItem('chat_user_id', friend_detail.to_user_id);
            sessionStorage.setItem('chat_user_name', friend_detail.to_username);
            sessionStorage.setItem('room_id', friend_detail.room_id);
            personal_room_channel_name = 'random-chat-room-' +friend_detail.room_id;
            getRoomChatData(friend_detail.room_id);
        }

        async function getFriendList(user_id) {
            await friendListApi(user_id);
        }

        function friendListApi(user_id) {
            axios.get('api/v1/friend/' + user_id + '/list', {
                params: {
                    status:'confirm'
                }
            }).then((response) => {
                friend_data.value = response.data.return_data;
            }).catch((error) => {
                console.log(error)
            });
        }

        async function getRoomChatData(room_id) {
            chat_user_name.value = sessionStorage.getItem('chat_user_name');
            await axios.get('api/v1/message/room/' + room_id).then((response) => {
                    console.log(response);
                    if (response.data.return_data.length > 0) {
                        chat_data.value = response.data.return_data;
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

        function sendMessage() {
            axios.post('api/v1/message/send', {
                room_id: send_chat_data.room_id,
                from_user_id: send_chat_data.from_user_id,
                to_user_id: send_chat_data.to_user_id,
                message: chat_message.value
            }).then((response) => {
                    console.log(response);
                    chat_message.value = '';
                }
            ).catch((error) => {
                console.log(error)
            });
        }

        function getKeyUpForWhisper(e) {
            console.log(personal_room_channel_name);
            if (e.code !== 'Enter' && e.code !== 'NumpadEnter') {
                setTimeout(function () {
                    Echo.private(personal_room_channel_name)
                        .whisper('typing', {
                            message:'對方正在輸入中...',
                            user_id: user_id
                        });
                }, 100);
            }
        }

        onMounted(function () {
            getFriendList(user_id);
        });
        watch(friend_data, (new_value, old_value) => {
            new_value.forEach(function (node) {
                personal_room_channel_name = 'random-chat-room-' + node.room_id;
                Echo.private(personal_room_channel_name)
                    .listen('.random-chat-room', function (e) {
                        console.log(e);
                        let to_user_id = sessionStorage.getItem('chat_user_id');
                        let to_room_id = sessionStorage.getItem('room_id');
                        if ( to_user_id == e.to_user_id || node.room_id == to_room_id) {
                            chat_data.value.push(e);
                        }
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
        })

        return {
            friend_data,
            show_friend_list,
            show_friend_chat,
            chat_message,
            chat_data,
            user_id,
            chat_user_name,
            typing,
            typing_data,
            sendMessage,
            friendChat,
            getKeyUpForWhisper
        }
    }
}
</script>

<style scoped>

.scrollspy-example {
    position: relative;
    height: 750px;
    overflow: auto;
}

</style>
