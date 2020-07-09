<template>
    <div>
        <div class="row my-4">
            <div class="col-12">
                <div class="card f-12">
                    <div class="card-header p-1">
                        <span class="el-icon-info mr-2"></span> Resumo
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-sm mb-0 f-12 table-hover">
                                <thead>
                                    <tr>
                                        <th style="width:350px">
                                            Título /
                                            <small>Ano</small>
                                        </th>
                                        <template v-for="(m,i) in months">
                                            <th
                                                style="width:100px"
                                                :key="`${i}_head`"
                                                class="orange"
                                            >
                                                {{ m.value }} /
                                                <small>{{year}}</small>
                                            </th>
                                        </template>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <b>Geração de Caixa</b>
                                            <small>Despesas</small>
                                        </td>
                                        <template v-for="(m,i) in months">
                                            <td
                                                style="width:100px"
                                                :key="`${i}_body`"
                                                class="orange2"
                                            >{{(cash(m)).currency()}}</td>
                                        </template>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Saldo</b>
                                            <small>Reserva</small>
                                        </td>
                                        <template v-for="(m,i) in months">
                                            <td
                                                style="width:100px"
                                                :key="`${i}_body`"
                                                class="green4"
                                            >{{(patrimony(m)).currency()}}</td>
                                        </template>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row my-4">
            <div class="col-12">
                <div class="card f-12">
                    <div class="card-header p-1">
                        <span class="el-icon-s-opportunity mr-2"></span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-sm mb-0 f-12 table-hover">
                                <thead>
                                    <tr>
                                        <th colspan="3">
                                            <b>Base</b>
                                        </th>
                                        <th class="bdl" colspan="3">
                                            <b>Atual</b>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Receita</td>
                                        <td>{{model_amount.currency()}}</td>
                                        <td>100%</td>
                                        <td class="bdl">{{model_amount.currency()}}</td>
                                        <td>100%</td>
                                    </tr>
                                    <tr>
                                        <td>Gastos Fixos</td>
                                        <td>{{amoutByPercentage(50).currency()}}</td>
                                        <td>50%</td>
                                        <td class="bdl">{{getSumByType('fixed').currency()}}</td>
                                        <td v-html="percentageSumByType('fixed',50)" />
                                    </tr>
                                    <tr>
                                        <td>Gastos Variáveis</td>
                                        <td>{{amoutByPercentage(30).currency()}}</td>
                                        <td>30%</td>
                                        <td class="bdl">{{getSumByType('variable').currency()}}</td>
                                        <td v-html="percentageSumByType('variable',30)" />
                                    </tr>
                                    <tr>
                                        <td>Investimento / Crescimento</td>
                                        <td>{{amoutByPercentage(20).currency()}}</td>
                                        <td>20%</td>
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
    </div>
</template>
<script>
export default {
    props: ["months", "year", "sections", "entries"],
    computed: {
        model_amount() {
            let sum = this.entries.map(row => {
                let value = 0
                this.months.forEach(month => {
                    value += row[month.value]
                })
                return value
            }).reduce((a, b) => a + b, 0)
            return sum
        }
    },
    methods: {
        amoutByPercentage(percentage) {
            return ((this.model_amount * percentage) / 100).toFixed(2)
        },
        getSumByType(_type) {
            let _sum = Object.keys(this.sections).map(section => {
                let sum = 0
                this.sections[section].forEach(row => {
                    if (row.type == _type) sum += this.months.map(m => row[m.value]).reduce((a, b) => a + b, 0)
                })
                return sum
            })
            return _sum.reduce((a, b) => a + b, 0)
        },
        percentageSumByType(_type, expected) {
            let base = Number(this.amoutByPercentage(expected))
            let percentage = (this.getSumByType(_type) * 100) / base
            let is_red = percentage > expected
            return `<span class="${is_red ? 'text-danger' : 'text-success'}">${percentage.toFixed(2)}%</span>`
        },
        cash(month) {
            let result = Object.keys(this.sections).map(sec => {
                return this.sections[sec].map(x => x[month.value]).reduce((a, b) => a + b, 0)
            }).reduce((a, b) => a + b, 0)
            return result
        },
        income(month) {
            return this.entries.map(x => x[month.value]).reduce((a, b) => a + b, 0)
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