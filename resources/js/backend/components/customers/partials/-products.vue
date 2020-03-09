<template>
    <div
        class="tab-pane fade"
        v-bind:class="{'show active' : active == 'products'}"
        id="v-pills-products"
        role="tabpanel"
        aria-labelledby="v-pills-products-tab"
    >
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h5>
                            <span class="el-icon-s-finance mr-2"></span>Serviços / produtos do Cliente
                        </h5>
                        <a
                            href="#"
                            class="link"
                            v-if="products.length>0"
                            @click.prevent="addProduct"
                        >Adicionar Serviço / Produto</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-12">
                                <template v-if="products.length<=0">
                                    <div
                                        class="h-100 d-flex align-items-center justify-content-center flex-column"
                                    >
                                        <h1 class="mt-4">
                                            <span class="el-icon-s-finance mr-2"></span>
                                        </h1>
                                        <h5>Cliente não possui serviços ou produtos</h5>
                                        <small>Adicione serviço / produto a este cliente</small>
                                        <button
                                            class="btn btn-primary mb-4 mt-3"
                                            @click="addProduct"
                                        >Adicionar Serviço / Produto</button>
                                    </div>
                                </template>
                                <template v-else>
                                    <table
                                        class="table table-striped hovered resource-table table-hover mb-0"
                                    >
                                        <thead>
                                            <tr>
                                                <th>Tipo</th>
                                                <th>Nome</th>
                                                <th>Preço</th>
                                                <th>Total</th>
                                                <th>Adicionado Por ...</th>
                                                <th>Adicionado em ..</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(p,i) in products" :key="i">
                                                <td>{{p.product.product.type}}</td>
                                                <td>{{p.product.product.name}}</td>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <div>R$ {{p.product.price.toFixed(2)}}</div>
                                                        <small>{{p.product.qty}} {{`${p.product.product.type=='Serviço' ? 'mês(es)' : 'unidade(s)'}`}}</small>
                                                    </div>
                                                </td>
                                                <td>R$ {{(p.product.price*p.product.qty).toFixed(2)}}</td>
                                                <td>{{p.user.name}}</td>
                                                <td>{{p.f_created_at}}</td>
                                                <td>
                                                    <div
                                                        class="d-fle flex-row justify-content-center align-items-center"
                                                    >
                                                        <a
                                                            href="#"
                                                            class="text-danger"
                                                            @click.prevent="destroy(p)"
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
        <modal
            name="modal"
            :clickToClose="false"
            :height="500"
            :width="'60%'"
            :resizable="false"
            :adaptive="true"
        >
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <b>
                        <span class="el-icon-plus mr-2"></span>Adicionar Serviço / Produto
                    </b>
                    <a @click.prevent="$modal.hide('modal')">
                        <i class="el-icon-close"></i>
                    </a>
                </div>
                <div class="card-body d-flex flex-column">
                    <div class="row">
                        <div class="col-12">
                            <v-select
                                label="Produto / Serviço*"
                                ref="products"
                                v-model="form.product_id"
                                list_model="\App\Http\Models\Product"
                                description="Selecione o serviço ou produto que deseja adicionar a este cliente"
                                placeholder="Selecione ..."
                                :route_list="`/admin/inputs/option_list`"
                            />
                        </div>
                    </div>
                    <template v-if="form.product_id">
                        <div class="row mt-2">
                            <div class="col-sm-12 col-md-6">
                                <v-input
                                    v-model="product.type"
                                    label="Tipo"
                                    :disabled="true"
                                    description="O tipo pode ser <b>Produto</b> ou <b>Serviço</b>"
                                />
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <v-input
                                    prepend="<b>R$</b>"
                                    v-model="product.price"
                                    label="Preço"
                                    :description="`Valor do ${product.type.toLowerCase()} por ${product.type=='Serviço' ? 'parcela' : 'unidade'}`"
                                    :disabled="true"
                                />
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-sm-12 col-md-6">
                                <v-input
                                    ref="qty"
                                    v-model="form.qty"
                                    type="number"
                                    :label="`${product.type=='Serviço' ? 'Vigência' : 'Quantidade'}*`"
                                    :description="`${product.type=='Serviço' ? 'Vigência em meses' : 'Quantidade de produtos'}`"
                                />
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <v-input
                                    ref="price"
                                    type="number"
                                    prepend="<b>R$</b>"
                                    v-model="form.price"
                                    :label="`Valor à Cobrar*`"
                                    :description="`Valor a ser cobrado por ${product.type=='Serviço' ? 'parcela' : 'unidade'}`"
                                />
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-sm-12 col-md-6">
                                <v-input
                                    ref="subtotal"
                                    :disabled="true"
                                    type="number"
                                    prepend="<b>R$</b>"
                                    v-model="form.subtotal"
                                    label="Subtotal"
                                    description="Valor total calculado"
                                />
                            </div>
                        </div>
                        <div class="mt-auto">
                            <div class="d-flex flex-row align-items-center justify-content-end">
                                <a
                                    class="text-danger mr-3"
                                    href="#"
                                    @click.prevent="$modal.hide('modal')"
                                >Cancelar</a>
                                <button class="btn btn-primary" @click="submit">Salvar</button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </modal>
    </div>
</template>
<script>
export default {
    props: {
        products: {
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
    data() {
        return {
            form: {
                customer_id: this.customer.id,
                product_id: null,
                qty: 0,
                loading: null,
                subtotal: 0,
                price: 0,
            },
            product: {}
        }
    },
    watch: {
        "form.product_id"(val) {
            this.product = this.$refs.products.options.find(x => x.id == val)
            this.form.qty = 1
            this.form.price = this.product.price
            this.form.subtotal = this.product.price
        },
        "form.qty"(qty) {
            if (Number(qty) <= 0) {
                this.$refs.qty.val, this.form.qty = 1
                return this.$message({ showClose: true, message: "Quantidade Mínima é 1", type: "warning" })
            }
            if (!this.$refs.subtotal) return
            this.form.subtotal = (Number(this.form.price) * Number(qty)).toFixed(2)
            this.$refs.subtotal.val = this.form.subtotal
        },
        "form.price"(price) {
            if (Number(price) < 0) {
                this.form.price, this.$refs.price.val = 0
                return this.$message({ showClose: true, message: `Preço não pode ser negativo`, type: "warning" })
            }
            if (!this.$refs.price) return
            this.form.subtotal = (Number(this.form.price) * Number(this.form.qty)).toFixed(2)
            this.$refs.subtotal.val = this.form.subtotal
        }
    },
    methods: {
        destroy(p) {
            this.$confirm(`Confirma exclusão ?`, "Confirmação", {
                confirmButtonText: "Sim",
                cancelButtonText: "Não",
                type: 'warning'
            }).then(() => {
                this.loading = this.$loading()
                this.$http.post(laravel.general.root_url + "/admin/customers/destroy_product", { customer_id: this.customer.id, product: p }).then(res => {
                    window.location.reload()
                }).catch(er => {
                    this.loading.close()
                    console.log(er)
                    this.$message({ showClose: true, message: "Erro ao excluir", type: "error" })
                })
            })
        },
        addProduct() {
            this.form.product_id = null
            this.$modal.show('modal')
        },
        submit() {
            if (!this.form.product_id) return this.$message({ showClose: true, message: "Selecione o produto antes de confirmar", type: "error" })
            this.loading = this.$loading()
            this.$http.post(laravel.general.root_url + "/admin/customers/post_new_product", this.form).then(res => {
                window.location.reload()
            }).catch(er => {
                this.loading.close()
                console.log(er)
                this.$message({ showClose: true, message: "Erro ao salvar", type: "error" })
            })
        }
    }
}
</script>