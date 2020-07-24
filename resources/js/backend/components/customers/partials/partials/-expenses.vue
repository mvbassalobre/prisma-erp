<template>
    <div class="mt-4" v-loading="loading_expenses">
        <expense-section
            v-for="s in sections"
            :key="s.id"
            :section="s"
            :year="year"
            :customer="customer"
        />
        <div class="row mt-3 mb-2">
            <div class="col-12 text-left">
                <span class="el-icon-circle-plus mr-2"></span>
                <a
                    href="#"
                    class="link"
                    @click.prevent="addSection"
                >Adicionar Nova Sessão de despesa em {{year.value}}</a>
            </div>
        </div>
        <cash-flow :year="year" :sections="sections" />
    </div>
</template>
<script>
export default {
    props: ['year', 'customer', 'customer_area'],
    data() {
        return {
            attempts: 0,
            sections: [],
            loading_expenses: true,
        }
    },
    components: {
        "expense-section": require("./-expense-section.vue").default,
        "cash-flow": require("./-cash-flow.vue").default,
    },
    created() {
        this.init()
    },
    methods: {
        init() {
            this.getSections()
        },
        getSections() {
            this.attempts++
            this.loading_expenses = true
            this.$http.post("/admin/api/get-data/customerYearSections", this.year).then(resp => {
                resp = resp.data
                this.loading_expenses = false
                this.sections = resp
            }).catch(er => {
                if (this.attempts <= 3) return this.getExpenses()
                this.loading_expenses = false
                return console.log(er)
            })
        },
        addSection() {
            this.$prompt('Digite o nome da sessão de deseja criar', 'Adicionar Sessão', {
                confirmButtonText: 'Adicionar',
                cancelButtonText: 'Cancelar',
                inputErrorMessage: 'Valor Inválido'
            }).then(({ value }) => {
                let section = value
                this.$http.post(`/admin/customers/${this.customer.code}/attendance/add-sections`, { section, year: this.year }).then(resp => {
                    resp = resp.data
                    this.loading_expenses = false
                    this.sections = resp.sections
                }).catch(er => {
                    console.log(er)
                    this.loading_expenses = false
                })
            })
        },
    }
}
</script>
