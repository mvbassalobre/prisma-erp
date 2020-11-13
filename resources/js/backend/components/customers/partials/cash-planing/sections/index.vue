<template>
    <div class="mt-4" v-loading="loading">
        <!-- <expense-section v-for="s in sections" :years="years" :key="s.id" :section="s" :year="year" :customer="customer" /> -->
        <div class="row mt-3 mb-2">
            <div class="col-12 text-left">
                <span class="el-icon-circle-plus mr-2"></span>
                <a href="#" class="link" @click.prevent="addSection">Adicionar Nova Sessão de despesa em {{ year.value }}</a>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ['year'],
    computed: {
        loading() {
            return false
        },
        sections() {
            return []
        },
        months() {
            return this.$store.getters['global/getMonths']
        },
        customer() {
            return this.$store.state.customer
        },
    },
    methods: {
        addSection() {
            console.log('addSection')
        },
    },
}
</script>
