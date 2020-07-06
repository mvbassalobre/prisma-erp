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
                                <b>Usuário</b>
                            </label>
                            <el-select
                                class="w-100"
                                v-model="filter.user"
                                filterable
                                placeholder="Selecione o usuário"
                            >
                                <el-option label="Todos" value="all" />
                                <el-option
                                    v-for="(t,i) in users"
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
                                <b>Sala de Reunião</b>
                            </label>
                            <el-select
                                class="w-100"
                                v-model="filter.team"
                                filterable
                                placeholder="Selecione a Sala"
                            >
                                <el-option label="Todos" value="all" />
                                <el-option
                                    v-for="(t,i) in rooms"
                                    :key="i"
                                    :label="t.name"
                                    :value="t.id"
                                />
                            </el-select>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <label class="f-12">
                                <b>Data de Cadastro</b>
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
                        <div class="col-md-4 col-sm-12">
                            <label class="f-12">
                                <b>Duração</b>
                            </label>
                            <el-date-picker
                                class="w-100"
                                v-model="filter.range_data"
                                type="datetimerange"
                                range-separator="-"
                                start-placeholder="Início do Periodo"
                                end-placeholder="Fim do Periodo"
                                format="dd/MM/yyyy HH:mm:ss"
                                value-format="yyyy-MM-dd HH:mm:ss"
                            />
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-4 col-sm-12">
                            <label class="f-12">
                                <b>Status</b>
                            </label>
                            <el-select
                                class="w-100"
                                v-model="filter.status"
                                filterable
                                placeholder="Selecione o status"
                            >
                                <el-option label="Todos" value="all" />
                                <el-option
                                    v-for="(t,i) in status"
                                    :key="i"
                                    :label="t.name"
                                    :value="t.id"
                                >
                                    <span
                                        :style="{
                                            float:'left',
                                            backgroundColor:t.color,
                                            marginRight : 10,
                                            marginTop: 12,
                                            height: 10,
                                            width: 10,
                                            borderRadius: '100%',
                                        }"
                                    />
                                    <span>{{t.name}}</span>
                                </el-option>
                            </el-select>
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
    props: ["users", "rooms", "status"],
    data() {
        return {
            filter: {
                name: null,
                code: null,
                created_at_interval: [],
                range_data: [],
                status: "all",
                team: "all",
                room: "all",
            },
            customers: [],
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