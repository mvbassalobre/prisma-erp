<template>
    <div id="results">
        <component-sumary :result="result" :loading="loading" :total="total" ref="sumary" />
        <div class="row mt-3" v-loading="loading" v-if="initialized">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex flex-row">
                        <b class="f-12">
                            <span class="el-icon-s-grid mr-2"></span>Clientes
                        </b>
                        <el-pagination
                            class="ml-auto"
                            background
                            small
                            layout="prev, pager, next"
                            :current-page="page"
                            @update:currentPage="p => page = p"
                            :page-size="25"
                            :total="result.total"
                        />
                    </div>
                    <div class="card-body p-0">
                        <div class="row" v-if="result.data.length > 0">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table
                                        class="table table-striped hovered resource-table table-hover mb-0 table-sm f-12"
                                    >
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(row,i) in result.data" :key="i">
                                                <td>
                                                    <a
                                                        class="link"
                                                        :href="`/admin/customers/${row.code}`"
                                                        target="_BLANK"
                                                    >{{row.code}}</a>
                                                </td>
                                                <td>
                                                    <div v-html="row.name"></div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row" v-else>
                            <div class="col-12 text-center py-5">
                                <span>Nenhum resultado encontrado !!</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ["checkout_route"],
    data() {
        return {
            loading: false,
            page: 1,
            total: 0,
            result: {
                data: [],
                last_page: 0
            },
            initialized: false,
            attempts: 0,
        }
    },
    components: {
        "component-sumary": require("./-sumary.vue").default
    },
    watch: {
        page(val) {
            this.refresh(this.$parent.$refs.filter.filter)
        }
    },
    methods: {
        refresh(filter) {
            this.attempts++
            this.loading = true
            this.initialized = true
            this.$http.post("/admin/reports/customer-by-team/table", { page: this.page, ...filter }).then(resp => {
                resp = resp.data
                this.result = resp.data
                this.loading = false
                this.attempts = 0
                this.$refs.sumary.init()
            }).catch(er => {
                if (this.attempts <= 3) this.refresh(this.$parent.$refs.filter.filter)
                console.log(er)
            })
        }
    }
}
</script>