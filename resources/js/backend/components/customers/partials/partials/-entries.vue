<template>
    <div v-loading="loading">
        <div class="row mb-2">
            <div class="col-12 text-right">
                <el-button size="small" class="mb-2" @click.prevent="addYear">
                    <span class="el-icon-circle-plus mr-2" />
                    <template v-if="years.length <= 0">Iniciar Fluxo</template>
                    <template v-else>Extender 1 Ano</template>
                </el-button>
            </div>
        </div>
        <el-tabs type="border-card" ref="tabs" v-if="years.length > 0" :closable="true" @tab-remove="removeYear">
            <el-tab-pane v-for="(year, y) in years" :label="`${year.value}`" :name="`${y}`" :key="year.id">
                <div class="row f-12">
                    <div class="col-12">
                        <div class="card f-12">
                            <div class="card-header p-1"><span class="el-icon-circle-plus mr-2"></span>Entradas</div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-striped table-sm mb-0 f-12 table-hover">
                                        <thead>
                                            <tr>
                                                <th class="w-25">
                                                    Conta
                                                    <small class="ml-1">Mês</small>
                                                </th>
                                                <template v-for="(m, i) in months">
                                                    <th style="width: 100px" :key="`${i}_head`" class="green">
                                                        {{ m.value }} /
                                                        <small>{{ year.value }}</small>
                                                    </th>
                                                </template>
                                                <th class="green"></th>
                                            </tr>
                                            <tr class="w-25">
                                                <th>
                                                    Entradas
                                                    <small>Receitas</small>
                                                </th>
                                                <template v-for="(m, i) in months">
                                                    <th style="width: 150px" class="f-10 green2" :key="`${i}_head_2`">
                                                        {{ total(year.entries, m).currency() }}
                                                    </th>
                                                </template>
                                                <th class="green2"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(q, y) in year.entries" :key="q.id">
                                                <td class="w-25">
                                                    <edit-input v-model="q.name" :can_edit="true" @change="changeValue(q)" />
                                                </td>
                                                <template v-for="(m, i) in months">
                                                    <td :key="`${i}_${y}_body`">
                                                        <edit-input
                                                            type="number"
                                                            v-model="q[m.value]"
                                                            :currency="true"
                                                            :can_edit="true"
                                                            @change="changeValue(q, m)"
                                                        />
                                                    </td>
                                                </template>
                                                <td class="text-center">
                                                    <button class="append-btn" type="button" @click.prevent="deleteEntry(q)">
                                                        <span class="el-icon-error text-danger"></span>
                                                    </button>
                                                </td>
                                            </tr>
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
                                                    <button class="append-btn" type="button" @click.prevent="addEntry(year)">
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
                <expenses :years="years" :year="year" :customer="customer" :customer_area="customer_area" :ref="`expense_${year.value}`" />
            </el-tab-pane>
        </el-tabs>
        <template v-else>Não Existe Fluxo de Caixa Iniciado</template>
    </div>
</template>
<script>
export default {
    props: ['customer', 'customer_area'],
    data() {
        return {
            attempts: 0,
            loading: true,
            years: [],
            sections: {},
            months: this.$getMoths(),
            form: {
                name: null,
            },
        }
    },
    components: {
        expenses: require('./-expenses.vue').default,
    },
    created() {
        this.months.map(({ value }) => this.$set(this.form, value, 0))
        this.init()
    },
    methods: {
        init() {
            this.loadYears()
        },
        loadYears() {
            this.attempts++
            this.loading = true
            this.$http
                .post('/admin/api/get-data/customerFluxYears', { customer_id: this.customer.id })
                .then((resp) => {
                    resp = resp.data
                    this.loading = false
                    this.years = resp
                })
                .catch((er) => {
                    if (this.attempts <= 3) return this.loadYears()
                    this.loading = false
                    return console.log(er)
                })
        },
        changeValue(entry, current_month = null) {
            if (current_month) {
                let months = this.months.filter((m) => m.number > current_month.number)
                months.forEach((month) => {
                    let value = entry[current_month.value]
                    let index = month.value
                    this.$set(entry, index, value)
                })
            }
            this.loading = true
            this.$http
                .put(`/admin/customers/${this.customer.code}/attendance/edit-flux-entry`, entry)
                .then((resp) => {
                    resp = resp.data
                    this.years = resp.years
                    this.loading = false
                })
                .catch((er) => {
                    console.log(er)
                    this.loading = false
                })
        },
        deleteEntry(entry) {
            this.$confirm('Deseja excluir ?', 'Confirmação', {
                confirmButtonText: 'Sim',
                cancelButtonText: 'Não',
                type: 'warning',
            }).then(() => {
                this.loading = true
                this.$http
                    .delete(`/admin/customers/attendance/delete-flux-entry/${entry.id}`)
                    .then((resp) => {
                        resp = resp.data
                        this.years = resp.years
                        this.loading = false
                    })
                    .catch((er) => {
                        console.log(er)
                        this.loading = false
                    })
            })
        },
        total(entries, month) {
            return entries.map((e) => Number(e[month.value])).reduce((a, b) => a + b, 0)
        },
        setAllValues(current_month) {
            this.months.forEach((month) => {
                if (month.number > current_month.number) this.form[month.value] = this.form[current_month.value]
            })
        },
        startFlux() {
            this.$prompt('Digite o ano que deseja iniciar o fluxo', 'Iniciar pelo ano', {
                confirmButtonText: 'Adicionar',
                cancelButtonText: 'Cancelar',
                inputPattern: /^(19|20)\d{2}$/,
                inputErrorMessage: 'Ano Inválido',
            }).then(({ value }) => {
                let new_year = parseInt(value)
                let start_qty = 3
                this._addYear(start_qty, new_year, () => this.setYearTab(this.years[0]))
            })
        },
        _addYear(qty, start_year, callback) {
            let new_years = [start_year]
            for (let i = 1; i <= qty; i++) new_years.push(start_year + i)
            this.loading = true
            this.$http
                .post(`/admin/customers/${this.customer.code}/attendance/add-flux-year`, new_years)
                .then((resp) => {
                    resp = resp.data
                    this.years = resp.years
                    this.loading = false
                    callback()
                })
                .catch((er) => {
                    console.log(er)
                    this.loading = false
                })
        },
        addYear() {
            if (this.years.length <= 0) return this.startFlux()
            this.$confirm('Adicionar mais 1 ano ao fluxo', 'Adicionar ano', {
                confirmButtonText: 'Sim',
                cancelButtonText: 'Não',
            }).then(() => {
                let new_year = parseInt(this.years[this.years.length - 1].value) + 1
                this._addYear(0, new_year, () => this.setYearTab(this.years.length - 1))
            })
        },
        setYearTab(year) {
            this.$refs.tabs.currentName = String(Object.keys(this.years).indexOf(`${String(year)}`))
        },
        refreshForm() {
            this.form.name = null
            let months_values = this.months.map(({ value }) => value)
            Object.keys(this.form).map((k) => {
                if (months_values.includes(k)) return this.$set(this.form, k, 0)
                return this.$set(this.form, k, null)
            })
        },
        addEntry(year) {
            if (!this.form.name) return this.$message.warning('De um nome a esta entrada !!')
            this.loading = true
            this.$http
                .post(`/admin/customers/${this.customer.code}/attendance/add-year-entry`, { year, ...this.form })
                .then((resp) => {
                    resp = resp.data
                    this.years = resp.years
                    this.loading = false
                    this.refreshForm()
                })
                .catch((er) => {
                    console.log(er)
                    this.loading = false
                })
        },
        removeYear(y) {
            let year = this.years[y]
            this.$confirm('Você deseja remover este mês ?', 'Confirmação', {
                confirmButtonText: 'Sim',
                cancelButtonText: 'Não',
                type: 'warning',
            }).then(() => {
                this.loading = true
                this.$http
                    .delete(`/admin/customers/attendance/delete-flux-year/${year.id}`)
                    .then((resp) => {
                        resp = resp.data
                        this.years = resp.years
                        this.setYearTab(this.years.length - 1)
                        this.loading = false
                    })
                    .catch((er) => {
                        console.log(er)
                        this.loading = false
                    })
            })
        },
    },
}
</script>