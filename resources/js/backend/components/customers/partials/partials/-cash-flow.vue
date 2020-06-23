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
                                            <b>Geração de Patrimônio</b>
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
    </div>
</template>
<script>
export default {
    props: ["months", "year", "sections", "entries"],
    methods: {
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