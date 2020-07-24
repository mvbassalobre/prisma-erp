<template>
    <div class="row mb-2">
        <div class="col-12">
            <div :class="`card f-12 ${section.type}`">
                <div class="card-header p-1 d-flex flex-row align-items-center">
                    <span class="el-icon-remove mr-2"></span>
                    <div>
                        <edit-input v-model="section.name" :can_edit="true" @change="change" />
                    </div>
                    <div class="ml-2">
                        <select v-model="section.type" @change="change">
                            <option value="fixed">Gastos Fixos</option>
                            <option value="variable">Gastos Varíaveis</option>
                            <option value="grow">Investimento / Crescimento</option>
                        </select>
                    </div>
                    <a href="#" @click.prevent="deleteSection" class="text-danger f-12 ml-auto">
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
                                                    class="colored"
                                                >
                                                    {{ m.value }} /
                                                    <small>{{year.value}}</small>
                                                </th>
                                            </template>
                                            <th class="colored"></th>
                                        </tr>
                                        <tr>
                                            <th style="width:350px">
                                                <b>Receita</b>
                                                <small>Entradas</small>
                                            </th>
                                            <template v-for="(m,i) in months">
                                                <th
                                                    style="width:150px"
                                                    class="f-10 colored4"
                                                    :key="`${i}_head_3`"
                                                >{{entries(m.value).currency()}}</th>
                                            </template>
                                            <th class="colored4"></th>
                                        </tr>
                                        <tr>
                                            <th style="width:350px">
                                                Despesa
                                                <small>Consumo</small>
                                            </th>
                                            <template v-for="(m,i) in months">
                                                <th
                                                    style="width:150px"
                                                    class="f-10 colored2"
                                                    :key="`${i}_head_2`"
                                                >{{total(m.value).currency()}}</th>
                                            </template>
                                            <th class="colored2"></th>
                                        </tr>
                                        <tr>
                                            <th style="width:350px">
                                                <b>%</b>
                                                <small>Sobre a Receita</small>
                                            </th>
                                            <template v-for="(m,i) in months">
                                                <th
                                                    style="width:150px"
                                                    class="f-10 colored3"
                                                    :key="`${i}_head_3`"
                                                >{{percentage(m.value)}} %</th>
                                            </template>
                                            <th class="colored3"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(q,y) in section.expenses" :key="q.id">
                                            <td>
                                                <edit-input
                                                    @change="changeValue(q,m)"
                                                    v-model="q.name"
                                                />
                                            </td>
                                            <template v-for="(m,i) in months">
                                                <td :key="`${i}_${y}_body`">
                                                    <edit-input
                                                        type="number"
                                                        v-model="q[m.value]"
                                                        :currency="true"
                                                        @change="changeValue(q,m)"
                                                    />
                                                </td>
                                            </template>
                                            <td class="text-center">
                                                <button
                                                    v-loading="loading_expenses"
                                                    class="append-btn"
                                                    type="button"
                                                    @click="deleteExpense(q)"
                                                >
                                                    <span class="el-icon-error text-danger"></span>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
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
                                                        @change="setAllValues(m)"
                                                    />
                                                </td>
                                            </template>
                                            <td class="text-center">
                                                <button
                                                    v-loading="loading_expenses"
                                                    class="append-btn"
                                                    type="button"
                                                    @click.prevent="addExpense"
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
    props: ["section", "year", "customer", "customer_area"],
    data() {
        return {
            loading_expenses: true,
            attempts: 0,
            months: this.$getMoths(),
            form: {
                name: "",
            }
        }
    },
    watch: {
        type(val) {
            this.sections[this.s].map(row => this.$set(row, 'type', val))
        }
    },
    created() {
        this.months.map(({ value }) => this.$set(this.form, value, 0))
        this.init()
    },
    methods: {
        entries(m) {
            return this.year.entries.map(e => Number(e[m])).reduce((a, b) => a + b, 0)
        },
        init() {
            this.loadExpenses()
        },
        loadExpenses() {
            this.attempts++
            this.loading_expenses = true
            this.$http.post("/admin/api/get-data/sectionExpenses", this.section).then(resp => {
                resp = resp.data
                this.loading_expenses = false
                this.section.expenses = resp
            }).catch(er => {
                if (this.attempts <= 3) return this.loadExpenses()
                this.loading_expenses = false
                return console.log(er)
            })
        },
        change() {
            this.loading_expenses = true
            this.$http.put(`/admin/customers/${this.customer.code}/attendance/edit-section`, this.section).then(resp => {
                resp = resp.data
                this.section.name = resp.section.name
                this.loading_expenses = false
            }).catch(er => {
                console.log(er)
                this.loading_expenses = false
            })
        },
        changeValue(expense, current_month = null) {
            if (current_month) {
                let months = this.months.filter(m => m.number > current_month.number)
                months.forEach((month) => {
                    let value = expense[current_month.value]
                    let index = month.value
                    this.$set(expense, index, value)
                })
            }
            this.loading_expenses = true
            this.$http.put(`/admin/customers/${this.customer.code}/attendance/edit-expense`, expense).then(resp => {
                resp = resp.data
                this.$parent.sections = resp.sections
                this.loading_expenses = false
            }).catch(er => {
                console.log(er)
                this.loading_expenses = false
            })
        },
        deleteSection() {
            this.$confirm("Deseja excluir ?", "Confirmação", {
                confirmButtonText: "Sim",
                cancelButtonText: "Não",
                type: 'warning'
            }).then(() => {
                this.loading_expenses = true
                this.$http.delete(`/admin/customers/attendance/delete-section/${this.section.id}`).then(resp => {
                    resp = resp.data
                    this.$parent.sections = resp.sections
                    this.loading_expenses = false
                }).catch(er => {
                    console.log(er)
                    this.loading_expenses = false
                })
            })
        },
        percentage(month) {
            let total_entry = this.year.entries.map(e => Number(e[month])).reduce((a, b) => a + b, 0)
            let total_expense = this.total(month)
            if (!total_entry || !total_expense) return 0
            return ((total_expense * 100) / total_entry).toFixed(2)
            return 0
        },
        total(month) {
            return this.section.expenses.map(e => Number(e[month])).reduce((a, b) => a + b, 0)
        },
        refreshForm() {
            this.form.name = null
            let months_values = this.months.map(({ value }) => value)
            Object.keys(this.form).map(k => {
                if (months_values.includes(k)) return this.$set(this.form, k, 0)
                return this.$set(this.form, k, null)
            })
        },
        addExpense(section) {
            if (!this.form.name) return this.$message.warning("De um nome a esta despesa !!")
            this.$http.post(`/admin/customers/${this.customer.code}/attendance/add-expense`, { ...this.form, section_id: this.section.id }).then(resp => {
                resp = resp.data
                this.section.expenses = resp.expenses
                this.refreshForm()
            }).catch(er => {
                console.log(er)
            })
        },
        setAllValues(current_month) {
            this.months.forEach((month) => {
                if (month.number > current_month.number) this.form[month.value] = this.form[current_month.value]
            })
        },
        deleteExpense(expense) {
            this.$confirm("Deseja excluir ?", "Confirmação", {
                confirmButtonText: "Sim",
                cancelButtonText: "Não",
                type: 'warning'
            }).then(() => {
                this.$http.delete(`/admin/customers/attendance/delete-expense/${expense.id}`).then(resp => {
                    resp = resp.data
                    this.$parent.sections = resp.sections
                    this.refreshForm()
                }).catch(er => {
                    console.log(er)
                })
            })
        },

    }
}
</script>
<style lang="scss" scoped>
.card {
    &.fixed {
        .colored {
            background-color: #a55656;
            color: white;
            border-bottom-color: black;
        }
        .colored2 {
            background-color: #b08181;
            color: white;
        }
        .colored3 {
            background-color: #ffd7d7;
            color: black;
            border-bottom-color: #a9a9b4;
        }
    }
    &.variable {
        .colored {
            background-color: #8b7542;
            color: white;
            border-bottom-color: black;
        }
        .colored2 {
            background-color: #c6b387;
            color: white;
        }
        .colored3 {
            background-color: #ebe2cc;
            color: black;
            border-bottom-color: #a9a9b4;
        }
    }
    &.grow {
        .colored {
            background-color: #56587e;
            color: white;
            border-bottom-color: black;
        }
        .colored2 {
            background-color: #7f81b4;
            color: white;
        }
        .colored3 {
            background-color: #a9a9b4;
            color: white;
            border-bottom-color: #a9a9b4;
        }
    }
}
</style>