<template>
<p v-show="show_redirect"> 跳轉中... </p>
    <section v-show="show_select_gender" class="vh-100 gradient-custom">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-4">
                    <div class="card bg-dark text-white" style="border-radius: 1rem;">
                        <div class="card-body p-5 text-center">
                            <div class="mb-md-5 mt-md-5 pb-4">
                                <h2 class="fw-bold mb-5 text-uppercase">選擇性別</h2>
                                <div class="form-outline form-white mb-3">
                                    <div class="form-outline form-white mb-4">
                                        <div class="form-floating">
                                            <select v-model="gender" class="form-select" id="floatingSelect" aria-label="Floating label select example">
                                                <option value="male">男</option>
                                                <option value="female">女</option>
                                            </select>
                                            <label class="form-label"  for="floatingSelect">性別</label>
                                        </div>
                                    </div>
                                </div>

                                <button @click="editGender"  class="btn btn-outline-light btn-lg px-5" type="submit">確認</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import {onMounted, ref} from "vue";
export default {
    name: "callback",
    setup() {
        let url = '';
        let url_code = '';
        let show_select_gender = ref(false);
        let show_redirect = ref(false);
        let gender = ref('');
        async function googleLogin() {
            await getUrl();
            axios.get('api/v1/user/google/login',  {
                params: {
                    code: url_code
                }
            }).then((response) => {
                let return_data = response.data.return_data;
                if (return_data.gender == null) {
                    show_select_gender.value = true;
                } else {
                    show_redirect.value = true;
                    location.href = '/random';
                }
                localStorage.setItem('user_id', response.data.return_data.user_id);
                localStorage.setItem('username', response.data.return_data.username);
                sessionStorage.setItem('add_friend_unread_count', response.data.return_data.add_friend_unread_count);

                }
            ).catch((error) => {
                console.log(error);
            })
        }

        function editGender() {
            axios.patch('api/v1/user/edit/profile', {
                gender: gender.value
            }).then((response) => {
                location.href = '/random';
                }
            ).catch((error) => {
                console.log(error)
            });
        }

        onMounted(function () {
            googleLogin();
        });

        function getUrl() {
            url = location.href;
            url = new URL(url);
            url_code = url.searchParams.get('code');
        }


        return {
            show_select_gender,
            show_redirect,
            gender,
            googleLogin,
            editGender
        }
    }
}
</script>

<style scoped>
.gradient-custom {
    /* fallback for old browsers */
    background: #6a11cb;

    /* Chrome 10-25, Safari 5.1-6 */
    background: -webkit-linear-gradient(to right, rgba(106, 17, 203, 1), rgba(37, 117, 252, 1));

    /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
    background: linear-gradient(to right, rgba(106, 17, 203, 1), rgba(37, 117, 252, 1))
}
</style>
