<template>
    <div>
        <div class="row mb-2">
            <div class="col-12 text-right">
                <span class="el-icon-circle-plus mr-2"></span>
                <a href="#" class="link" @click.prevent="addYear">Adicionar Ano</a>
            </div>
        </div>
        <el-tabs
            type="border-card"
            ref="tabs"
            v-if="Object.keys(years).length>0"
            closable
            @tab-remove="removeYear"
        >
            <el-tab-pane
                v-for="(year,i) in Object.keys(years).sort()"
                :label="`${year}`"
                :name="`${i}`"
                :key="i"
                closable
            >
                <div class="row f-12">
                    <div class="col-12">
                        <div class="card f-12">
                            <div class="card-header p-1">Entradas</div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table
                                        class="table table-striped table-sm mb-0 f-12 table-hover"
                                    >
                                        <thead>
                                            <tr>
                                                <th style="width:350px" class="bordered">
                                                    Conta
                                                    <small>Mês</small>
                                                </th>
                                                <template v-for="(m,i) in months">
                                                    <th style="width:100px" :key="`${i}_head`">
                                                        {{ m.value }} /
                                                        <small>{{year}}</small>
                                                    </th>
                                                </template>
                                                <th></th>
                                            </tr>
                                            <tr>
                                                <th style="width:350px" class="bordered">
                                                    Entradas
                                                    <small>Receitas</small>
                                                </th>
                                                <template v-for="(m,i) in months">
                                                    <th
                                                        style="width:150px"
                                                        class="f-10"
                                                        :key="`${i}_head_2`"
                                                    >R$ 0,00</th>
                                                </template>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(q,y) in years[year]" :key="y">
                                                <td class="bordered">{{q.name}}</td>
                                                <template v-for="(m,i) in months">
                                                    <td
                                                        :key="`${i}_${y}_body`"
                                                    >{{(Number(q[m.value])).currency()}}</td>
                                                </template>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td class="bordered">
                                                    <input class="w-100 mr-1" v-model="form.name" />
                                                </td>
                                                <template v-for="(m,i) in months">
                                                    <td :key="`${i}_form`">
                                                        <input
                                                            class="w-100 mr-1"
                                                            v-model="form[m.value]"
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
            </el-tab-pane>
        </el-tabs>
    </div>
</template>
<script>
export default {
    props: ['months'],
    data() {
        return {
            loading_entries: false,
            years: Object.assign({}, this.$attrs.value),
            form: {
                name: null
            }
        }
    },
    created() {
        this.months.map(({ value }) => this.$set(this.form, value, "0"))
    },
    watch: {
        years(val) {
            this.$emit("input", val)
        }
    },
    methods: {
        setAllValues(month) {
            if (month == "jan") {
                let other_months = this.months.filter(({ value }) => value != "jan").map(({ value }) => value)
                let emptys = Object.keys(this.form).filter(k => {
                    if ((other_months.includes(k)) && (!Number(this.form[k]))) return true
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
            this.years[year].push(Object.assign({}, this.form))
            let months_values = this.months.map(({ value }) => value)
            Object.keys(this.form).map(k => {
                if (months_values.includes(k)) return this.form[k] = 0
                return this.form[k] = null
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