<template>
    <div class="row mb-2">
        <div class="col-12">
            <div class="card f-12">
                <div class="card-header p-1 d-flex flex-row align-items-center">
                    <span class="el-icon-remove mr-2"></span>
                    <div>{{s}}</div>
                    <a
                        href="#"
                        @click.prevent="deleteSection"
                        class="text-danger f-12 ml-auto"
                        v-if="!customer_area"
                    >
                        <span class="el-icon-error text-danger mr-2"></span>Excluir Sessão
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive" v-if="!loading_expenses">
                                <table class="table table-striped table-sm mb-0">
                                    <thead>
                                        <tr>
                                            <th style="width:350px">
                                                Conta
                                                <small>Mês</small>
                                            </th>
                                            <template v-for="(m,i) in months">
                                                <th
                                                    style="width:100px"
                                                    :key="`${i}_head`"
                                                    class="blue"
                                                >
                                                    {{ m.value }} /
                                                    <small>{{year}}</small>
                                                </th>
                                            </template>
                                            <th class="blue" v-if="!customer_area"></th>
                                        </tr>
                                        <tr>
                                            <th style="width:350px">
                                                Despesa
                                                <small>Consumo</small>
                                            </th>
                                            <template v-for="(m,i) in months">
                                                <th
                                                    style="width:150px"
                                                    class="f-10 blue2"
                                                    :key="`${i}_head_2`"
                                                >{{total(s,m.value).currency()}}</th>
                                            </template>
                                            <th class="blue2" v-if="!customer_area"></th>
                                        </tr>
                                        <tr>
                                            <th style="width:350px">
                                                <b>%</b>
                                                <small>Sobre a Receita</small>
                                            </th>
                                            <template v-for="(m,i) in months">
                                                <th
                                                    style="width:150px"
                                                    class="f-10 blue3"
                                                    :key="`${i}_head_3`"
                                                >{{percentage(s,m.value)}} %</th>
                                            </template>
                                            <th class="blue3" v-if="!customer_area"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(q,y) in sections[s]" :key="y">
                                            <td>
                                                <edit-input
                                                    v-model="q.name"
                                                    :can_edit="!customer_area"
                                                />
                                            </td>
                                            <template v-for="(m,i) in months">
                                                <td :key="`${i}_${y}_body`">
                                                    <edit-input
                                                        type="number"
                                                        v-model="q[m.value]"
                                                        :currency="true"
                                                        :can_edit="!customer_area"
                                                    />
                                                </td>
                                            </template>
                                            <td class="text-center" v-if="!customer_area">
                                                <button
                                                    v-loading="loading_expenses"
                                                    class="append-btn"
                                                    type="button"
                                                    @click="deleteExpense(s,y)"
                                                >
                                                    <span class="el-icon-error text-danger"></span>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr v-if="!customer_area">
                                            <td>
                                                <input class="w-100 mr-1" v-model="form.name" />
                                            </td>
                                            <template v-for="(m,i) in months">
                                                <td :key="`${i}_form`">
                                                    <input
                                                        class="w-100 mr-1"
                                                        v-model.number="form[m.value]"
                                                        type="number"
                                                        step="0.01"
                                                        @change="setAllValues(m.value)"
                                                    />
                                                </td>
                                            </template>
                                            <td class="text-center">
                                                <button
                                                    v-loading="loading_expenses"
                                                    class="append-btn"
                                                    type="button"
                                                    @click.prevent="addExpense(s)"
                                                >
                                                    <span class="el-icon-success text-success"></span>
                                                </button>
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
    </div>
</template>
<script>
export default {
    props: ["sections", "s", "months", "entries", "year", "customer", "customer_area"],
    data() {
        return {
            loading_expenses: false,
            form: {
                name: ""
            }
        }
    },
    created() {
        this.months.map(({ value }) => this.$set(this.form, value, 0))
    },
    methods: {
        deleteSection() {
            this.$confirm("Deseja excluir ?", "Confirmação", {
                confirmButtonText: "Sim",
                cancelButtonText: "Não",
                type: 'warning'
            }).then(() => {
                setTimeout(() => {
                    delete this.sections[this.s]
                    this.$parent.saveSections()
                    this.$message.success('Sessão excluida !!!')
                }, 500)
            })
        },
        percentage(label, month) {
            let total_entry = this.entries.reduce((a, b) => a + b[month], 0)
            let total_expense = this.sections[label].reduce((a, b) => a + b[month], 0)
            if (!total_entry || !total_expense) return 0
            return ((total_expense * 100) / total_entry).toFixed(2)
            return 0
        },
        total(label, month) {
            return this.sections[label].reduce((a, b) => a + b[month], 0)
        },
        addExpense(section) {
            if (!this.form.name) return this.$message.warning("De um nome a esta despesa !!")
            this.$set(this.sections[section], this.sections[section].length, Object.assign({}, this.form))
            let months_values = this.months.map(({ value }) => value)
            Object.keys(this.form).map(k => {
                if (months_values.includes(k)) return this.$set(this.form, k, 0)
                return this.$set(this.form, k, null)
            })
            this.$message.success("Despesa adicionado com sucesso !!")
        },
        setAllValues(month) {
            if (month == "jan") {
                let other_months = this.months.filter(({ value }) => value != "jan").map(({ value }) => value)
                let emptys = Object.keys(this.form).filter(k => {
                    if ((other_months.includes(k)) && (!this.form[k])) return true
                    return false
                })
                if (emptys.length == 11) return other_months.map(m => this.form[m] = this.form.jan)
            }
        },
        deleteExpense(section, y) {
            this.$confirm("Deseja excluir ?", "Confirmação", {
                confirmButtonText: "Sim",
                cancelButtonText: "Não",
                type: 'warning'
            }).then(() => {
                this.loading_expenses = true
                setTimeout(() => {
                    this.sections[section].splice(y, 1)
                    this.$message.success('Despesa excluido !!!')
                    this.loading_expenses = false
                }, 500)
            })
        },

    }
}
</script>