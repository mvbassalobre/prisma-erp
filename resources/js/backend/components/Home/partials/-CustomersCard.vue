<template>
    <div
        class="col-md-3 col-sm-12 dashcard d-flex flex-row justify-content-between align-items-center"
    >
        <div class="title d-flex flex-column">
            <div class="mb-2">Clientes</div>
            <loading-container :height="33" :loaded="loaded">
                <h3 class="mb-0">{{qty}}</h3>
            </loading-container>
        </div>
        <div>
            <i class="el-icon-s-custom icon"></i>
        </div>
    </div>
</template>
<script>
export default {
    props: ['role', 'user'],
    data() {
        return {
            loaded: null,
            qty: null
        }
    },
    components: {
        "loading-container": require("../../general/-LoadingContainer").default
    },
    mounted() {
        this.$http.post(laravel.general.root_url + "/admin/dashboard/get_info", { type: "qtyCustomers" }).then(res => {
            setTimeout(() => {
                res = res.data
                this.qty = res.qty
                this.loaded = true
            }, 500)
        }).catch(er => {
            this.loaded = true
        })
    }
}
</script>
<style scoped lang="scss">
.dashcard {
    background-color: #0fceab;
    color: white;
    .title {
        font-size: 30px;
    }
    .icon {
        font-size: 100px;
    }
}
</style>