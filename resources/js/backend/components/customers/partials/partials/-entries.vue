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
                <div class="row mb-2">
                    <div class="col-12 text-right">
                        <span class="el-icon-circle-plus mr-2"></span>
                        <a href="#" class="link" @click.prevent="addEntry(year)">
                            Adicionar Entrada para
                            <b>{{year}}</b>
                        </a>
                    </div>
                </div>
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
                                                        {{ m.name.substring(0,3) }} /
                                                        <small>{{year}}</small>
                                                    </th>
                                                </template>
                                            </tr>
                                            <tr>
                                                <th style="width:350px" class="bordered">
                                                    Entradas
                                                    <small>Receitas</small>
                                                </th>
                                                <template v-for="(m,i) in months">
                                                    <th
                                                        style="width:100px"
                                                        class="f-10"
                                                        :key="`${i}_head_2`"
                                                    >R$ 0,00</th>
                                                </template>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(q,y) in years[year]" :key="y">
                                                <td class="bordered">{{q[y]}}</td>
                                                <template v-for="(m,i) in months">
                                                    <td
                                                        :key="`${i}_${y}_body`"
                                                    >{{(Number(q[i+1])).currency()}}</td>
                                                </template>
                                            </tr>
                                            <!-- <tr>
                                                <td>
                                                    <input
                                                        class="form-control form-control-sm"
                                                        v-model="years[year][]"
                                                    />
                                                </td>
                                            </tr>-->
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
            years: Object.assign({}, this.$attrs.value)
        }
    },
    watch: {
        years(val) {
            this.$emit("input", val)
        }
    },
    methods: {
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
            console.log(`add entrada ${year}`)
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