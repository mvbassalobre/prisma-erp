<template>
    <div class="container-fluid page-body-wrapper">
        <div class="main-panel w-100">
            <comp-navbar :customer="customer" :logo="logo" />
            <div class="container-fluid pt-2 mb-5">
                <div class="row mb-3 mt-2">
                    <div class="col-12 d-flex flex-row align-items-center">
                        <h4 class="mb-1"><span class="el-icon-s-finance mr-2"></span> Area do Cliente</h4>
                    </div>
                </div>
                <slot />
                <customer-attendance v-if="loaded" :customer="customer" :data="data" :customer_area="true" :meetings="data.meetings" />
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ['customer', 'logo'],
    data() {
        return {
            loaded: false,
            data: {},
        }
    },
    components: {
        'comp-navbar': require('./-navbar.vue').default,
    },
    created() {
        this.load()
    },
    methods: {
        load() {
            console.log(this.customer.tenant)
            this.$http.post(`/${this.customer.code}/get-customer-data`).then((resp) => {
                resp = resp.data
                this.data = resp.data
                this.loaded = true
            })
        },
    },
}
</script>