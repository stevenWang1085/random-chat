<template>
    <home></home>
    <section class="vh-100 gradient-custom">
        <div class="container py-5">
            <div v-if="dashboard.show" class="card-group">
                <div class="row row-cols-sm-2 row-cols-md-2 g-1">
                    <div class="row">
                        <div class="card border-success mb-3" style="max-width: 18rem;">
                            <div class="card-header bg-transparent border-success">總註冊人數</div>
                            <div class="card-body text-success">
                                <h1 class="card-title">{{dashboard.user}}</h1>
                                <p class="card-text"></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="card border-success mb-3" style="max-width: 18rem;">
                            <div class="card-header bg-transparent border-success">當前成功配對數</div>
                            <div class="card-body text-success">
                                <h1 class="card-title">{{dashboard.complete}}</h1>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="card border-success mb-3" style="max-width: 18rem;">
                            <div class="card-header bg-transparent border-success">當前等待配對數</div>
                            <div class="card-body text-success">
                                <h1 class="card-title">{{dashboard.pending}}</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
</template>

<script>
import {onMounted, reactive, ref} from "vue";
import Home from "./home.vue";
export default {
    name: "dashboard",
    components: {Home},
    setup() {
        const dashboard = reactive({
            show: 0,
            user: '',
            complete: '',
            pending: '',
        });
        onMounted(function () {
            showList();
        });

        function showList() {
            axios.get('api/v1/dashboard/list', {
            }).then((response) => {
                let result = response.data;
                if (result.code === 403) {
                    alert('無此權限');
                } else {
                    dashboard.show = 1;
                    dashboard.user = result.user;
                    dashboard.complete = result.complete;
                    dashboard.pending = result.pending;
                }
            }
            ).catch((error) => {
                console.log(error);
            })
        }

        return {
            dashboard
        }
    }
}
</script>
<style scoped>
.gradient-custom {
    padding-top: 80px;
}
</style>
