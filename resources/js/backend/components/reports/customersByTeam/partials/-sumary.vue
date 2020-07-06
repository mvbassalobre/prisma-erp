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
                                    suffix=" clientes(s)"
                                />
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <pie-chart
                                    :donut="true"
                                    legend="top"
                                    :discrete="true"
                                    height="200px"
                                    :data="chart_data_user"
                                    suffix=" clientes(s)"
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
            report_name: "Relatório de Clientes por Time",
            total: 0,
            chart_data_team: [],
            chart_data_user: [],
            attempts: {
                team: 0,
                user: 0,
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
            this.$http.post("/admin/reports/get-data-customers/csv", { page: this.page, ...filter, paginate: false }).then(resp => {
                resp = resp.data
                let result = resp.csv
                let text = ""
                text = addToCsv(text, "Código")
                text = addToCsv(text, "Nome")
                text = addToCsv(text, "Email")
                text = addToCsv(text, "Time")
                text = addToCsv(text, "Tefefone")
                text = addToCsv(text, "Celular")
                text = addToCsv(text, "Data de Criação")
                text = addToCsv(text, "Última Atualização", true)
                result.forEach(row => {
                    text = addToCsv(text, row.code ? row.code : "")
                    text = addToCsv(text, row.name ? row.name : "")
                    text = addToCsv(text, row.email ? row.email : "")
                    text = addToCsv(text, row.team_name ? row.team_name : "")
                    text = addToCsv(text, row.phone ? row.phone : "")
                    text = addToCsv(text, row.cellphone ? row.cellphone : "")
                    text = addToCsv(text, row.f_created_at ? row.f_created_at : "")
                    text = addToCsv(text, row.last_update ? row.last_update : "", true)
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
            this.$http.post("/admin/reports/get-data-customers/team", { page: this.page, ...filter }).then(resp => {
                resp = resp.data
                this.chart_data_team = resp.chart_data
                this.attempts.team = 0
            }).catch(er => {
                if (this.attempts.team <= 3) this.getTeams()
                console.log(er)
            })
        },
        getUsers() {
            this.attempts.user++
            let filter = this.$parent.$parent.$refs.filter.filter
            this.$http.post("/admin/reports/get-data-customers/user", { page: this.page, ...filter }).then(resp => {
                resp = resp.data
                this.chart_data_user = resp.chart_data
                this.attempts.user = 0
            }).catch(er => {
                if (this.attempts.user <= 3) this.getUsers()
                console.log(er)
            })
        },
        init() {
            this.getTeams()
            this.getUsers()
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