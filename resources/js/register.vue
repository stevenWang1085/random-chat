<template>
    <section class="vh-100 gradient-custom">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card bg-dark text-white" style="border-radius: 1rem;">
                        <div class="card-body p-5 text-center">

                            <div class="mb-md-5 mt-md-4 pb-5">

                                <h2 class="fw-bold mb-2 text-uppercase">註冊</h2>
                                <p class="text-white-50 mb-5">帳號與密碼至少六位，性別請填男或女</p>

                                <div class="form-outline form-white mb-4">
                                    <input v-model="member.account" id="type_account" class="form-control form-control-lg" />
                                    <label class="form-label" for="type_account">帳號</label>
                                </div>

                                <div class="form-outline form-white mb-4">
                                    <input v-model="member.password" type="password" id="typePasswordX" class="form-control form-control-lg" />
                                    <label class="form-label" for="typePasswordX">密碼</label>
                                </div>

                                <div class="form-outline form-white mb-4">
                                    <input v-model="member.username" id="nickname" class="form-control form-control-lg" />
                                    <label class="form-label" for="nickname">使用名稱</label>
                                </div>

                                <div class="form-outline form-white mb-4">
                                    <input v-model="member.gender" id="gender" class="form-control form-control-lg" />
                                    <label class="form-label" for="gender">性別</label>
                                </div>


<!--                                <p class="small mb-5 pb-lg-2"><a class="text-white-50" href="#!">Forgot password?</a></p>-->

                                <button @click="signUp" class="btn btn-outline-light btn-lg px-5" type="submit">註冊</button>

                                <div class="d-flex justify-content-center text-center mt-4 pt-1">
<!--                                    <a href="#!" class="text-white"><i class="fab fa-facebook-f fa-lg"></i></a>-->
<!--                                    <a href="#!" class="text-white"><i class="fab fa-twitter fa-lg mx-4 px-2"></i></a>-->
                                    <a href="#!" class="text-white"><i class="fab fa-google fa-lg"></i></a>
                                </div>

                            </div>

                            <div>
                                <p class="mb-0">已經有帳號了？ <a href="javascript:void(0);" @click="goToLogin" class="text-white-50 fw-bold">登入</a>
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
    name: "register",
    setup() {
        const member = reactive({
            account: '',
            password: '',
            gender: '',
            username: ''
        });


        function goToLogin() {
            location.href='/login';
        }

        function signUp() {
            let gender = '';
            if ( member.gender === '男') {
                gender = 'male'
            } else if (member.gender === '女') {
                gender = 'female'
            } else {
                alert('性別請輸入男或女。');
                return;
            }
            axios.post('api/v1/user/register', {
                account: member.account,
                password: member.password,
                gender: gender,
                username: member.username
            }).then((response) => {
                    alert('註冊成功');
                    location.href = '/login';
                }
            ).catch((error) => {
                alert(error.response.data.message);
            })
        }

        return {
            member,
            signUp,
            goToLogin
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
