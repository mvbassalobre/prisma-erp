<template>
    <div
        class="tab-pane fade"
        v-bind:class="{'show active' : active == 'sales'}"
        id="v-pills-sales"
        role="tabpanel"
        aria-labelledby="v-pills-sales-tab"
    >
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h5>
                            <span class="el-icon-s-finance mr-2"></span>Lançamentos Financeiros
                        </h5>
                        <a
                            href="#"
                            class="link"
                            v-if="sales.length>0"
                            @click.prevent="addSale"
                        >Adicionar</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-12">
                                <template v-if="sales.length<=0">
                                    <div
                                        class="h-100 d-flex align-items-center justify-content-center flex-column"
                                    >
                                        <h1 class="mt-4">
                                            <span class="el-icon-s-finance mr-2"></span>
                                        </h1>
                                        <h5>Cliente não possui lançamentos</h5>
                                        <small>Adicione um lançamento clicando no botão abaixo</small>
                                        <button
                                            class="btn btn-primary mb-4 mt-3"
                                            @click="addSale"
                                        >Adicionar Lançamento</button>
                                    </div>
                                </template>
                                <template v-else>
                                    <table
                                        class="table table-striped hovered resource-table table-hover mb-0"
                                    >
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Items</th>
                                                <th>Total</th>
                                                <!-- <th>Adicionado Por ...</th>
                                                <th>Adicionado em ..</th>-->
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(s,i) in sales" :key="i">
                                                <td>{{s.f_code}}</td>
                                                <td>
                                                    <div v-html="s.f_items"></div>
                                                </td>
                                                <td>R$ {{(Number(s.subtotal)).toFixed(2)}}</td>
                                                <!-- <td>{{s.user.name}}</td>
                                                <td>{{s.f_created_at}}</td>-->
                                                <td>
                                                    <div
                                                        class="d-fle flex-row justify-content-center align-items-center"
                                                    >
                                                        <a
                                                            href="#"
                                                            class="text-danger"
                                                            @click.prevent="destroy(s)"
                                                        >
                                                            <span class="el-icon-delete-solid"></span>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <modal-sales ref="modal_sales" :customer="customer" />
    </div>
</template>
<script>
export default {
    props: {
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
    },
    components: {
        "modal-sales": require("./-modal-sales.vue").default
    },
    methods: {
        destroy(p) {
            this.$confirm(`Confirma exclusão ?`, "Confirmação", {
                confirmButtonText: "Sim",
                cancelButtonText: "Não",
                type: 'warning'
            }).then(() => {
                this.loading = this.$loading()
                this.$http.post(laravel.general.root_url + "/admin/customers/destroy_product", { customer_id: this.customer.id, sale: p }).then(res => {
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
        }
    }
}
</script>