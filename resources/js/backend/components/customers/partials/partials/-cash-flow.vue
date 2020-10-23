<template>
    <div class="row my-4">
        <div class="col-12">
            <div class="card f-12">
                <div class="card-header p-1 d-flex flex-row justify-content-between">
                    <div>
                        <span class="el-icon-s-opportunity mr-2" /> <b>Receita & Distribuição de Despesas Geral até {{ year.value }}</b>
                    </div>
                    <small class="f-12 text-muted" v-if="loading">... Atualizando Calculo</small>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-sm mb-0 f-12">
                            <thead>
                                <tr>
                                    <th class="text-center w-50" colspan="3">
                                        <b>Cenário Ideal</b>
                                    </th>
                                    <th class="text-center w-50" colspan="3">
                                        <b>Resultado & Comparação com Cenário Ideal</b>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="colored4">Receita</td>
                                    <td class="colored4">
                                        <template>{{ model_amount.currency() }}</template>
                                    </td>
                                    <td class="colored4">100%</td>
                                    <td class="bdl">
                                        <template>{{ model_amount.currency() }}</template>
                                    </td>
                                    <td>100%</td>
                                </tr>
                                <tr>
                                    <td class="flow fixed">Gastos Fixos</td>
                                    <td class="flow fixed">
                                        <template>{{ amoutByPercentage(50).currency() }}</template>
                                    </td>
                                    <td class="flow fixed">50%</td>
                                    <td class="bdl">
                                        <template>{{ getSumByType('fixed').currency() }}</template>
                                    </td>
                                    <td>
                                        <span v-html="percentageSumByType('fixed', 50)" />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="flow variable">Gastos Variáveis</td>
                                    <td class="flow variable">
                                        <template>{{ amoutByPercentage(30).currency() }}</template>
                                    </td>
                                    <td class="flow variable">30%</td>
                                    <td class="bdl">
                                        <template>{{ getSumByType('variable').currency() }}</template>
                                    </td>
                                    <td>
                                        <span v-html="percentageSumByType('variable', 30)" />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="flow grow">Investimento</td>
                                    <td class="flow grow">
                                        <template>{{ amoutByPercentage(20).currency() }}</template>
                                    </td>
                                    <td class="flow grow">20%</td>
                                    <td class="bdl">
                                        <template>{{ getSumByType('grow').currency() }}</template>
                                    </td>
                                    <td>
                                        <span v-html="percentageSumByType('grow', 20)" />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ['year', 'customer', 'sections'],
    data() {
        return {
            months: this.$getMoths(),
            all_sections: {},
            loading: true,
            attempts: 0,
            values: {
                total: 0,
                grow: 0,
                fixed: 0,
                entries: 0,
                variable: 0,
            },
        }
    },
    computed: {
        model_amount() {
            return this.values.total
        },
    },
    created() {
        this.loadData()
        setInterval(() => {
            this.loading = true
            setTimeout(() => {
                this.loadData()
            }, 2000)
        }, 10000)
    },
    methods: {
        loadData() {
            this.attempts++
            this.$http
                .post(`/admin/api/get-data/calcFluxTotal`, { customer_id: this.customer.id, year_id: this.year.id })
                .then((resp) => {
                    resp = resp.data
                    this.attempts = 0
                    this.$set(this.values, 'total', resp.total)
                    this.$set(this.values, 'fixed', resp.fixed)
                    this.$set(this.values, 'grow', resp.grow)
                    this.$set(this.values, 'variable', resp.variable)
                    this.loading = false
                })
                .catch((er) => {
                    if (this.attempts <= 3) return this.loadData()
                    return console.log(er)
                })
        },
        amoutByPercentage(percentage) {
            return ((this.model_amount * percentage) / 100).toFixed(2)
        },
        getSumByType(_type) {
            return this.values[_type]
        },
        percentageSumByType(_type, expected) {
            let base = Number(this.amoutByPercentage(expected))
            let sum = this.getSumByType(_type)
            let percentage = sum <= 0 ? 0 : (sum * 100) / base
            let is_red = _type != 'grow' ? percentage > expected : percentage < expected
            return `<span class="${is_red ? 'text-danger' : 'text-success'}">${percentage.toFixed(2)}%</span>`
        },
    },
}
</script>
<style lang="scss" scoped>
.flow {
    &.fixed {
        background-color: #a55656;
        color: white;
        border-bottom-color: black;
    }
    &.variable {
        background-color: #c6b387;
        color: white;
        border-bottom-color: black;
    }
    &.grow {
        background-color: #56587e;
        color: white;
        border-bottom-color: black;
    }
}
</style>