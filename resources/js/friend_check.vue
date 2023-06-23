<template>
<home></home>
    <div class="container py-5">
        <div class="row">
            <div class=" scrollspy-example">
                <h5 class="font-weight-bold mb-3 text-center text-lg-start">好友邀請</h5>
                <div class="card">
                    <div v-if="pending_data.length === 0" class="card-body">
                        <ul class="list-unstyled mb-2">
                            <li class="p-2 border-bottom" style="background-color: #eee;">
                                <div class="d-flex justify-content-between">
                                    <div class="d-flex flex-row">
                                        <div class="pt-1">
                                            <p class="fw-bold mb-0">尚無邀請</p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <!--                        <ul class="list-unstyled mb-3">-->
                        <!--                            <li class="p-2 border-bottom" style="background-color: #eee;">-->
                        <!--                                <a href="javascript:void(0);" class="d-flex justify-content-between">-->
                        <!--                                    <div class="d-flex flex-row">-->
                        <!--                                        <div class="pt-1">-->
                        <!--                                            <p class="fw-bold mb-0">123</p>-->
                        <!--                                            <p class="small text-muted">Hello, Are you there?</p>-->
                        <!--                                        </div>-->
                        <!--                                    </div>-->
                        <!--                                    <div class="pt-1">-->
                        <!--                                        <p class="small text-muted mb-1">Just now</p>-->
                        <!--                                        <span class="badge bg-danger float-end">1</span>-->
                        <!--                                    </div>-->
                        <!--                                </a>-->
                        <!--                            </li>-->
                        <!--                        </ul>-->
                    </div>
                    <div v-for="(value, index) in pending_data" :key="index" class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li class="p-2 border-bottom mb-0" style="background-color: #eee;">
                                <div class="d-flex justify-content-between">
                                    <div class="d-flex flex-row">
                                        <div class="pt-1">
                                            <p class="fw-bold mb-0 text-primary">
                                                暱稱：{{value.from_username}}
                                            </p>
                                            <p class="small text-muted">{{value.send_at}}</p>
                                        </div>
                                    </div>
                                    <div class="pt-1">
                                        <div class="d-grid gap-2 d-md-flex">
                                            <button @click="editFriendCheck(value.user_friend_id, 'confirm')" class="btn btn-primary btn-lg" type="button">確認</button>
                                            <button @click="editFriendCheck(value.user_friend_id, 'reject')" class="btn btn-outline-danger btn-lg" type="button">拒絕</button>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
<!--                        <ul class="list-unstyled mb-3">-->
<!--                            <li class="p-2 border-bottom" style="background-color: #eee;">-->
<!--                                <a href="javascript:void(0);" class="d-flex justify-content-between">-->
<!--                                    <div class="d-flex flex-row">-->
<!--                                        <div class="pt-1">-->
<!--                                            <p class="fw-bold mb-0">123</p>-->
<!--                                            <p class="small text-muted">Hello, Are you there?</p>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                    <div class="pt-1">-->
<!--                                        <p class="small text-muted mb-1">Just now</p>-->
<!--                                        <span class="badge bg-danger float-end">1</span>-->
<!--                                    </div>-->
<!--                                </a>-->
<!--                            </li>-->
<!--                        </ul>-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Home from "./home.vue";
import {onMounted, ref} from "vue";
export default {
    name: "friend_check",
    components: {Home},
    setup () {
        const user_id = localStorage.getItem('user_id');
        let pending_data = ref([]);

        function getCheckList(status) {
            axios.get('api/v1/friend/' + user_id + '/list', {
                params: {
                    status: status
                }
            }).then((response) => {
                    pending_data.value = response.data.return_data;
                    sessionStorage.setItem('add_friend_unread_count', 0);
                    console.log(response);
                }
            ).catch((error) => {
                console.log(error)
            });
        }

        async function editFriendCheck(user_friend_id, status) {
            if (status === 'confirm') {
                if (confirm('確定要加入好友嗎?')) {
                    await editFriendApi(user_friend_id, status);

                }
            } else {
                if (confirm('確定要拒絕嗎?')) {
                    await editFriendApi(user_friend_id, status);
                }
            }
        }
        function editFriendApi(user_friend_id, status) {
            axios.patch('api/v1/friend/' + user_friend_id + '/update', {
                status: status
            }).then((response) => {
                    console.log(response);
                    getCheckList('pending')
                }
            ).catch((error) => {
                console.log(error)
            });
        }
        onMounted(function () {
            getCheckList('pending');
        });

        return {
            pending_data,
            editFriendCheck
        };
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
