<template>
    <div>
        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-striped table-sm sheet mb-0 f-12 table-hover">
                        <thead>
                            <tr>
                                <th class="purple">Objetivo</th>
                                <th class="purple">Valor</th>
                                <th class="purple">Prazo</th>
                                <th class="purple"></th>
                                <th class="purple"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-for="(g,i) in goals">
                                <tr :key="g.description" class="clickable">
                                    <td>
                                        <edit-input
                                            @change="changeGoal"
                                            v-model="g.description"
                                            :can_edit="true"
                                        />
                                    </td>
                                    <td>
                                        <edit-input
                                            type="number"
                                            v-model="g.value"
                                            @change="changeGoal"
                                            :currency="true"
                                            :can_edit="true"
                                        />
                                    </td>
                                    <td>
                                        <edit-input @change="changeGoal" v-model="g.term" />
                                    </td>
                                    <td>
                                        <edit-input
                                            v-model="g.term_type"
                                            type="select"
                                            :options="term_type_options"
                                            :can_edit="true"
                                            @change="changeGoal"
                                        />
                                    </td>
                                    <td class="text-center">
                                        <button
                                            v-loading="loading_goals"
                                            class="append-btn"
                                            type="button"
                                            @click.prevent="deleteGoal(i)"
                                        >
                                            <span class="el-icon-error text-danger"></span>
                                        </button>
                                    </td>
                                </tr>
                            </template>

                            <tr>
                                <td>
                                    <input class="w-100" v-model="form.description" />
                                </td>
                                <td>
                                    <currency-input
                                        class="w-100"
                                        currency="BRL"
                                        :auto-decimal-mode="true"
                                        v-model="form.value"
                                    />
                                </td>
                                <td>
                                    <input
                                        class="w-100 mr-1"
                                        v-model="form.term"
                                        type="number"
                                        step="1"
                                    />
                                </td>
                                <td>
                                    <select class="w-100" v-model="form.term_type">
                                        <option
                                            v-for="(o,i) in term_type_options"
                                            :value="o.value"
                                            v-html="o.value ? o.label : o.value"
                                            :key="i"
                                        />
                                    </select>
                                </td>
                                <td class="text-center">
                                    <button
                                        v-loading="loading_goals"
                                        class="append-btn"
                                        type="button"
                                        @click.prevent="appendGoal"
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
</template>
<script>
export default {
    props: ["customer", "customer_area"],
    data() {
        return {
            loading_goals: false,
            goals: this.customer.data ? (this.customer.data.goals ? this.customer.data.goals : []) : [],
            default_form: {
                description: null,
                value: null,
                term_type: "Ano(s)",
                term: 1,
            },
            form: {},
            term_type_options: [
                { value: 'Dia(s)', label: 'Dia(s)' },
                { value: 'Me(es)', label: 'Me(es)' },
                { value: 'Ano(s)', label: 'Ano(s)' },
            ]
        }
    },
    created() {
        this.refreshForm()
    },
    methods: {
        changeGoal() {
            this.saveGoals(this.goals)
        },
        deleteGoal(i) {
            this.$confirm("Deseja excluir ?", "Confirmação", {
                confirmButtonText: "Sim",
                cancelButtonText: "Não",
                type: 'warning'
            }).then(() => {
                this.goals.splice(i, 1)
                this.saveGoals(this.goals)
                this.$message.success('Objetivo excluido !!!')
            })
        },
        refreshForm() {
            (Object.keys(this.default_form)).map(k => this.$set(this.form, k, this.default_form[k]))
        },
        appendGoal() {
            if (!this.form.description) return this.$message.error('Defina ao menos uma descrição')
            this.goals.push(Object.assign({}, this.form))
            this.saveGoals(this.goals)
        },
        saveGoals(values) {
            this.$http.post(`/admin/customers/${this.customer.code}/attendance/add-goal`, values).then(resp => {
                resp = resp.data
                this.goals = resp.goals
                this.refreshForm()
            }).catch(er => {
                console.log(er)
            })
        }

    }
}
</script>