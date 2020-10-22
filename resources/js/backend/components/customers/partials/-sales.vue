<template>
    <div class="tab-pane fade f-12" v-bind:class="{ 'show active': active == 'sales' }" id="v-pills-sales" role="tabpanel" aria-labelledby="v-pills-sales-tab">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h5><span class="el-icon-s-finance mr-2"></span>Lançamentos Financeiros</h5>
                        <a href="#" class="link" v-if="sales.length > 0 && canaddsale" @click.prevent="addSale">Adicionar</a>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <template v-if="sales.length <= 0">
                                    <div class="h-100 d-flex align-items-center justify-content-center flex-column pb-4">
                                        <h1 class="mt-4">
                                            <span class="el-icon-s-finance mr-2"></span>
                                        </h1>
                                        <h5>Cliente não possui lançamentos</h5>
                                        <small v-if="canaddsale">Adicione um lançamento clicando no botão abaixo</small>
                                        <button v-if="canaddsale" class="btn btn-primary mb-4 mt-3" @click="addSale">Adicionar Lançamento</button>
                                    </div>
                                </template>
                                <template v-else>
                                    <el-timeline class="pl-0">
                                        <el-timeline-item>
                                            <div class="card">
                                                <div class="card-header">
                                                    <b> <span class="el-icon-s-operation mr-2" />Filtro </b>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-4 col-sm-12">
                                                            <label>Produto</label>
                                                            <el-input class="w-100" placeholder="Nome do Produto" v-model="filter.product" clearable />
                                                        </div>
                                                        <div class="col-md-4 col-sm-12">
                                                            <label>Código</label>
                                                            <el-input class="w-100" placeholder="Código do lançamento" v-model="filter.code" clearable />
                                                        </div>
                                                        <div class="col-md-4 col-sm-12">
                                                            <label>Data do Registro</label>
                                                            <el-date-picker
                                                                class="w-100"
                                                                v-model="filter.range_data"
                                                                type="datetimerange"
                                                                range-separator="-"
                                                                start-placeholder="Início do Periodo"
                                                                end-placeholder="Fim do Periodo"
                                                                format="dd/MM/yyyy HH:mm:ss"
                                                                value-format="yyyy-MM-dd HH:mm:ss"
                                                                clearable
                                                            />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </el-timeline-item>
                                        <el-timeline-item :timestamp="s.f_created_at" placement="top" v-for="(s, i) in __sales" :key="i">
                                            <div class="card">
                                                <div class="card-header">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <b>
                                                            <span class="el-icon-info mr-2" />
                                                            <span classs="capitalize">
                                                                Lançamento Código
                                                                <a class="link" href="#" @click.prevent="showDetail(s)">{{ s.f_code }}</a>
                                                            </span>
                                                        </b>
                                                        <div class="d-flex flex-row align-items-center">
                                                            <div>
                                                                <span class="el-icon-time mr-1" />
                                                                <span v-html="s.f_created_at" />
                                                            </div>
                                                            <div v-if="!customer_area">
                                                                <div class="dropdown">
                                                                    <button
                                                                        class="btn btn-secondary dropdown-toggle btn-sm ml-3"
                                                                        type="button"
                                                                        id="dropdownMenuButton"
                                                                        data-toggle="dropdown"
                                                                        aria-haspopup="true"
                                                                        aria-expanded="false"
                                                                    >
                                                                        Ações
                                                                    </button>
                                                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                                                        <template v-if="s.payment">
                                                                            <a class="dropdown-item" href="#" @click.prevent="changeStatusModal(s)">
                                                                                Alterar Status do Pagto
                                                                            </a>
                                                                            <div class="dropdown-divider" />
                                                                            <a class="dropdown-item text-success" href="#" @click.prevent="baixa(s)">
                                                                                Dar baixa no lançamento
                                                                            </a>
                                                                            <div class="dropdown-divider" />
                                                                        </template>
                                                                        <a class="dropdown-item text-danger" href="#" @click.prevent="destroy(s)">
                                                                            Excluir lançamento
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-8 col-sm-12">
                                                            <table class="table table-striped table-sm hovered resource-table table-hover mb-0">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Produto</th>
                                                                        <th>Preço</th>
                                                                        <th>Qtde</th>
                                                                        <th>Subtotal</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr v-for="(item, i) in s.items" :key="i">
                                                                        <td v-html="item.name" />
                                                                        <td v-html="item.price.currency()" />
                                                                        <td v-html="item.qty" />
                                                                        <td v-html="item.total.currency()" />
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                            <h4 class="mt-3 text-right">
                                                                <b>Total :</b>
                                                                {{ s.subtotal.currency() }}
                                                            </h4>
                                                        </div>
                                                        <div class="col-md-4 col-sm-12">
                                                            <div class="d-flex flex-column">
                                                                <div v-if="s.user" class="d-flex align-items-center">
                                                                    <b>Responsável :</b>
                                                                    <a class="link ml-1" target="_BLANK" :href="`/admin/users/${s.user.code}`">{{
                                                                        s.user.name
                                                                    }}</a>
                                                                    <small class="f-10 ml-1" v-html="s.user.f_active" />
                                                                </div>
                                                                <div>
                                                                    <b class="mr-1">Data do Lançamento :</b>
                                                                    {{ s.f_created_at.split(' - ')[0] }}
                                                                </div>
                                                                <div>
                                                                    <b class="mr-1">Hora do Lançamento :</b>
                                                                    {{ s.f_created_at.split(' - ')[1] }}
                                                                </div>
                                                                <div v-if="!s.payment">
                                                                    <upload-document :customer="customer" :sale="s" />
                                                                </div>
                                                                <template v-else>
                                                                    <div>
                                                                        <b class="mr-1">Status de Pagto :</b>
                                                                        {{ s.payment.status }}
                                                                    </div>
                                                                    <div v-if="s.payment.description">
                                                                        <b class="mr-1">Descrição :</b>
                                                                        {{ s.payment.description }}
                                                                    </div>
                                                                    <div v-if="s.payment.reference">
                                                                        <b class="mr-1">Ref. :</b>
                                                                        {{ s.payment.reference }}
                                                                    </div>
                                                                    <div class="mt-2" v-if="s.payment.url">
                                                                        <b class="text-success mr-1">Com Link de Pagto</b>
                                                                        <url-qrcode :customer="customer" :url="s.payment.url" :default_email="customer.email" />
                                                                    </div>
                                                                </template>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </el-timeline-item>
                                    </el-timeline>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <modal-sales ref="modal_sales" :customer="customer" />
        <modal-detail ref="modal_detail" :customer="customer" :customer_area="customer_area" />

        <el-dialog title="Alteração de Status" :visible.sync="dialogStatus" width="30%" center>
            <el-select v-model="new_status" placeholder="Selecione o status do pagamento" class="w-100">
                <el-option label="Aguardando Pagto" value="Aguardando Pagto" />
                <el-option label="Pagamento Efetuado" value="Pagamento Efetuado" />
                <el-option label="Em Análise" value="Em Análise" />
                <el-option label="Disponível" value="Disponível" />
                <el-option label="Cancelado" value="Cancelado" />
            </el-select>
            <template slot="footer">
                <div class="d-flex justify-content-end">
                    <button class="btn btn-primary" :disabled="!new_status" @click="changeStatus" type="button">Alterar</button>
                </div>
            </template>
        </el-dialog>
    </div>
</template>
<script>
export default {
    props: {
        customer_area: {
            type: Boolean,
            default: false,
        },
        sales: {
            type: Array,
            default: () => [],
        },
        customer: {
            type: Object,
            default: () => ({}),
        },
        active: {
            type: String,
            default: () => null,
        },
        canaddsale: {
            type: Boolean,
            default: false,
        },
    },
    computed: {
        __sales() {
            let _sales = this._sales
            if (this.filter.code != null) _sales = this.sales.filter((row) => row.f_code.toLowerCase().indexOf(this.filter.code.toLowerCase()) >= 0)
            if (this.filter.product != null) {
                _sales = _sales.filter((row) => row.items.filter((item) => item.name.toLowerCase().indexOf(this.filter.product.toLowerCase()) >= 0).length > 0)
            }
            if (this.filter.range_data) {
                if (this.filter.range_data.length >= 2)
                    _sales = _sales.filter((row) => {
                        let date_1 = new Date(this.filter.range_data[0])
                        let date_2 = new Date(this.filter.range_data[1])
                        let date = new Date(row.created_at)
                        if (date >= date_1 && date <= date_2) return true
                        return false
                    })
            }
            return (_sales ? _sales : []).reverse()
        },
    },
    created() {
        this._sales = this.sales
    },
    data() {
        return {
            loading: false,
            _sales: [],
            openedInDialogStatus: null,
            dialogStatus: false,
            new_status: null,
            filter: {
                range_data: [],
                code: null,
                product: null,
            },
        }
    },
    components: {
        'modal-sales': require('./-modal-sales.vue').default,
        'modal-detail': require('./-modal-detail.vue').default,
        'url-qrcode': require('./-urlQrcode.vue').default,
        'upload-document': require('./-uploadDocument.vue').default,
    },
    methods: {
        changeStatus() {
            this.$confirm(`Confirma alteração de status lançamento ?`, 'Confirmação', {
                confirmButtonText: 'Sim',
                cancelButtonText: 'Não',
                type: 'warning',
            }).then(() => {
                this.loading = this.$loading()
                this.$http
                    .post('/admin/sales/change-status', {
                        new_status: this.new_status,
                        sale: this.openedInDialogStatus,
                    })
                    .then((res) => {
                        window.location.reload()
                    })
            })
        },
        changeStatusModal(s) {
            this.dialogStatus = true
            this.openedInDialogStatus = s
        },
        showDetail(s) {
            this.$refs.modal_detail.showModal(s)
        },
        baixa(p) {
            this.$confirm(`Confirma baixa desse lançamento ?`, 'Confirmação', {
                confirmButtonText: 'Sim',
                cancelButtonText: 'Não',
                type: 'warning',
            }).then(() => {
                this.loading = this.$loading()
                this.$http
                    .post(laravel.general.root_url + '/admin/customers/baixa', {
                        customer_id: this.customer.id,
                        sale: p,
                    })
                    .then((res) => {
                        window.location.reload()
                    })
                    .catch((er) => {
                        this.loading.close()
                        console.log(er)
                        this.$message({
                            showClose: true,
                            message: 'Erro ao excluir',
                            type: 'error',
                        })
                    })
            })
        },
        destroy(p) {
            this.$confirm(`Confirma exclusão ?`, 'Confirmação', {
                confirmButtonText: 'Sim',
                cancelButtonText: 'Não',
                type: 'warning',
            }).then(() => {
                this.loading = this.$loading()
                this.$http
                    .post(laravel.general.root_url + '/admin/customers/destroy_sale', { customer_id: this.customer.id, sale: p })
                    .then((res) => {
                        window.location.reload()
                    })
                    .catch((er) => {
                        this.loading.close()
                        console.log(er)
                        this.$message({
                            showClose: true,
                            message: 'Erro ao excluir',
                            type: 'error',
                        })
                    })
            })
        },
        addSale() {
            this.$refs.modal_sales.showModal()
        },
    },
}
</script>