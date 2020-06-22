<template>
    <div class="mt-4">
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
    </div>
</template>
<script>
export default {
    props: ['year', 'months', 'customer', '_sections', 'entries'],
    data() {
        return {
            sections: this._sections
        }
    },
    components: {
        "expense-section": require("./-expense-section.vue").default
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
        }
    }
}
</script>
