<template>
    <div class="mt-4" v-loading="loading">
        <section-row v-for="(section, s) in year.sections" :key="s" :section="section" :year="year" />
        <div class="row mt-3 mb-2">
            <div class="col-12 text-left">
                <span class="el-icon-circle-plus mr-2"></span>
                <a href="#" class="link" @click.prevent="addSection">Adicionar Nova Sessão de despesa em {{ year.value }}</a>
            </div>
        </div>
    </div>
</template>
<script>
import SectionRow from './-section-row.vue'
export default {
    props: ['year'],
    components: {
        SectionRow,
    },
    computed: {
        cash_planing() {
            return this.$store.state.cash_planing
        },
        loading() {
            return this.cash_planing.sections_loading[this.year.id] ? this.cash_planing.sections_loading[this.year.id] : false
        },
    },
    methods: {
        addSection() {
            this.$prompt('Digite o nome da sessão de deseja criar', 'Adicionar Sessão', {
                confirmButtonText: 'Adicionar',
                cancelButtonText: 'Cancelar',
                inputErrorMessage: 'Valor Inválido',
            }).then(({ value }) => {
                this.$store.dispatch('cash_planing/addSections', { year_id: this.year.id, section: value })
            })
        },
    },
}
</script>
