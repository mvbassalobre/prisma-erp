<template>
    <div
        class="tab-pane fade"
        v-bind:class="{'show active' : active == 'info'}"
        id="v-pills-info"
        role="tabpanel"
        aria-labelledby="v-pills-info-tab"
    >
        <div class="row">
            <div class="col-12">
                <div class="row" v-for="(card, i) in info.fields" :key="i">
                    <div class="col-12">
                        <div class="card mb-3" :id="`${card.label}`">
                            <div v-if="card.label" class="card-header">
                                <h5 v-html="card.label"></h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-striped mb-0">
                                            <tbody>
                                                <tr v-for="(field, i) in card.inputs" :key="i">
                                                    <td
                                                        style="width:25%;"
                                                        v-if="i.indexOf('IGNORE__')<0"
                                                    >
                                                        <span v-html="i"></span>
                                                    </td>
                                                    <td>
                                                        <v-runtime-template
                                                            :key="i"
                                                            :template="`<span>${field===null ? '' : field}</span>`"
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
                <div class="row goals">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5>Objetivos</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table
                                        class="table table-striped table-sm sheet table-bordered"
                                    >
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
                                            <tr v-for="(g,i) in goals" :key="i">
                                                <td class="text-center">
                                                    <span
                                                        class="el-icon-coin pt-1"
                                                        v-if="!goals[i].editing"
                                                    ></span>
                                                    <button
                                                        v-else
                                                        class="append-btn"
                                                        type="button"
                                                        @click.prevent="goals.splice(i,1)"
                                                    >
                                                        <span class="el-icon-error text-danger"></span>
                                                    </button>
                                                </td>
                                                <td>
                                                    <input
                                                        class="form-control form-control-sm"
                                                        v-model="goals[i].description"
                                                        :disabled="!goals[i].editing"
                                                    />
                                                </td>
                                                <td>
                                                    <input
                                                        class="form-control form-control-sm"
                                                        v-model="goals[i].value"
                                                        type="number"
                                                        step="0.01"
                                                        :disabled="!goals[i].editing"
                                                    />
                                                </td>
                                                <td>
                                                    <div class="d-flex flex-row">
                                                        <input
                                                            class="form-control form-control-sm mr-1"
                                                            v-model="goals[i].term"
                                                            type="number"
                                                            step="1"
                                                            :disabled="!goals[i].editing"
                                                        />
                                                        <select
                                                            class="form-control form-control-sm"
                                                            v-model="goals[i].term_type"
                                                            :disabled="!goals[i].editing"
                                                        >
                                                            <option value="Dia(s)">Dia(s)</option>
                                                            <option value="Mes(es)">Mes(es)</option>
                                                            <option value="Ano(s)">Ano(s)</option>
                                                        </select>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <button
                                                        v-if="!goals[i].editing"
                                                        class="append-btn"
                                                        type="button"
                                                        @click.prevent="startEdit(i)"
                                                    >
                                                        <span class="el-icon-edit text-info"></span>
                                                    </button>
                                                    <button
                                                        v-else
                                                        class="append-btn"
                                                        type="button"
                                                        @click.prevent="goals[i].editing = false"
                                                    >
                                                        <span class="el-icon-success text-success"></span>
                                                    </button>
                                                </td>
                                            </tr>

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
            </div>
        </div>
    </div>
</template>
<script>
import VRuntimeTemplate from "v-runtime-template"
export default {
    props: ["info", "active"],
    components: {
        "v-runtime-template": VRuntimeTemplate
    },
    data() {
        return {
            goals: [],
            default_form: {
                description: null,
                value: null,
                term_type: "Ano(s)",
                term: 1,
                editing: false
            },
            form: {}
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
        refreshForm() {
            (Object.keys(this.default_form)).map(k => this.$set(this.form, k, this.default_form[k]))
        },
        appendGoal() {
            if (this.isEditing) return this.$message.error('Confirme a edição para editar outra linha')
            this.goals.push(Object.assign({}, this.form))
            this.refreshForm()
        },
        startEdit(i) {
            if (this.isEditing) return this.$message.error('Confirme a edição para editar outra linha')
            this.goals[i].editing = true
        },
        saveGoals(values) {
            console.log("salva", goals)
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
    .sheet {
        .form-control {
            border: unset;
        }
    }
}
</style>