<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <b class="f-12">
                        <span class="el-icon-search mr-2"></span>
                        Filtro
                    </b>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 col-sm-12">
                            <label class="f-12">
                                <b>Código</b>
                            </label>
                            <input
                                class="form-control input-element"
                                placeholder="Código da Venda"
                                v-model="filter.code"
                            />
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <label class="f-12">
                                <b>Cliente</b>
                            </label>
                            <input
                                class="form-control input-element"
                                placeholder="Nome do Cliente"
                                v-model="filter.name"
                            />
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <label class="f-12">
                                <b>Time</b>
                            </label>
                            <el-select
                                class="w-100"
                                v-model="filter.team"
                                filterable
                                placeholder="Selecione o time"
                            >
                                <el-option label="Todos" value="all" />
                                <el-option
                                    v-for="(t,i) in teams"
                                    :key="i"
                                    :label="t.name"
                                    :value="t.id"
                                />
                            </el-select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-4 col-sm-12">
                            <label class="f-12">
                                <b>Produto</b>
                            </label>
                            <input
                                class="form-control input-element"
                                placeholder="Nome do Produto ..."
                                v-model="filter.product"
                            />
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <label class="f-12">
                                <b>Data do Lançamento</b>
                            </label>
                            <el-date-picker
                                class="w-100"
                                v-model="filter.created_at_interval"
                                type="daterange"
                                range-separator="-"
                                start-placeholder="Início do Periodo"
                                end-placeholder="Fim do Periodo"
                                format="dd/MM/yyyy"
                                value-format="yyyy-MM-dd"
                            />
                        </div>
                    </div>
                    <div
                        class="alert alert-info f-12 mt-2"
                        role="alert"
                    >Confira se os filtros selecionados são compatíveis com oque você está buscando.</div>
                    <div class="row">
                        <div class="col-12">
                            <button class="btn btn-secondary btn-sm f-12" @click.prevent="submit">
                                <span class="el-icon-search mr-2"></span> Buscar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ["teams"],
    data() {
        return {
            filter: {
                status: null,
                name: null,
                code: null,
                created_at_interval: [],
                range_data: [],
                team: "all",
                product: null,
            },
            attempts: 0
        }
    },
    mounted() {
        this.submit()
    },
    methods: {
        submit() {
            return this.$emit("filter", this.filter)
        }
    }
}
</script>
<style scoped lang="scss">
.input-element {
    margin-top: 1.1px;
    height: 38px;
    &::placeholder {
        color: lightgray;
    }
}
</style>