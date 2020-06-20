<template>
    <div>
        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-striped table-sm sheet mb-0 f-12 table-hover">
                        <thead>
                            <tr>
                                <th>Objetivo</th>
                                <th>Valor</th>
                                <th>Prazo</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-for="(g,i) in goals">
                                <tr :key="i" class="clickable">
                                    <td class="hoverable">
                                        <edit-input
                                            v-model="g.description"
                                            @change="deleteGoal(i)"
                                        />
                                    </td>
                                    <td class="hoverable">
                                        <edit-input
                                            type="number"
                                            v-model="g.value"
                                            :currency="true"
                                        />
                                    </td>
                                    <td class="hoverable">
                                        <edit-input v-model="g.term" />
                                    </td>
                                    <td class="hoverable">
                                        <edit-input
                                            v-model="g.term_type"
                                            type="select"
                                            :options="term_type_options"
                                        />
                                    </td>
                                    <td></td>
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
    props: ["customer"],
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
    watch: {
        goals: {
            handler(val) {
                this.saveGoals(val)
            },
            deep: true
        }
    },
    created() {
        this.refreshForm()
    },
    methods: {
        deleteGoal(i) {
            if (this.goals[i]) {
                if (!String(this.goals[i].description).trim()) {
                    this.goals.splice(i, 1)
                    this.$message.success('Objetivo excluido !!!')
                }
            }
        },
        refreshForm() {
            (Object.keys(this.default_form)).map(k => this.$set(this.form, k, this.default_form[k]))
        },
        appendGoal() {
            if (!this.form.description) return this.$message.error('Defina ao menos uma descrição')
            this.goals.push(Object.assign({}, this.form))
            this.refreshForm()
            this.adding = false
        },
        saveGoals(values) {
            this.loading_goals = true
            this.$http.post(`/admin/customers/${this.customer.code}/attendance/add-goal`, values).then(resp => {
                resp = resp.data
                this.loading_goals = false
            }).catch(er => {
                console.log(er)
                this.loading_goals = false
            })
        }

    }
}
</script>