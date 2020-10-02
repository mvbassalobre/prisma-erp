<template>
    <div class="row my-4">
        <div class="col-12">
            <div class="card f-12">
                <div class="card-header p-1">
                    <span class="el-icon-s-opportunity mr-2" /> <b>Receita & Distribuição de Despesas Anual</b>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-sm mb-0 f-12">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th
                                        class="text-center"
                                        colspan="2"
                                    >
                                        <b>Cenário Ideal</b>
                                    </th>
                                    <th
                                        class="text-center"
                                        colspan="3"
                                    >
                                        <b>Resultado & Comparação com Cenário Ideal</b>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="colored4">Receita</td>
                                    <td class="colored4">{{model_amount.currency()}}</td>
                                    <td class="colored4">100%</td>
                                    <td class="bdl">{{model_amount.currency()}}</td>
                                    <td>100%</td>
                                </tr>
                                <tr>
                                    <td class="flow fixed">Gastos Fixos</td>
                                    <td class="flow fixed">{{amoutByPercentage(50).currency()}}</td>
                                    <td class="flow fixed">50%</td>
                                    <td class="bdl">{{getSumByType('fixed').currency()}}</td>
                                    <td v-html="percentageSumByType('fixed',50)" />
                                </tr>
                                <tr>
                                    <td class="flow variable">Gastos Variáveis</td>
                                    <td class="flow variable">{{amoutByPercentage(30).currency()}}</td>
                                    <td class="flow variable">30%</td>
                                    <td class="bdl">{{getSumByType('variable').currency()}}</td>
                                    <td v-html="percentageSumByType('variable',30)" />
                                </tr>
                                <tr>
                                    <td class="flow grow">Investimento</td>
                                    <td class="flow grow">{{amoutByPercentage(20).currency()}}</td>
                                    <td class="flow grow">20%</td>
                                    <td class="bdl">{{getSumByType('grow').currency()}}</td>
                                    <td v-html="percentageSumByType('grow',20)" />
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
    props: ["year", "sections"],
    data() {
        return {
            months: this.$getMoths(),
        }
    },
    computed: {
        model_amount() {
            let sum = this.year.entries.map(row => {
                let value = 0
                this.months.forEach(month => {
                    value += Number(row[month.value])
                })
                return value
            }).reduce((a, b) => a + b, 0)
            return sum
        }
    },
    methods: {
        entries(month) {
            if (this.year.entries.length <= 0) return
            return this.year.entries.map(e => Number(e[month.value])).reduce((a, b) => a + b, 0)
        },
        amoutByPercentage(percentage) {
            return ((this.model_amount * percentage) / 100).toFixed(2)
        },
        getSumByType(_type) {
            let _sum = this.sections.filter(x => x.type == _type).map(section => {
                let sum = 0
                section.expenses.forEach(row => {
                    sum += this.months.map(m => Number(row[m.value])).reduce((a, b) => a + b, 0)
                })
                return sum
            })
            return _sum.reduce((a, b) => a + b, 0)
        },
        percentageSumByType(_type, expected) {
            let base = Number(this.amoutByPercentage(expected))
            let percentage = (this.getSumByType(_type) * 100) / base
            let is_red = _type != 'grow' ? percentage > expected : percentage < expected
            return `<span class="${is_red ? 'text-danger' : 'text-success'}">${percentage.toFixed(2)}%</span>`
        },
        cash(month) {
            if (this.sections.length <= 0) return 0
            return this.sections.map(sec => sec.expenses.map(x => Number(x[month.value])).reduce((a, b) => a + b, 0)).reduce((a, b) => a + b, 0)
        },
        income(month) {
            if (this.year.entries <= 0) return 0
            return (this.year.entries.map(e => Number(e[month.value])).reduce((a, b) => a + b, 0))
        },
        patrimony(month) {
            let cash = this.cash(month)
            let entries = this.income(month)
            let rest = 0
            let result = this.months.filter(m => m.number <= month.number).map(x => {
                let _cash = this.cash(x)
                let _entries = this.income(x)
                return _entries - _cash
            })
            return result.reduce((a, b) => a + b, 0)
        }
    }
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