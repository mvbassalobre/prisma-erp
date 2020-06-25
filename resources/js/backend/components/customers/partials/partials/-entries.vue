<template>
    <div>
        <div class="row mb-2" v-loading="loading_entries">
            <div class="col-12 text-right" v-if="!customer_area">
                <span class="el-icon-circle-plus mr-2"></span>
                <a href="#" class="link" @click.prevent="addYear">Adicionar Ano</a>
            </div>
        </div>
        <el-tabs
            type="border-card"
            ref="tabs"
            v-if="Object.keys(years).length>0"
            :closable="!customer_area"
            @tab-remove="removeYear"
        >
            <el-tab-pane
                v-for="(year,i) in Object.keys(years).sort()"
                :label="`${year}`"
                :name="`${i}`"
                :key="i"
                :closable="!customer_area"
            >
                <div class="row f-12">
                    <div class="col-12">
                        <div class="card f-12">
                            <div class="card-header p-1">
                                <span class="el-icon-circle-plus mr-2"></span>Entradas
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table
                                        class="table table-striped table-sm mb-0 f-12 table-hover"
                                        v-if="!loading_entries"
                                    >
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
                                                        class="green"
                                                    >
                                                        {{ m.value }} /
                                                        <small>{{year}}</small>
                                                    </th>
                                                </template>
                                                <th class="green" v-if="!customer_area"></th>
                                            </tr>
                                            <tr>
                                                <th style="width:350px">
                                                    Entradas
                                                    <small>Receitas</small>
                                                </th>
                                                <template v-for="(m,i) in months">
                                                    <th
                                                        style="width:150px"
                                                        class="f-10 green2"
                                                        :key="`${i}_head_2`"
                                                    >{{total(year,m.value).currency()}}</th>
                                                </template>
                                                <th class="green2" v-if="!customer_area"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(q,y) in years[year]" :key="y">
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
                                                        v-loading="loading_entries"
                                                        class="append-btn"
                                                        type="button"
                                                        @click.prevent="deleteEntry(year,y)"
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
                                                        v-loading="loading_entries"
                                                        class="append-btn"
                                                        type="button"
                                                        @click.prevent="addEntry(year)"
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
                <comp-expenses
                    :year="year"
                    :months="months"
                    :customer="customer"
                    :_sections="sections[year] ? sections[year] : {}"
                    :entries="years[year]"
                    :customer_area="customer_area"
                />
            </el-tab-pane>
        </el-tabs>
        <template v-else>Nenhum lançamento realizado</template>
    </div>
</template>
<script>
export default {
    props: ['customer', 'months', 'customer_area'],
    data() {
        return {
            loading_entries: false,
            years: this.customer.data ? (this.customer.data.entries ? this.customer.data.entries : {}) : {},
            sections: this.customer.data ? (this.customer.data.sections ? this.customer.data.sections : {}) : {},
            form: {
                name: null
            }
        }
    },
    components: {
        'comp-expenses': require("./-expenses.vue").default,
    },
    created() {
        this.months.map(({ value }) => this.$set(this.form, value, 0))
    },
    watch: {
        years: {
            handler(val) {
                this.saveEntries()
            },
            deep: true
        }
    },
    methods: {
        saveEntries(val) {
            this.loading_entries = true
            this.$http.post(`/admin/customers/${this.customer.code}/attendance/save-flux`, this.years).then(resp => {
                resp = resp.data
                this.loading_entries = false
            }).catch(er => {
                console.log(er)
                this.loading_entries = false
            })
        },
        deleteEntry(year, y) {

            this.$confirm("Deseja excluir ?", "Confirmação", {
                confirmButtonText: "Sim",
                cancelButtonText: "Não",
                type: 'warning'
            }).then(() => {
                this.loading_entries = true
                setTimeout(() => {
                    this.years[year].splice(y, 1)
                    this.$message.success('Entrada excluido !!!')
                    this.loading_entries = false
                }, 500)
            })
        },
        total(year, month) {
            return this.years[year].reduce((a, b) => a + b[month], 0)
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
        addYear() {
            this.$prompt('Digite o ano que deseja adicionar', 'Adicionar Ano', {
                confirmButtonText: 'Adicionar',
                cancelButtonText: 'Cancelar',
                inputPattern: /^(19|20)\d{2}$/,
                inputErrorMessage: 'Ano Inválido'
            }).then(({ value }) => {
                let year = parseInt(value)
                if (this.years[year]) {
                    this.setYearTab(year)
                    return this.$message.warning("Este ano já foi adicionado !!")
                }
                this.$set(this.years, `${String(year)}`, [])
                this.$message.success("Ano adicionado com sucesso !!")
                this.$nextTick(() => {
                    this.setYearTab(year)
                })
            })
        },
        setYearTab(year) {
            this.$refs.tabs.currentName = String(Object.keys(this.years).indexOf(`${String(year)}`))
        },
        addEntry(year) {
            if (!this.form.name) return this.$message.warning("De um nome a esta entrada !!")
            this.$set(this.years[year], this.years[year].length, Object.assign({}, this.form))
            let months_values = this.months.map(({ value }) => value)
            Object.keys(this.form).map(k => {
                if (months_values.includes(k)) return this.$set(this.form, k, 0)
                return this.$set(this.form, k, null)
            })
            this.$message.success("Entrada adicionado com sucesso !!")
        },
        removeYear(name) {
            this.$confirm("Você deseja remover este mês ?", "Confirmação", {
                confirmButtonText: "Sim",
                cancelButtonText: "Não",
                type: 'warning'
            }).then(() => {
                let years = Object.assign({}, this.years)
                let index = Number(name)
                let keys = Object.keys(this.years)
                delete years[keys[index]]
                this.years = years
                keys = Object.keys(this.years)
                if (keys.length > 0) {
                    this.$refs.tabs.currentName = String(keys.length - 1)
                }
                this.$message.success('Mês excluido com sucesso !!!')
            })
        }
    }
}
</script>