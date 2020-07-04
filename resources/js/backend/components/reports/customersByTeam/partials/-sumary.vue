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
                                <p class="f-12">
                                    <b>Quantidade :</b>
                                    {{result.total}}
                                </p>
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
            attempts: {
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
            this.$http.post("/admin/reports/customer-by-team/csv", { page: this.page, ...filter, paginate: false }).then(resp => {
                resp = resp.data
                let result = resp.csv
                let text = ""
                text = addToCsv(text, "Código")
                text = addToCsv(text, "Nome")
                result.forEach(row => {
                    text = addToCsv(text, row.code ? row.code : "")
                    text = addToCsv(text, row.name ? row.name : "")
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
            this.$http.post("/admin/reports/customer-by-team/team", { page: this.page, ...filter }).then(resp => {
                resp = resp.data
                this.chart_data_team = resp.chart_data
                this.attempts.team = 0
            }).catch(er => {
                if (this.attempts.team <= 3) this.getTeams()
                console.log(er)
            })
        },
        init() {
            this.getTeams()
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