<template>
    <div class="row my-4">
        <div class="col-12">
            <div class="card f-12">
                <div class="card-header p-1 d-flex flex-row justify-content-between">
                    <div>
                        <span class="el-icon-s-opportunity mr-2" /> <b>Receita & Distribuição de Despesas Geral até {{ year.value }}</b>
                    </div>
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
    props: ['year', 'years'],
    data() {
        return {
            all_sections: {},
        }
    },
    computed: {
        last_years() {
            return this.years.filter((x) => Number(x.value) <= Number(this.year.value))
        },
        months() {
            return this.$store.getters['global/getMonths']
        },
        customer() {
            return this.$store.state.cash_planing.customer
        },
        values() {
            return {
                total: this.getEntries(),
                fixed: this.getExpense('fixed'),
                grow: this.getExpense('grow'),
                variable: this.getExpense('variable'),
            }
        },
        model_amount() {
            return this.values.total
        },
    },
    methods: {
        getExpense(type) {
            let total = this.last_years
                ?.map((year) => {
                    return year.sections
                        ?.map((section) => {
                            return section.expenses?.map((expense) => this.getMonthsSum(expense)).reduce((a, b) => a + b, 0)
                        })
                        .reduce((a, b) => a + b, 0)
                })
                .reduce((a, b) => a + b, 0)
            return total ? total : 0
        },
        getEntries() {
            let total = this.last_years
                ?.map((year) => year.entries?.map((entry) => this.getMonthsSum(entry)).reduce((a, b) => a + b, 0))
                .reduce((a, b) => a + b, 0)
            return total ? total : 0
        },
        getMonthsSum(arr) {
            let total = 0
            this.months.forEach((m) => (total += Number(arr[m.value])))
            return total
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