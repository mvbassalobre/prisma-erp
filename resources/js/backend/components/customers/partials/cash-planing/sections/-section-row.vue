<template>
    <div class="mt-4">
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
                                <div class="table-responsive">
                                    <table class="table table-striped table-sm mb-0">
                                        <thead>
                                            <tr>
                                                <th class="w-25">
                                                    Conta
                                                    <small>Mês</small>
                                                </th>
                                                <template v-for="(m, i) in months">
                                                    <th style="width: 100px" :key="`${i}_head`" class="colored">
                                                        {{ m.value }} /
                                                        <small>{{ year.value }}</small>
                                                    </th>
                                                </template>
                                                <th class="colored"></th>
                                            </tr>
                                            <tr>
                                                <th class="w-25">
                                                    Despesa
                                                    <small>Consumo</small>
                                                </th>
                                                <template v-for="(m, i) in months">
                                                    <th style="width: 150px" class="f-10 colored2" :key="`${i}_head_2`">{{ total(m.value).currency() }}</th>
                                                </template>
                                                <th class="colored2"></th>
                                            </tr>
                                            <tr>
                                                <th class="w-25">
                                                    <b>%</b>
                                                    <small>Sobre a Receita</small>
                                                </th>
                                                <template v-for="(m, i) in months">
                                                    <th style="width: 150px" class="f-10 colored3" :key="`${i}_head_3`">{{ percentage(m.value) }} %</th>
                                                </template>
                                                <th class="colored3"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr
                                                is="expense-row"
                                                v-for="expense in section.expenses"
                                                :key="expense.id"
                                                :expense="expense"
                                                :months="months"
                                                :year="year"
                                            />
                                            <tr>
                                                <td>
                                                    <input class="w-100 mr-1" v-model="form.name" />
                                                </td>
                                                <template v-for="(m, i) in months">
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
                                                    <button class="append-btn" type="button" @click.prevent="addExpense">
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
    </div>
</template>
<script>
import ExpenseRow from './-expense-row.vue'
export default {
    props: ['section', 'year'],
    data() {
        return {
            form: {
                name: '',
            },
        }
    },
    components: {
        ExpenseRow,
    },
    computed: {
        months() {
            return this.$store.getters['global/getMonths']
        },
    },
    methods: {
        addExpense() {
            if (!this.form.name) return this.$message.warning('De um nome a esta despesa !!')
            this.$store.dispatch('cash_planing/addExpense', {
                year_id: this.year.id,
                expense: this.form,
                section_id: this.section.id,
                callback: () => {
                    this.refreshForm()
                },
            })
        },
        setAllValues(current_month) {
            this.months.forEach((month) => {
                if (month.number > current_month.number) this.form[month.value] = this.form[current_month.value]
            })
        },
        percentage(month) {
            if (!Array.isArray(this.year.entries)) return 0
            let total_entry = this.year.entries.map((e) => Number(e[month])).reduce((a, b) => a + b, 0)
            let total_expense = this.total(month)
            if (!total_entry || !total_expense) return 0
            return ((total_expense * 100) / total_entry).toFixed(2)
            return 0
        },
        total(month) {
            if (!Array.isArray(this.section.expenses)) return 0
            return this.section.expenses.map((e) => Number(e[month])).reduce((a, b) => a + b, 0)
        },
        refreshForm() {
            this.form.name = null
            let months_values = this.months.map(({ value }) => value)
            Object.keys(this.form).map((k) => {
                if (months_values.includes(k)) return this.$set(this.form, k, 0)
                return this.$set(this.form, k, null)
            })
        },
        change() {
            this.$store.dispatch('cash_planing/editSection', {
                year_id: this.year.id,
                section: this.section,
            })
        },
        deleteSection() {
            this.$confirm('Deseja excluir ?', 'Confirmação', {
                confirmButtonText: 'Sim',
                cancelButtonText: 'Não',
                type: 'warning',
            }).then(() => {
                this.$store.dispatch('cash_planing/deleteSection', {
                    year_id: this.year.id,
                    section_id: this.section.id,
                })
            })
        },
    },
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