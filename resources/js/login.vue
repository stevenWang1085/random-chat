<template>
    <section class="vh-100 gradient-custom">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card bg-dark text-white" style="border-radius: 1rem;">
                        <div class="card-body p-5 text-center">

                            <div class="mb-md-5 mt-md-4 pb-5">

                                <h2 class="fw-bold mb-2 text-uppercase">登入</h2>
                                <p class="text-white-50 mb-5"></p>

                                <div class="form-outline form-white mb-4">
                                    <input v-model="member.account"  v-on:keyup.enter="login" id="type_account" class="form-control form-control-lg" />
                                    <label class="form-label" for="type_account">帳號</label>
                                </div>

                                <div class="form-outline form-white mb-4">
                                    <input v-model="member.password" v-on:keyup.enter="login" type="password" id="typePasswordX" class="form-control form-control-lg" />
                                    <label class="form-label" for="typePasswordX">密碼</label>
                                </div>

                                <p class="small mb-5 pb-lg-2"><a class="text-white-50" href="#!">忘記密碼？</a></p>

                                <button @click="login" class="btn btn-outline-light btn-lg px-5" type="submit">登入</button>

                                <div class="d-flex justify-content-center text-center mt-4 pt-1">
                                    <a href="#!" class="text-white"><i class="fab fa-google fa-lg"></i></a>
                                </div>

                            </div>

                            <div>
                                <p class="mb-0">沒有帳號嗎? <a href="javascript:void(0);" @click="goToRegister" class="text-white-50 fw-bold">註冊</a>
                                </p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import {reactive, ref} from "vue";
export default {
    name: "login",
    props: {

    },
    setup() {
        const member = reactive({
            account: '',
            password: ''
        });
        function goToRegister() {
            location.href='/register';
        }
        function login() {
            axios.post('api/v1/user/login', {
                account: member.account,
                password: member.password
            }).then((response) => {
                console.log(response);
                localStorage.setItem('user_id', response.data.return_data.user_id);
                localStorage.setItem('username', response.data.return_data.username);
                location.href = '/random';
                }
            ).catch((error) => {
                alert('帳號或密碼輸入錯誤。')
            })
        }

        return {
            member,
            login,
            goToRegister
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
