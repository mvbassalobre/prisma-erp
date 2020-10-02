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
                            <table class="table table-striped table-sm mb-0 f-12">
                                <thead>
                                    <tr>
                                        <th class="w-25">
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
                                                <small>{{year.value}}</small>
                                            </th>
                                        </template>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <b>Entradas</b>
                                        </td>
                                        <template v-for="(m,i) in months">
                                            <td
                                                style="width:100px;background-color:#56587e;color:white;"
                                                :key="`${i}_body`"
                                            >{{(entries(m)).currency()}}</td>
                                        </template>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Consumo</b>
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
                                            <b>Geração de Caixa</b>
                                        </td>
                                        <template v-for="(m,i) in months">
                                            <td
                                                style="width:100px"
                                                :key="`${i}_body`"
                                                class="pink"
                                            >{{(entries(m) - cash(m)).currency()}}</td>
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
                        <span class="el-icon-s-opportunity mr-2" /><b>Receita & Distribuição de Despesas Mensal</b>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-sm mb-0 f-12">
                                <thead>
                                    <tr>
                                        <th class="w-25" />
                                        <template v-for="(m,i) in months">
                                            <th
                                                style="width:100px"
                                                :key="`${i}_head`"
                                                class="orange"
                                            >
                                                {{ m.value }} /
                                                <small>{{year.value}}</small>
                                            </th>
                                        </template>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td
                                            class="colored4"
                                            colspan="13"
                                        >
                                            <b>Receita</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="bdr"><b>Alcançado </b>/<small>Mês</small></td>
                                        <td
                                            v-for="(m,i) in months"
                                            :key="`${i}_head`"
                                        >
                                            {{model_amount(m).currency()}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="13" />
                                    </tr>
                                    <tr>
                                        <td
                                            class="flow fixed"
                                            colspan="13"
                                        >
                                            <b>Gastos Fixos</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="bdr"><b>Meta </b>/<small>Mês</small></td>
                                        <td
                                            v-for="(m,i) in months"
                                            :key="`${i}_head`"
                                        >
                                            {{amoutByPercentage(50,m).currency()}}
                                            <b class="ml-1 text-success">50%</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="bdr"><b>Alcançado </b>/<small>Mês</small></td>
                                        <td
                                            v-for="(m,i) in months"
                                            :key="`${i}_head`"
                                        >
                                            {{getSumByType('fixed',m).currency()}}
                                            <b
                                                class="ml-1"
                                                v-html="percentageSumByType('fixed',m,50)"
                                            />
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="13" />
                                    </tr>
                                    <tr>
                                        <td
                                            class="flow variable"
                                            colspan="13"
                                        >
                                            <b>Gastos Variáveis</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="bdr"><b>Meta</b>/<small>Mês</small></td>
                                        <td
                                            v-for="(m,i) in months"
                                            :key="`${i}_head`"
                                        >
                                            {{amoutByPercentage(30,m).currency()}}
                                            <b class="ml-1 text-success">30%</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="bdr"><b>Alcançado </b>/<small>Mês</small></td>
                                        <td
                                            v-for="(m,i) in months"
                                            :key="`${i}_head`"
                                        >
                                            {{getSumByType('variable',m).currency()}}
                                            <b
                                                class="ml-1"
                                                v-html="percentageSumByType('variable',m,50)"
                                            />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="13" />
                                    </tr>
                                    <tr>
                                        <td
                                            class="flow grow"
                                            colspan="13"
                                        >
                                            <b>Investimentos</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="bdr"><b>Meta </b>/<small>Mês</small></td>
                                        <td
                                            v-for="(m,i) in months"
                                            :key="`${i}_head`"
                                        >
                                            {{amoutByPercentage(20,m).currency()}}
                                            <b class="ml-1 text-success">20%</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="bdr"><b>Alcançado </b>/<small>Mês</small></td>
                                        <td
                                            v-for="(m,i) in months"
                                            :key="`${i}_head`"
                                        >
                                            {{getSumByType('grow',m).currency()}}
                                            <b
                                                class="ml-1"
                                                v-html="percentageSumByType('grow',m,50)"
                                            />
                                        </td>
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
    props: ["year", "sections"],
    data() {
        return {
            months: this.$getMoths(),
        }
    },
    methods: {
        model_amount(month) {
            let sum = this.year.entries.map(row => Number(row[month.value])).reduce((a, b) => a + b, 0)
            return sum
        },
        entries(month) {
            if (this.year.entries.length <= 0) return
            return this.year.entries.map(e => Number(e[month.value])).reduce((a, b) => a + b, 0)
        },
        amoutByPercentage(percentage, month) {
            return ((this.model_amount(month) * percentage) / 100).toFixed(2)
        },
        getSumByType(_type, month) {
            let _sum = this.sections.filter(x => x.type == _type).map(section => {
                let sum = 0
                section.expenses.forEach(row => sum += Number(row[month.value]))
                return sum
            })
            return _sum.reduce((a, b) => a + b, 0)
        },
        percentageSumByType(_type, month, expected) {
            let base = Number(this.amoutByPercentage(expected, month))
            let percentage = (this.getSumByType(_type, month) * 100) / base
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