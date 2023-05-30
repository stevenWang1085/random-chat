<template>
<home></home>
    <section class="gradient-custom">
        <div class="container py-5">
            <div class="row">
                <div class=" scrollspy-example">
                    <h5 class="font-weight-bold mb-3 text-center text-lg-start">Member</h5>
                    <div class="card">
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                <li class="p-2 border-bottom" style="background-color: #eee;">
                                    <a href="#!" class="d-flex justify-content-between">
                                        <div class="d-flex flex-row">
                                            <div class="pt-1">
                                                <p class="fw-bold mb-0">John Doe</p>
                                                <p class="small text-muted">Hello, Are you there?</p>
                                            </div>
                                        </div>
                                        <div class="pt-1">
                                            <p class="small text-muted mb-1">Just now</p>
                                            <span class="badge bg-danger float-end">1</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="p-2 border-bottom">
                                    <a href="#!" class="d-flex justify-content-between">
                                        <div class="d-flex flex-row">
                                            <div class="pt-1">
                                                <p class="fw-bold mb-0">Danny Smith</p>
                                                <p class="small text-muted">Lorem ipsum dolor sit.</p>
                                            </div>
                                        </div>
                                        <div class="pt-1">
                                            <p class="small text-muted mb-1">5 mins ago</p>
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

</template>

<script>
import Home from "./home.vue";
export default {
    name: "random",
    components: {Home}
}
</script>

<style scoped>

.gradient-custom {
/*background: linear-gradient(to bottom right, rgba(252, 203, 144, 1), rgba(213, 126, 235, 1))*/
}

.scrollspy-example {
    position: relative;
    height: 750px;
    overflow: auto;
}

</style>
