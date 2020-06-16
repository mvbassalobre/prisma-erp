<template>
    <div class="row goals">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Objetivos</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-sm sheet mb-0">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Objetivo</th>
                                    <th>Valor</th>
                                    <th>Prazo</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="(g,i) in goals">
                                    <tr v-if="!goals[i].editing" :key="i">
                                        <td class="text-center">
                                            <span class="el-icon-coin pt-1"></span>
                                        </td>
                                        <td>{{g.description}}</td>
                                        <td>{{g.value}}</td>
                                        <td>
                                            <div class="d-flex flex-row">
                                                <div class="col-6">{{g.term}}</div>
                                                <div class="col-6">{{g.term_type}}</div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <button
                                                class="append-btn"
                                                type="button"
                                                @click.prevent="startEdit(i)"
                                            >
                                                <span class="el-icon-edit text-info"></span>
                                            </button>
                                        </td>
                                    </tr>

                                    <tr v-else :key="i">
                                        <td class="text-center">
                                            <button
                                                class="append-btn"
                                                type="button"
                                                @click.prevent="deleteGoal(i)"
                                            >
                                                <span class="el-icon-error text-danger"></span>
                                            </button>
                                        </td>
                                        <td>
                                            <input
                                                class="form-control form-control-sm"
                                                v-model="editing_form.description"
                                            />
                                        </td>
                                        <td>
                                            <input
                                                class="form-control form-control-sm"
                                                v-model="editing_form.value"
                                                type="number"
                                                step="0.01"
                                            />
                                        </td>
                                        <td>
                                            <div class="d-flex flex-row">
                                                <input
                                                    class="form-control form-control-sm mr-1"
                                                    v-model="editing_form.term"
                                                    type="number"
                                                    step="1"
                                                />
                                                <select
                                                    class="form-control form-control-sm"
                                                    v-model="editing_form.term_type"
                                                >
                                                    <option value="Dia(s)">Dia(s)</option>
                                                    <option value="Mes(es)">Mes(es)</option>
                                                    <option value="Ano(s)">Ano(s)</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <button
                                                v-loading="loading_goals"
                                                class="append-btn"
                                                type="button"
                                                @click.prevent="saveEditing(i)"
                                            >
                                                <span class="el-icon-success text-success"></span>
                                            </button>
                                        </td>
                                    </tr>
                                </template>

                                <tr>
                                    <td class="text-center">
                                        <span class="el-icon-coin pt-1"></span>
                                    </td>
                                    <td>
                                        <input
                                            class="form-control form-control-sm"
                                            v-model="form.description"
                                        />
                                    </td>
                                    <td>
                                        <input
                                            class="form-control form-control-sm"
                                            v-model="form.value"
                                            type="number"
                                            step="0.01"
                                        />
                                    </td>
                                    <td>
                                        <div class="d-flex flex-row">
                                            <input
                                                class="form-control form-control-sm mr-1"
                                                v-model="form.term"
                                                type="number"
                                                step="1"
                                            />
                                            <select
                                                class="form-control form-control-sm"
                                                v-model="form.term_type"
                                            >
                                                <option value="Dia(s)">Dia(s)</option>
                                                <option value="Mes(es)">Mes(es)</option>
                                                <option value="Ano(s)">Ano(s)</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <button
                                            v-loading="loading_goals"
                                            class="append-btn"
                                            type="button"
                                            @click.prevent="appendGoal"
                                        >
                                            <span class="el-icon-circle-plus"></span>
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
                editing: false
            },
            form: {},
            editing_form: {},
        }
    },
    watch: {
        goals: {
            handler(val) {
                if (!this.isEditing) this.saveGoals(val)
            },
            deep: true
        }
    },
    created() {
        this.refreshForm()
    },
    computed: {
        isEditing() {
            let res = this.goals.filter(g => g.editing)
            return (res.length > 0)
        }
    },
    methods: {
        deleteGoal(i) {
            this.$confirm("Você deseja mesmo excluir este objetivo ?", "Confirmação", {
                confirmButtonText: "Sim",
                cancelButtonText: "Não",
                type: 'warning'
            }).then(() => {
                this.goals.splice(i, 1)
            }).catch(er => {
                console.log(er)
            })
        },
        refreshForm() {
            (Object.keys(this.default_form)).map(k => this.$set(this.form, k, this.default_form[k]))
        },
        checkValidation(values) {
            if (!values.description) {
                this.$message.error('Descrição é obrigatorio')
                throw ("invalid form")
            }
            if (!values.value || values.value < 0) {
                this.$message.error('Valor inválido')
                throw ("invalid form")
            }
            if (!values.term) {
                this.$message.error('Prazo obrigatório')
                throw ("invalid form")
            }
        },
        saveEditing(i) {
            this.$confirm("Você deseja editar este objetivo ?", "Confirmação", {
                confirmButtonText: "Sim",
                cancelButtonText: "Não",
                type: 'warning'
            }).then(() => {
                this.checkValidation(this.editing_form)
                this.goals[i].description = this.editing_form.description
                this.goals[i].value = this.editing_form.value
                this.goals[i].term = this.editing_form.term
                this.goals[i].term_type = this.editing_form.term_type
                this.goals[i].editing = false
            })
        },
        appendGoal() {
            this.$confirm("Você deseja adicionar este objetivo ?", "Confirmação", {
                confirmButtonText: "Sim",
                cancelButtonText: "Não",
                type: 'warning'
            }).then(() => {
                if (this.isEditing) return this.$message.error('Confirme a edição para editar outra linha')
                this.checkValidation(this.form)
                this.goals.push(Object.assign({}, this.form))
                this.refreshForm()
            })
        },
        startEdit(i) {
            if (this.isEditing) return this.$message.error('Confirme a edição para editar outra linha')
            this.goals[i].editing = true
            this.editing_form = Object.assign({}, this.goals[i])
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
<style scoped lang="scss">
.goals {
    .append-btn {
        background-color: transparent;
        border: unset;
        padding: 10px;
        margin: 0;
        padding-top: 7px;
    }
}
</style>