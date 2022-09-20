<template>
    <div class="col-md-6 col-sm-12">
        <div class="card h-100">
            <div class="card-body d-flex flex-column">
                <span class="f-12 mb-3">
                    <b>
                        <span class="el-icon-money mr-2"></span>
                        {{ withPayment == undefined ? 'PRODUTOS' : 'ANÁLISES'}}
                    </b> /
                    <template v-if="filter.daterange.length>0">No periodo do filtro</template>
                    <template v-else>Nos últimos 7 dias</template>
                </span>
                <loading-shimmer :loading="loading" :h="41">
                    <template v-if="!loading">
                        <h3>
                            <b>{{data.total_amount}}</b>
                        </h3>
                    </template>
                </loading-shimmer>
                <loading-shimmer :loading="loading" :h="16">
                    <template v-if="!loading">
                        <small>{{data.qty}} {{data.qty>1 ? 'lançamentos':'lançamento'}}</small>
                    </template>
                </loading-shimmer>
                <loading-shimmer :loading="loading" :h="200" class="dashboard-chart-area">
                    <template v-if="!loading">
                        <area-chart
                            :discrete="true"
                            height="200px"
                            :data="data.chart_data"
                            :legend="false"
                            prefix="R$ "
                        />
                    </template>
                </loading-shimmer>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ["filter", "withPayment"],
    data() {
        return {
            loading: true,
            attempts: 0,
            data: {},
        }
    },
    watch: {
        filter: {
            handler(val) {
                this.attempts = 0
                this.loading = true
                this.init()
            },
            deep: true
        }
    },
    components: {
        "loading-shimmer": require("../../template/-loading-shimmer.vue").default
    },
    created() {
        this.init()
    },
    methods: {
        init() {
            this.attempts++
            this.$http.post(`${laravel.general.root_url}/admin/dashboard/get_info/sold`, { ...this.filter, withPayment: (this.withPayment == undefined ? false : true) }).then(resp => {
                setTimeout(() => {
                    resp = resp.data
                    this.data = resp
                    this.loading = false
                }, 500)
            }).catch(er => {
                if (this.attempts <= 3) return this.init()
                console.log(er)
            })
        }
    }
}
</script>

<style lang="scss">
.f-10 {
    .el-switch__label {
        span {
            padding-top: 5px;
            font-size: 10px;
        }
    }
    .el-switch__core {
        height: 15px;
        &::after {
            width: 11px;
            height: 11px;
        }
    }
}
</style>