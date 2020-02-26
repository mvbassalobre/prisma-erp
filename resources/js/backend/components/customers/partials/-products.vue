<template>
    <div
        class="tab-pane fade"
        id="v-pills-products"
        role="tabpanel"
        aria-labelledby="v-pills-products-tab"
    >
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h5>
                            <span class="el-icon-s-finance mr-2"></span>Produtos do Cliente
                        </h5>
                        <a
                            href="#"
                            class="link"
                            v-if="products.length>0"
                            @click.prevent="addProduct"
                        >Adicionar Produto</a>
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
                                        <h5>Cliente não possui produtos</h5>
                                        <small>Adicione produto a este cliente</small>
                                        <button
                                            class="btn btn-primary mb-4"
                                            @click="addProduct"
                                        >Adicionar Produto</button>
                                    </div>
                                </template>
                                <template v-else>
                                    <table
                                        class="table table-striped hovered resource-table table-hover mb-0"
                                    >
                                        <thead>
                                            <tr>
                                                <th>Nome</th>
                                                <th>Preço</th>
                                                <th>Adicionado Por ...</th>
                                                <th>Adicionado em ..</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(p,i) in products" :key="i">
                                                <td>{{p.product.name}}</td>
                                                <td>{{p.product.fprice}}</td>
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
            :height="350"
            :width="500"
            :resizable="false"
            :adaptive="true"
        >
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <b class="mt-2">
                        <b>
                            <span class="el-icon-plus mr-2"></span>Adicionar Produto
                        </b>
                    </b>
                    <a @click.prevent="$modal.hide('modal')">
                        <i class="el-icon-close"></i>
                    </a>
                </div>
                <div class="card-body d-flex flex-column">
                    <div class="row">
                        <div class="col-12">
                            <v-select
                                ref="products"
                                v-model="form.product_id"
                                list_model="\App\Http\Models\Product"
                                label="Produto"
                                description="Selecione o produto que deseja adicionar a este cliente"
                                placeholder="Selecione ..."
                                :route_list="`/admin/inputs/option_list`"
                            />
                        </div>
                        <template v-if="form.product_id">
                            <div class="col-12">
                                <div class="form-group row mb-3 d-flex align-items-center">
                                    <label class="col-sm-2 col-form-label">Preço</label>
                                    <div class="col-sm-10 col-sm-10">
                                        <b>{{product.fprice}}</b>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                    <div class="mt-auto">
                        <div class="d-flex flex-row align-items-center justify-content-end">
                            <a
                                class="text-danger mr-2"
                                href="#"
                                @click.prevent="$modal.hide('modal')"
                            >Cancelar</a>
                            <button class="btn btn-primary" @click="submit">Salvar</button>
                        </div>
                    </div>
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
        }
    },
    data() {
        return {
            form: {
                product_id: null,
                loading: null
            },
            product: {}
        }
    },
    watch: {
        "form.product_id"(val) {
            this.product = this.$refs.products.options.find(x => x.id == val)
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
            this.$http.post(laravel.general.root_url + "/admin/customers/post_new_product", { customer_id: this.customer.id, product_id: this.form.product_id }).then(res => {
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