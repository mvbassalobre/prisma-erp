<template>
    <div>
        <div class="row mb-2">
            <div class="col-12">
                <nav class="nav nav-pills flex-column flex-sm-row">
                    <a
                        class="flex-sm text-sm-center nav-link mr-1"
                        v-for="(option, i) in options"
                        :key="i"
                        v-bind:class="{ active: option.active }"
                        :href="`#v-pills-${option.name}`"
                        @click.prevent="setActive(option)"
                        >{{ option.label }}</a
                    >
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-12">
                        <div class="row f-12" v-if="active == 'info'">
                            <div class="col-12">
                                <div class="card mb-3" :id="`${infoData.label}`">
                                    <div class="card-body p-0">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <table class="table table-striped mb-0">
                                                    <tbody>
                                                        <tr v-for="(field, i) in infoData.inputs" :key="i">
                                                            <td style="width: 25%" v-if="i.indexOf('IGNORE__') < 0">
                                                                <span v-html="`<b>${i}</b>`"></span>
                                                            </td>
                                                            <td>
                                                                <v-runtime-template :key="i" :template="`<span>${processField(field, i)}</span>`" />
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="row" v-if="!customer_area">
                                            <div class="col-12">
                                                <table class="table table-striped mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-if="!has_customer_area">
                                                            <td>
                                                                <b>Acesso a Area do Usuário</b>
                                                            </td>
                                                            <td colspan="4">
                                                                <a href="#" class="link" @click.prevent="generateUser">Gerar Acesso a Area do Cliente</a>
                                                            </td>
                                                        </tr>
                                                        <tr v-else>
                                                            <td>
                                                                <b>
                                                                    Acesso a
                                                                    <a class="link" target="_BLANK" :href="customer_area_url">Area do Usuário</a>
                                                                </b>
                                                            </td>
                                                            <td>{{ customer.username }}</td>
                                                            <td>
                                                                <b>Senha</b>
                                                            </td>
                                                            <td>****************</td>
                                                            <td class="width:1%">
                                                                <button class="append-btn" type="button" @click.prevent="deleteAccess">
                                                                    <span class="el-icon-error text-danger"></span>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-content" id="v-pills-tabContent">
                    <comp-info :info="data" :active="active" :customer="customer" />
                    <comp-timeline :customer="customer" :active="active" />
                    <comp-sales
                        :sales="customer.sales"
                        :customer="customer"
                        :active="active"
                        :canaddsale="canaddsale"
                        :customer_area="customer_area"
                        name="sales"
                        type="Serviço"
                        :texts="{
                            plural: 'análises',
                            singular: 'análise',
                        }"
                    />
                    <comp-sales
                        :sales="customer.sales"
                        :customer="customer"
                        :active="active"
                        :canaddsale="canaddsale"
                        :customer_area="customer_area"
                        name="products"
                        type="Produto"
                        :texts="{
                            plural: 'produtos',
                            singular: 'produto',
                        }"
                    />
                    <comp-flux :customer="customer" :active="active" :customer_area="customer_area" />
                    <access-component
                        :sales="customer.sales"
                        :customer="customer"
                        :active="active"
                        :canaddsale="canaddsale"
                        v-if="customer_area"
                        :customer_area="customer_area"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import VRuntimeTemplate from 'v-runtime-template'
export default {
    props: ['customer', 'data', 'canaddsale', 'customer_area_url', 'customer_area'],
    data() {
        return {
            backup_collapse: null,
            active: 'info',
            options: [
                { name: 'info', label: 'Dados do Cliente', active: true },
                { name: 'timeline', label: 'Timeline', active: false },
                { name: 'sales', label: 'Análises', active: false },
                { name: 'products', label: 'Produtos', active: false },
                { name: 'flux', label: 'Planejamento', active: false },
                { name: 'access', label: 'Acesso', active: false },
            ],
        }
    },
    components: {
        'comp-info': require('./partials/-info').default,
        'comp-timeline': require('./partials/-timeline').default,
        'comp-sales': require('./partials/-sales').default,
        'comp-flux': require('./partials/-flux').default,
        'access-component': require('./partials/-accessComponent').default,
        'v-runtime-template': VRuntimeTemplate,
    },
    computed: {
        has_customer_area() {
            if (!this.customer.username || !this.customer.password) return false
            return true
        },
        infoData() {
            let info = this.data.fields.filter(({ label }) => label == 'Informações')[0]
            return info
        },
    },
    created() {
        if (this.customer_area)
            this.options.splice(
                this.options.findIndex((x) => x.name == 'sales'),
                1
            )
        if (!this.customer_area)
            this.options.splice(
                this.options.findIndex((x) => x.name == 'access'),
                1
            )
        this.$root.sidebarCollapse = true
        this.initHash()
    },
    methods: {
        processField(field, label) {
            if (label == 'Data de Nascimento') {
                const splited_date = (field ?? '').split('-')
                if (splited_date.length < 3) return field
                return `${splited_date[2]}/${splited_date[1]}/${splited_date[0]}`
            }
            return field === null ? '' : field
        },
        initHash() {
            let hash = window.location.hash
            if (hash) {
                let option = this.options.find((x) => x.name == hash.replace('#', ''))
                if (option) this.setActive(option)
            }
        },
        setActive(option) {
            for (let i in this.options) {
                if (option.name == this.options[i].name) {
                    this.active = option.name
                    this.options[i].active = true
                    window.location.hash = `#${this.active}`
                } else this.options[i].active = false
            }
        },
        generateUser() {
            this.$confirm('Criar acesso a area do cliente ?', 'Confirmação', {
                confirmButtonText: 'Sim',
                cancelButtonText: 'Não',
                type: 'warning',
            }).then(() => {
                let loading = this.$loading()
                this.$http.post(`/admin/customers/${this.customer.code}/create-area-access`, {}).then((resp) => {
                    window.location.reload()
                })
            })
        },
        deleteAccess() {
            this.$confirm('Remove acesso a area do cliente ?', 'Confirmação', {
                confirmButtonText: 'Sim',
                cancelButtonText: 'Não',
                type: 'warning',
            }).then(() => {
                let loading = this.$loading()
                this.$http.post(`/admin/customers/${this.customer.code}/remove-area-access`, {}).then((resp) => {
                    Cookies.remove('customer_area_user')
                    window.location.reload()
                })
            })
        },
    },
}
</script>
<style lang="scss" scoped>
.nav {
    .nav-link {
        background-color: silver;
        color: white;
        &.active {
            background-color: #9e6de0;
            color: white;
        }
    }
}
</style>