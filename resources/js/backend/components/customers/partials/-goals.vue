<template>
    <div>
        <!-- <div class="row mb-2">
            <div class="col-12 text-right">
                <span class="el-icon-circle-plus mr-2"></span>
                <a href="#" class="link" @click.prevent="startAdding">Adicionar Objetivo</a>
            </div>
        </div>-->
        <div class="row goals">
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
                                    <td class="hoverable" @dblclick="editField(i,'description')">
                                        <template
                                            v-if="(editing.index == i && editing.field =='description')"
                                        >
                                            <input
                                                :ref="`${i}_description`"
                                                class="w-100"
                                                :value="g.description"
                                                @keyup.enter="(e) => changeInput(e.target.value,i,'description')"
                                                @blur="(e) => changeInput(e.target.value,i,'description')"
                                            />
                                        </template>
                                        <template v-else>{{g.description}}</template>
                                    </td>
                                    <td class="hoverable" @dblclick="editField(i,'value',true)">
                                        <template
                                            v-if="(editing.index == i && editing.field =='value')"
                                        >
                                            <input
                                                :ref="`${i}_value`"
                                                class="w-100"
                                                :value="g.value"
                                                type="number"
                                                step="0.01"
                                                @keyup.enter="(e) => changeInput(e.target.value,i,'value')"
                                                @blur="(e) => changeInput(e.target.value,i,'value')"
                                            />
                                        </template>
                                        <template v-else>{{Number(g.value).currency()}}</template>
                                    </td>
                                    <td class="hoverable" @dblclick="editField(i,'term')">
                                        <template
                                            v-if="(editing.index == i && editing.field =='term')"
                                        >
                                            <input
                                                :ref="`${i}_term`"
                                                class="w-100"
                                                :value="g.term"
                                                type="number"
                                                step="1"
                                                @keyup.enter="(e) => changeInput(e.target.value,i,'term')"
                                                @blur="(e) => changeInput(e.target.value,i,'term')"
                                            />
                                        </template>
                                        <template v-else>{{g.term}}</template>
                                    </td>
                                    <td class="hoverable" @dblclick="editField(i,'term_type')">
                                        <template
                                            v-if="(editing.index == i && editing.field =='term_type')"
                                        >
                                            <select
                                                class="w-100"
                                                :ref="`${i}_term_type`"
                                                :value="g.term_type"
                                                @blur="(e) => changeInput(e.target.value,i,'term_type')"
                                            >
                                                <option value="Dia(s)">Dia(s)</option>
                                                <option value="Mes(es)">Mes(es)</option>
                                                <option value="Ano(s)">Ano(s)</option>
                                            </select>
                                        </template>
                                        <template v-else>{{g.term_type}}</template>
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
                                        <option value="Dia(s)">Dia(s)</option>
                                        <option value="Mes(es)">Mes(es)</option>
                                        <option value="Ano(s)">Ano(s)</option>
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
            // adding: false,
            loading_goals: false,
            goals: this.customer.data ? (this.customer.data.goals ? this.customer.data.goals : []) : [],
            default_form: {
                description: null,
                value: null,
                term_type: "Ano(s)",
                term: 1,
            },
            form: {},
            editing: {
                index: undefined,
                field: undefined,
            }
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
        changeInput(v, i, field) {
            this.goals[i][field] = v
            this.editing.index = undefined
            this.editing.field = undefined
            if (field == "description" && !v.trim()) this.deleteGoal(i)
        },
        editField(index, field) {
            this.editing.index = index
            this.editing.field = field
            this.$nextTick(() => {
                this.$refs[`${index}_${field}`][0].focus()
            })
        },
        deleteGoal(i) {
            this.goals.splice(i, 1)
        },
        refreshForm() {
            (Object.keys(this.default_form)).map(k => this.$set(this.form, k, this.default_form[k]))
        },
        saveEditing(i) {
            this.$confirm("Você deseja editar este objetivo ?", "Confirmação", {
                confirmButtonText: "Sim",
                cancelButtonText: "Não",
                type: 'warning'
            }).then(() => {
                this.goals[i].description = this.editing_form.description
                this.goals[i].value = this.editing_form.value
                this.goals[i].term = this.editing_form.term
                this.goals[i].term_type = this.editing_form.term_type
                this.goals[i].editing = false
            })
        },
        appendGoal() {
            if (!this.form.description) return this.$message.error('Defina ao menos uma descrição')
            this.goals.push(Object.assign({}, this.form))
            this.refreshForm()
            this.adding = false
        },
        startEdit(i) {
            return this.$message.error('Confirme a edição para editar outra linha')
            this.goals[i].editing = true
            this.editing_form = Object.assign({}, this.goals[i])
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
<style scoped lang="scss">
.goals {
    .append-btn {
        background-color: transparent;
        border: unset;
        padding: 5px;
        margin: 0;
        padding-top: 7px;
    }
}
</style>