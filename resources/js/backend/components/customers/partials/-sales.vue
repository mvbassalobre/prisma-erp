<template>
    <div
        class="tab-pane fade f-12"
        v-bind:class="{'show active' : active == 'sales'}"
        id="v-pills-sales"
        role="tabpanel"
        aria-labelledby="v-pills-sales-tab"
    >
        <div class="row" v-loading="loading">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h5>
                            <span class="el-icon-s-finance mr-2"></span>Lançamentos Financeiros
                        </h5>
                        <a
                            href="#"
                            class="link"
                            v-if="(sales.length>0) && canaddsale"
                            @click.prevent="addSale"
                        >Adicionar</a>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <template v-if="sales.length<=0">
                                    <div
                                        class="h-100 d-flex align-items-center justify-content-center flex-column pb-4"
                                    >
                                        <h1 class="mt-4">
                                            <span class="el-icon-s-finance mr-2"></span>
                                        </h1>
                                        <h5>Cliente não possui lançamentos</h5>
                                        <small
                                            v-if="canaddsale"
                                        >Adicione um lançamento clicando no botão abaixo</small>
                                        <button
                                            v-if="canaddsale"
                                            class="btn btn-primary mb-4 mt-3"
                                            @click="addSale"
                                        >Adicionar Lançamento</button>
                                    </div>
                                </template>
                                <template v-else>
                                    <el-timeline class="pl-0">
                                        <el-timeline-item>
                                            <div class="card">
                                                <div class="card-header">
                                                    <b>
                                                        <span class="el-icon-s-operation mr-2" />Filtro
                                                    </b>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-4 col-sm-12">
                                                            <label>Produto</label>
                                                            <el-input
                                                                class="w-100"
                                                                placeholder="Nome do Produto"
                                                                v-model="filter.product"
                                                                clearable
                                                            />
                                                        </div>
                                                        <div class="col-md-4 col-sm-12">
                                                            <label>Código</label>
                                                            <el-input
                                                                class="w-100"
                                                                placeholder="Código do lançamento"
                                                                v-model="filter.code"
                                                                clearable
                                                            />
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
                                        <el-timeline-item>
                                            <div
                                                class="d-flex align-items-center flex-column justify-content-center"
                                            >
                                                <span
                                                    class="el-icon-loading mt-3"
                                                    :style="{fontSize:50, color :'#9e6de0'}"
                                                />
                                                <small
                                                    :style="{color :'rgba(158, 109, 224, 0.53)'}"
                                                >Atualizando em tempo real</small>
                                            </div>
                                        </el-timeline-item>
                                        <el-timeline-item
                                            :timestamp="s.f_created_at"
                                            placement="top"
                                            v-for="(s,i) in __sales"
                                            :key="i"
                                        >
                                            <div class="card">
                                                <div class="card-header">
                                                    <div
                                                        class="d-flex justify-content-between align-items-center"
                                                    >
                                                        <b>
                                                            <span class="el-icon-info mr-2" />
                                                            <span classs="capitalize">
                                                                Lançamento Código
                                                                <a
                                                                    class="link"
                                                                    href="#"
                                                                    @click.prevent="showDetail(s)"
                                                                >{{s.f_code}}</a>
                                                            </span>
                                                        </b>
                                                        <div class="d-flex flex-row">
                                                            <div>
                                                                <span class="el-icon-time mr-1" />
                                                                <span v-html="s.f_created_at" />
                                                            </div>
                                                            <div v-if="!customer_area">
                                                                <a
                                                                    href="#"
                                                                    class="ml-3 text-danger"
                                                                    @click.prevent="destroy(s)"
                                                                >Excluir Lançamento</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-8 col-sm-12">
                                                            <table
                                                                class="table table-striped table-sm hovered resource-table table-hover mb-0"
                                                            >
                                                                <thead>
                                                                    <tr>
                                                                        <th>Produto</th>
                                                                        <th>Preço</th>
                                                                        <th>Qtde</th>
                                                                        <th>Subtotal</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr
                                                                        v-for="(item,i) in s.items"
                                                                        :key="i"
                                                                    >
                                                                        <td v-html="item.name" />
                                                                        <td
                                                                            v-html="item.price.currency()"
                                                                        />
                                                                        <td v-html="item.qty" />
                                                                        <td
                                                                            v-html="item.total.currency()"
                                                                        />
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                            <h4 class="mt-3 text-right">
                                                                <b>Total :</b>
                                                                {{s.subtotal.currency()}}
                                                            </h4>
                                                        </div>
                                                        <div class="col-md-4 col-sm-12">
                                                            <div class="d-flex flex-column">
                                                                <div
                                                                    v-if="s.user"
                                                                    class="d-flex align-items-center"
                                                                >
                                                                    <b>Responsável :</b>
                                                                    <a
                                                                        class="link ml-1"
                                                                        target="_BLANK"
                                                                        :href="`/admin/users/${s.user.code}`"
                                                                    >{{s.user.name}}</a>
                                                                    <small
                                                                        class="f-10 ml-1"
                                                                        v-html="s.user.f_active"
                                                                    />
                                                                </div>
                                                                <div>
                                                                    <b
                                                                        class="mr-1"
                                                                    >Data do Lançamento :</b>
                                                                    {{s.f_created_at.split(" - ")[0]}}
                                                                </div>
                                                                <div>
                                                                    <b
                                                                        class="mr-1"
                                                                    >Hora do Lançamento :</b>
                                                                    {{s.f_created_at.split(" - ")[1]}}
                                                                </div>
                                                                <div>
                                                                    <b
                                                                        v-if="!s.payment"
                                                                        class="text-danger"
                                                                    >Sem Link de Pagto</b>
                                                                    <b
                                                                        v-else
                                                                        class="text-success"
                                                                    >Com Link de Pagto</b>
                                                                </div>
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
    </div>
</template>
<script>
export default {
    props: {
        customer_area: {
            type: Boolean,
            default: false
        },
        sales: {
            type: Array,
            default: () => ([])
        },
        customer: {
            type: Object,
            default: () => ({})
        },
        active: {
            type: String,
            default: () => null
        },
        canaddsale: {
            type: Boolean,
            default: false
        }
    },
    computed: {
        __sales() {
            let _sales = this._sales
            if (this.filter.code != null) _sales = this.sales.filter(row => row.f_code.toLowerCase().indexOf(this.filter.code.toLowerCase()) >= 0)
            if (this.filter.product != null) {
                _sales = _sales.filter(row => row.items.filter(item => item.name.toLowerCase().indexOf(this.filter.product.toLowerCase()) >= 0).length > 0)
            }
            if (this.filter.range_data) {
                if (this.filter.range_data.length >= 2) _sales = _sales.filter(row => {
                    let date_1 = new Date(this.filter.range_data[0])
                    let date_2 = new Date(this.filter.range_data[1])
                    let date = new Date(row.created_at)
                    if ((date >= date_1) && (date <= date_2)) return true
                    return false
                })
            }
            return _sales
        }
    },
    created() {
        this._sales = this.sales
        setInterval(() => {
            this.getSales()
        }, 7000)
    },
    data() {
        return {
            loading: false,
            _sales: [],
            filter: {
                range_data: [],
                code: null,
                product: null
            }
        }
    },
    components: {
        "modal-sales": require("./-modal-sales.vue").default,
        "modal-detail": require("./-modal-detail.vue").default,
    },
    methods: {
        showDetail(s) {
            this.$refs.modal_detail.showModal(s)
        },
        destroy(p) {
            this.$confirm(`Confirma exclusão ?`, "Confirmação", {
                confirmButtonText: "Sim",
                cancelButtonText: "Não",
                type: 'warning'
            }).then(() => {
                this.loading = this.$loading()
                this.$http.post(laravel.general.root_url + "/admin/customers/destroy_sale", { customer_id: this.customer.id, sale: p }).then(res => {
                    window.location.reload()
                }).catch(er => {
                    this.loading.close()
                    console.log(er)
                    this.$message({ showClose: true, message: "Erro ao excluir", type: "error" })
                })
            })
        },
        addSale() {
            this.$refs.modal_sales.showModal()
        },
        getSales() {
            this.$http.post(`/admin/customers/${this.customer.code}/get-sales`, {}).then(resp => {
                resp = resp.data
                if (!resp.data.isEqual(this._sales)) {
                    this.loading = true
                    setTimeout(() => {
                        this._sales = resp.data
                        this.loading = false
                    }, 1000)
                }
            })
        }
    }
}
</script>