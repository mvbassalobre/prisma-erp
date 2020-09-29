<template>
    <div v-loading="loading_goals">
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
                            <template v-for="g in goals">
                                <tr
                                    :key="g.id"
                                    class="clickable"
                                >
                                    <td>
                                        <edit-input
                                            @change="changeGoal(g)"
                                            v-model="g.description"
                                            :can_edit="true"
                                        />
                                    </td>
                                    <td>
                                        <edit-input
                                            type="number"
                                            v-model="g.value"
                                            @change="changeGoal(g)"
                                            :currency="true"
                                            :can_edit="true"
                                        />
                                    </td>
                                    <td>
                                        <edit-input
                                            @change="changeGoal(g)"
                                            v-model="g.term"
                                        />
                                    </td>
                                    <td>
                                        <edit-input
                                            v-model="g.term_type"
                                            type="select"
                                            :options="term_type_options"
                                            :can_edit="true"
                                            @change="changeGoal(g)"
                                        />
                                    </td>
                                    <td class="text-center">
                                        <button
                                            class="append-btn"
                                            type="button"
                                            @click.prevent="deleteGoal(g)"
                                        >
                                            <span class="el-icon-error text-danger"></span>
                                        </button>
                                    </td>
                                </tr>
                            </template>

                            <tr>
                                <td>
                                    <input
                                        class="w-100"
                                        v-model="form.description"
                                    />
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
                                    <select
                                        class="w-100"
                                        v-model="form.term_type"
                                    >
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
            attempts: 0,
            loading_goals: true,
            goals: [],
            default_form: {
                description: null,
                value: 0,
                term_type: "Ano(s)",
                term: 0,
            },
            form: {},
            term_type_options: [
                { value: "Dia(s)", label: "Dia(s)" },
                { value: "Mes(es)", label: "Mes(es)" },
                { value: "Ano(s)", label: "Ano(s)" },
            ],
        }
    },
    created() {
        this.init()
    },
    methods: {
        init() {
            this.loadGoals()
            this.refreshForm()
        },
        loadGoals() {
            this.attempts++
            this.loading_goals = true
            this.$http
                .post("/admin/api/get-data/customerGoals", {
                    customer_id: this.customer.id,
                })
                .then((resp) => {
                    resp = resp.data
                    this.loading_goals = false
                    this.goals = resp
                })
                .catch((er) => {
                    if (this.attempts <= 3) return this.loadGoals()
                    this.loading_goals = false
                    return console.log(er)
                })
        },
        changeGoal(goal) {
            this.loading_goals = true
            this.$http
                .put(
                    `/admin/customers/${this.customer.code}/attendance/edit-goal`,
                    goal
                )
                .then((resp) => {
                    resp = resp.data
                    this.goals = resp.goals
                    this.loading_goals = false
                })
                .catch((er) => {
                    console.log(er)
                    this.loading_goals = false
                })
        },
        deleteGoal(goal) {
            this.$confirm("Deseja excluir ?", "Confirmação", {
                confirmButtonText: "Sim",
                cancelButtonText: "Não",
                type: "warning",
            }).then(() => {
                this.loading_goals = true
                this.$http
                    .delete(
                        `/admin/customers/attendance/delete-goal/${goal.id}`
                    )
                    .then((resp) => {
                        resp = resp.data
                        this.goals = resp.goals
                        this.loading_goals = false
                    })
                    .catch((er) => {
                        console.log(er)
                        this.loading_goals = false
                    })
            })
        },
        refreshForm() {
            Object.keys(this.default_form).map((k) =>
                this.$set(this.form, k, this.default_form[k])
            )
        },
        appendGoal() {
            if (!this.form.description)
                return this.$message.error("Defina ao menos uma descrição")
            this.$http
                .post(
                    `/admin/customers/${this.customer.code}/attendance/add-goal`,
                    { ...this.form, customer_id: this.customer.id }
                )
                .then((resp) => {
                    resp = resp.data
                    this.goals = resp.goals
                    this.refreshForm()
                    this.$message.success("Objetivo cadastrado com sucesso !!")
                })
                .catch((er) => {
                    console.log(er)
                })
        },
    },
}
</script>