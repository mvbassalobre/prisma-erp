<template>
    <div class="mt-4" v-loading="loading_expenses">
        <template v-if="!loading_expenses">
            <expense-section
                v-for="(s,i) in Object.keys(sections)"
                :key="i"
                :sections="sections"
                :s="s"
                :months="months"
                :entries="entries"
                :year="year"
                :customer="customer"
            />
        </template>
        <div class="row mt-3 mb-2">
            <div class="col-12 text-left">
                <span class="el-icon-circle-plus mr-2"></span>
                <a
                    href="#"
                    class="link"
                    @click.prevent="addSection"
                >Adicionar Nova Sessão de despesa em {{year}}</a>
            </div>
        </div>
        <cash-flow
            :months="months"
            :year="year"
            :sections="sections"
            v-if="!loading_expenses"
            :entries="entries"
        />
    </div>
</template>
<script>
export default {
    props: ['year', 'months', 'customer', '_sections', 'entries'],
    data() {
        return {
            sections: this._sections,
            loading_expenses: false,
        }
    },
    components: {
        "expense-section": require("./-expense-section.vue").default,
        "cash-flow": require("./-cash-flow.vue").default,
    },
    watch: {
        sections: {
            handler(val) {
                this.saveSections()
            },
            deep: true
        }
    },
    methods: {
        addSection() {
            this.$prompt('Digite o nome da sessão de deseja criar', 'Adicionar Sessão', {
                confirmButtonText: 'Adicionar',
                cancelButtonText: 'Cancelar',
                inputErrorMessage: 'Valor Inválido'
            }).then(({ value }) => {
                let section = value
                this.$set(this.sections, value, [])
                this.$message.success("Sessão adicionada com sucesso !!")
            })
        },
        saveSections() {
            this.loading_expenses = true
            this.$http.post(`/admin/customers/${this.customer.code}/attendance/save-sections`, { section: this.sections, year: this.year }).then(resp => {
                resp = resp.data
                this.loading_expenses = false
            }).catch(er => {
                console.log(er)
                this.loading_expenses = false
            })
        }
    }
}
</script>
