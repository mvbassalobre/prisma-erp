<template>
    <div v-loading="loading" v-if="customer.id">
        <div class="row mb-2">
            <div class="col-12 text-right">
                <el-button size="small" class="mb-2" @click.prevent="addYear">
                    <span class="el-icon-circle-plus mr-2" />
                    <template v-if="!hasYears">Iniciar Fluxo</template>
                    <template v-else>Extender 1 Ano</template>
                </el-button>
            </div>
        </div>
        <template v-if="!hasYears">Não Existe Fluxo de Caixa</template>
        <template v-else>
            <el-tabs type="border-card" ref="tabs" :closable="true" @tab-remove="removeYear">
                <el-tab-pane v-for="(year, y) in years" :label="`${year.value}`" :name="`${y}`" :key="year.id">
                    <table-entries :year="year" :key="`entries_${y}`" />
                    <table-sections :year="year" :key="`sections_${y}`" />
                    <flow-years :year="year" :years="years" />
                </el-tab-pane>
            </el-tabs>
        </template>
    </div>
</template>
<script>
import TableEntries from './entries'
import TableSections from './sections'
import FlowYears from './-flow-years.vue'
import store from '../../../../store'
export default {
    props: ['customer'],
    store: store,
    created() {
        this.$nextTick(() => {
            this.init()
        })
    },
    components: {
        TableEntries,
        TableSections,
        FlowYears,
    },
    computed: {
        cash_planing() {
            return this.$store.state.cash_planing
        },
        loading() {
            return this.cash_planing.loading
        },
        years() {
            return this.cash_planing.years
        },
        hasYears() {
            return this.$store.getters['cash_planing/getHasYears']
        },
    },
    methods: {
        init() {
            this.$store.commit('cash_planing/setCustomer', this.customer)
            this.$store.dispatch('cash_planing/getYears')
        },
        addYear() {
            if (!this.hasYears) return this.startFlux()
            this.$confirm('Adicionar mais 1 ano ao fluxo', 'Adicionar ano', {
                confirmButtonText: 'Sim',
                cancelButtonText: 'Não',
            }).then(() => {
                let new_year = parseInt(this.years[this.years.length - 1].value) + 1
                // this._addYear(0, new_year, () => this.setYearTab(this.years.length - 1))
                this.$store.dispatch('cash_planing/addYears', { new_year, more: 0 })
            })
        },
        startFlux() {
            this.$prompt('Digite o ano que deseja iniciar o fluxo', 'Iniciar pelo ano', {
                confirmButtonText: 'Adicionar',
                cancelButtonText: 'Cancelar',
                inputPattern: /^(19|20)\d{2}$/,
                inputErrorMessage: 'Ano Inválido',
            }).then(({ value }) => {
                // this._addYear(start_qty, new_year, () => this.setYearTab(this.years[0]))
                this.$store.dispatch('cash_planing/addYears', { new_year: parseInt(value), more: 3 })
            })
        },
        removeYear(val) {
            this.$confirm('Você deseja remover este mês ?', 'Confirmação', {
                confirmButtonText: 'Sim',
                cancelButtonText: 'Não',
                type: 'warning',
            }).then(() => {
                this.$store.dispatch('cash_planing/removeYear', this.years[val])
                this.setYearTab(0)
            })
        },
        setYearTab(year) {
            this.$refs.tabs.currentName = String(Object.keys(this.years).indexOf(`${String(year)}`))
        },
    },
}
</script>