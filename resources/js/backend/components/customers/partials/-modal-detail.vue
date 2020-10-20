<template>
    <modal name="modal_detail" :clickToClose="false" :height="530" :width="'80%'" :resizable="false" :adaptive="true">
        <div class="card h-100" v-if="sale">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div>
                    Lançamento
                    <b>{{ sale.f_code }}</b> em detalhes
                </div>
                <a @click.prevent="$modal.hide('modal_detail')">
                    <i class="el-icon-close"></i>
                </a>
            </div>
            <div class="card-body d-flex flex-column" v-loading="loading" style="overflow-y: auto">
                <div class="row">
                    <div class="col-12">
                        <h5 v-if="!customer_area">
                            Cliente
                            <small class="ml-2">
                                <a :href="customerRoute" target="_BLANK" class="link">Ver Detalhadamente</a>
                            </small>
                        </h5>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-6 col-sm-12 d-flex flex-column">
                        <div>
                            <b>Nome :</b>
                            {{ this.customer.name }}
                        </div>
                        <div>
                            <b>Email :</b>
                            {{ this.customer.email }}
                        </div>
                        <div>
                            <b>Telefones :</b>
                            {{ this.customer.phone }} - {{ this.customer.cellphone }}
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 d-flex flex-column">
                        <div>
                            <b>CPF/CNPJ :</b>
                            {{ this.customer.cpfcnpj }}
                        </div>
                        <div>
                            <b>RG/IE :</b>
                            {{ this.customer.ierg }}
                        </div>
                        <div>
                            <b>Profissão :</b>
                            {{ this.customer.profession }}
                        </div>
                    </div>
                </div>
                <hr />
                <div class="row mt-4">
                    <div v-bind:class="{ 'col-12': !sale.payment, 'col-md-7 col-sm-12': sale.payment }">
                        <h5>Itens</h5>
                        <table class="table table-striped hovered resource-table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Items</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div v-html="sale.f_items"></div>
                                    </td>
                                    <td>R$ {{ Number(sale.subtotal).toFixed(2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-5 col-sm-12" v-if="sale.payment">
                        <h5>Pagamento</h5>
                        <div class="d-flex flex-row">
                            <template v-if="['Aguardando pagamento', 'waiting'].includes(sale.payment.status)">
                                <div class="d-flex flex-column col-md-6 col-sm-12 pl-0 mt-3">
                                    <url-qrcode :customer="customer" :url="sale.payment.url" :default_email="customer.email" />
                                </div>
                            </template>
                            <div class="d-flex flex-column col-md-6 col-sm-12">
                                <div>
                                    <b>Status :</b>
                                    {{ this.sale.payment.f_status }}
                                </div>
                                <div v-if="this.sale.payment.description">
                                    <b>Descrição :</b>
                                    {{ this.sale.payment.description }}
                                </div>
                                <div v-if="this.sale.payment.reference">
                                    <b>Ref. :</b>
                                    {{ this.sale.payment.reference }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </modal>
</template>
<script>
export default {
    props: ['customer', 'customer_area'],
    data() {
        return {
            loading: null,
            sale: null,
        }
    },
    components: {
        'url-qrcode': require('./-urlQrcode.vue').default,
    },
    computed: {
        customerRoute() {
            if (!this.sale.payment) return ''
            return `${laravel.general.root_url}/admin/customers/${this.customer.code}`
        },
    },
    methods: {
        showModal(s) {
            this.sale = s
            this.$modal.show('modal_detail')
        },
    },
}
</script>