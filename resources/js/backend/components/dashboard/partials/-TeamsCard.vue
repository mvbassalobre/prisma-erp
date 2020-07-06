<template>
    <div class="col-md-3 col-sm-12" @click="$goTo('/admin/teams')">
        <div class="dashcard d-flex flex-row justify-content-between align-items-center p-3">
            <div class="title d-flex flex-column">
                <div class="mb-2">Times</div>
                <loading-container :height="22" :loaded="loaded">
                    <h3 class="mb-0 number">{{qty}}</h3>
                </loading-container>
            </div>
            <div>
                <i class="el-icon-s-help icon"></i>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ['role', 'user'],
    data() {
        return {
            loaded: null,
            qty: null,
            attempts: 0
        }
    },
    components: {
        "loading-container": require("../../general/-LoadingContainer").default
    },
    mounted() {
        this.initialize()
    },
    methods: {
        initialize() {
            this.attempts++
            this.$http.post(`${laravel.general.root_url}/admin/dashboard/get_info/qtyUsers`).then(res => {
                setTimeout(() => {
                    res = res.data
                    this.qty = res.qty <= 0 ? "Nenhum" : res.qty
                    this.loaded = true
                }, 500)
            }).catch(er => {
                if (this.attempts <= 3) return this.initialize()
                console.log(er)
            })
        }
    }
}
</script>

<style lang="scss" scoped>
.dashcard {
    background-color: #7093e2;
}
</style>