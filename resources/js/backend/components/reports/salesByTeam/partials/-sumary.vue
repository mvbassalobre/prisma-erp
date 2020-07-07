<template>
    <div>
        <div class="row mt-3" v-if="result.data.length>0" v-loading="loading">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <b class="f-12">
                            <span class="el-icon-menu mr-2"></span>
                            Resumo
                        </b>
                        <div class="d-flex flex-row f-12">
                            <span>Exportar para :</span>
                            <a href="#" class="link-badge" @click.prevent="exportCSV">CSV</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 col-sm-12">
                                <h2>
                                    <p>
                                        <b>Total :</b>
                                        {{result.total}}
                                    </p>
                                </h2>
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <pie-chart
                                    :donut="true"
                                    legend="top"
                                    :discrete="true"
                                    height="200px"
                                    :data="chart_data_team"
                                    suffix=" vendas(s)"
                                />
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <pie-chart
                                    :donut="true"
                                    legend="top"
                                    :discrete="true"
                                    height="200px"
                                    :data="chart_data_status"
                                    suffix=" vendas(s)"
                                />
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
    props: ["result", "loading"],
    data() {
        return {
            report_name: "Relatório de Vendas por Time",
            total: 0,
            chart_data_team: [],
            chart_data_user: [],
            chart_data_status: [],
            attempts: {
                status: 0,
                team: 0,
                csv: 0
            }
        }
    },
    created() {
        this.init()
    },
    methods: {
        exportCSV() {
            this.attempts.csv++
            let loading = this.$loading({ text: "Gerando CSV, aguarde ..." })
            let filter = this.$parent.$parent.$refs.filter.filter
            this.$http.post("/admin/reports/get-data-sales/csv", { page: this.page, ...filter, paginate: false }).then(resp => {
                resp = resp.data
                let result = resp.csv
                let text = ""
                text = addToCsv(text, "Código")
                text = addToCsv(text, "Cliente")
                text = addToCsv(text, "Pagto")
                text = addToCsv(text, "items")
                text = addToCsv(text, "Responsável")
                text = addToCsv(text, "Time")
                text = addToCsv(text, "SubTotal")
                text = addToCsv(text, "Data Lançamento", true)
                result.forEach(row => {
                    text = addToCsv(text, row.code ? row.code : "")
                    text = addToCsv(text, row.customer_name ? row.customer_name : "")
                    text = addToCsv(text, row.user_name ? row.user_name : "")
                    text = addToCsv(text, row.status_payment ? row.status_payment : "")
                    text = addToCsv(text, row.items ? this.itemsRow(row.items) : "")
                    text = addToCsv(text, row.team_name ? row.team_name : "")
                    text = addToCsv(text, row.subtotal ? row.subtotal : "")
                    text = addToCsv(text, row.f_created_at ? row.f_created_at : "", true)
                })
                makeTextFile(text, `${this.report_name} - ${(new Date()).toLocaleDateString()}.csv`)
                this.attempts.csv = 0
                loading.close()
            }).catch(er => {
                if (this.attempts.csv <= 3) this.exportCSV()
                console.log(er)
            })
        },
        getTeams() {
            this.attempts.team++
            let filter = this.$parent.$parent.$refs.filter.filter
            this.$http.post("/admin/reports/get-data-sales/team", { page: this.page, ...filter }).then(resp => {
                resp = resp.data
                this.chart_data_team = resp.chart_data
                this.attempts.team = 0
            }).catch(er => {
                if (this.attempts.team <= 3) this.getTeams()
                console.log(er)
            })
        },
        getStatus() {
            this.attempts.status++
            let filter = this.$parent.$parent.$refs.filter.filter
            this.$http.post("/admin/reports/get-data-sales/status", { page: this.page, ...filter }).then(resp => {
                resp = resp.data
                this.chart_data_status = resp.chart_data
                this.attempts.status = 0
            }).catch(er => {
                if (this.attempts.status <= 3) this.getStatus()
                console.log(er)
            })
        },
        itemsRow(items) {
            items = JSON.parse(items)
            let text = ''
            items.forEach(i => text += `${i.name} ${i.price.currency()} x ${i.qty} = ${i.total.currency()},`)
            return text.slice(0, -1)
        },
        init() {
            this.getTeams()
            this.getStatus()
        }
    }
}
</script>
<style lang="scss" scoped>
.link-badge {
    margin-left: 5px;
    background-color: green;
    padding-left: 5px;
    font-size: 12px;
    padding-right: 5px;
    border-radius: 5px;
    color: white;
    &:hover {
        text-decoration: unset;
    }
}
</style>