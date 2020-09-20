<template>
    <div class="row d-flex flex-row justify-content-center">
        <div class="col-md-6 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <b class="f-12">
                        <span class="el-icon-s-custom mr-2" />Selecione o cliente que deseja efetuar o lançamento
                    </b>
                </div>
                <div class="card-body p-0" v-loading="loading">
                    <div class="row">
                        <div class="col-12">
                            <table class="table table-striped table-sm mb-0">
                                <tbody>
                                    <tr>
                                        <td class="w-25 text-center pt-2">Cliente</td>
                                        <td class="w-75">
                                            <el-select
                                                class="w-100"
                                                v-model="form.customer_code"
                                                placeholder="Selecione o cliente ..."
                                                filterable
                                            >
                                                <el-option
                                                    v-for="status in modelsData.customers"
                                                    :key="status.id"
                                                    :value="status.code"
                                                    :label="status.name"
                                                />
                                            </el-select>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer px-0" v-if="form.customer_code">
                    <div class="d-flex flex-row justify-content-end">
                        <div class="col-md-3 col-sm-12 pr-2">
                            <a
                                class="btn btn-primary btn-block text-white"
                                :href="`/admin/customers/${form.customer_code}/attendance#sales`"
                            >Ir para lançamentos</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            loading: true,
            form: {
                customer_code: ""
            },
            modelsData: {
                meetingRooms: [],
                meetingStatuses: [],
                customers: []
            },
            attempts: {
                meetingRooms: 0,
                meetingStatuses: 0,
                customers: 0
            },
        }
    },
    created() {
        this.loadCustomers()
    },
    methods: {
        loadCustomers() {
            this.attempts.customers++
            this.$http.post("/admin/inputs/option_list", { model: `App\\Http\\Models\\Customer` }).then(r => {
                this.loading = false
                this.modelsData.customers = r.data.data
            }).catch(er => {
                if (this.attempts.customers <= 3) return this.loadCustomers()
                console.log(er)
            })
        },
    }
}
</script>