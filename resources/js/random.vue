<template>
<home></home>
    <section class="gradient-custom">
        <div class="container py-5">
            <div class="row">
                <div class="scrollspy-example">

                    <ul class="list-unstyled">
                        <div class="">
                            <li class="d-flex justify-content-between mb-lg-4">
                                <div class="card w-50 start-50" style="background: honeydew">
                                    <div class="card-body" >
                                        <p class="mb-0">
                                            安
                                        </p>
                                        <p class="text-muted small mb-0"><i class="far fa-clock"></i> 10 mins ago</p>
                                    </div>
                                </div>
                            </li>
                        </div>
                        <div class="">
                            <li class="d-flex justify-content-between mb-lg-4">
                                <div class="card w-50 start-0" style="background: white">
                                    <div class="card-header small d-flex justify-content-between p-3"
                                         style="border-bottom: 1px solid rgba(255,255,255,.3);">
                                        <p class="fw-bold mb-0">Brad Pitt</p>
                                    </div>
                                    <div class="card-body" >
                                        <p class="mb-0">
                                            堅持下去！堅持下去！堅持下去！堅持下去！堅持下去！堅持下去！
                                        </p>
                                        <p class="text-muted small mb-0"><i class="far fa-clock"></i> 10 mins ago</p>
                                    </div>
                                </div>
                            </li>
                        </div>
                        <div class="">
                            <li class="d-flex justify-content-between mb-lg-4">
                                <div class="card w-50 start-50" style="background: honeydew">
                                    <div class="card-body" >
                                        <p class="mb-0">
                                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                                            labore et dolore magna aliqua.
                                        </p>
                                        <p class="text-muted small mb-0"><i class="far fa-clock"></i> 10 mins ago</p>
                                    </div>
                                </div>
                                <div class="rounded-circle">

                                </div>
                            </li>
                        </div>
                    </ul>

                </div>
            </div>
        </div>
        <div  class="input-group fixed-bottom">

            <div class="input-group input-group-lg">
                <input type="text"
                       class="form-control fixed-bottom input-text"
                       placeholder="Say something">
                <button class="btn btn-secondary btn-lg btn-secondary" type="button">輸入</button>
            </div>

        </div>
    </section>
</template>

<script>
import Home from "./home.vue";
export default {
    name: "random",
    components: {Home}
}
</script>

<style scoped>

.scrollspy-example {
    position: relative;
    height: 750px;
    overflow: auto;
}
</style>
