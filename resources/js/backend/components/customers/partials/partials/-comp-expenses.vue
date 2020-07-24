<template>
    <div class="mt-4">
        <!-- <expense-section
            v-for="s in Object.keys(sections)"
            :key="s"
            :sections="sections"
            :s="s"
            :months="months"
            :entries="entries"
            :year="year"
            :customer="customer"
            :saveSections="saveSections"
        />-->
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
        <cash-flow :months="months" :year="year" :sections="sections" :entries="entries" />
    </div>
</template>
<script>
export default {
    props: ['year', 'months', 'customer', 'sections', 'entries', 'customer_area'],
    data() {
        return {
            section: this.sections[this.year] ? this.sections[this.year] : {},
        }
    },
    components: {
        "expense-section": require("./-expense-section.vue").default,
        "cash-flow": require("./-cash-flow.vue").default,
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
                this.saveSections()
            })
        },
        saveSections() {
            this.$http.post(`/admin/customers/${this.customer.code}/attendance/save-sections`, { section: this.sections, year: this.year }).then(resp => {
                resp = resp.data
                this.$set(this.raw_section, this.year, resp.sections)
            }).catch(er => {
                console.log(er)
            })
        }
    }
}
</script>
