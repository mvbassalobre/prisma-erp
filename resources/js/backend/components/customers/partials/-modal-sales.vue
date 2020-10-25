<template>
    <modal name="modal_sales" :clickToClose="false" :height="530" :width="'80%'" :resizable="false" :adaptive="true">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div>
                    <span class="el-icon-plus mr-2"></span>
                    Adicionar {{ texts.singular }} ao cliente
                    <b>{{ customer.name }}</b>
                </div>
                <a @click.prevent="$modal.hide('modal_sales')">
                    <i class="el-icon-close"></i>
                </a>
            </div>
            <div class="card-body d-flex flex-column" v-loading="loading" style="overflow-y: auto">
                <div class="row">
                    <div class="col-md-5 col-sm-12">
                        <div class="d-flex flex-column">
                            <div class="d-flex flex-column">
                                <el-select v-model="product_id" filterable placeholder="Selecione o produto">
                                    <el-option :key="0" label value />
                                    <el-option v-for="p in products" :key="p.id" :label="`${p.id.pad(6)} - ${p.name}`" :value="p.id"></el-option>
                                </el-select>
                                <small>Selecione um produto / serviço ao lançamento</small>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-4 col-sm-12">
                                    <div class="d-flex flex-column">
                                        <input class="form-control" v-model="qty" :disabled="!selected_product" type="number" step="1" min="1" />
                                        <small>Quantidade</small>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <div class="d-flex flex-column">
                                        <input
                                            class="form-control"
                                            :disabled="!selected_product || selected_product.price != 0"
                                            v-model="selected_price"
                                            type="number"
                                        />
                                        <small>Valor Unitário</small>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <div class="d-flex flex-column">
                                        <input class="form-control" disabled :value="`${selected_product ? total : ''}`" />
                                        <small>Total</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <button class="btn btn-primary btn-block py-3" type="button" :disabled="!selected_product" @click="push">INCLUIR</button>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="d-flex flex-column">
                                        <textarea style="resize: none" rows="5" class="form-control" v-model="obs" :disabled="!selected_product"></textarea>
                                        <small>Observações</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7 col-sm-12">
                        <div class="item-content">
                            <table class="table-items">
                                <tr>
                                    <th>Produto</th>
                                    <th>Valor Unit.</th>
                                    <th>Qtde</th>
                                    <th>Total</th>
                                    <th></th>
                                </tr>
                                <template v-for="(item, i) in items">
                                    <tr :key="i">
                                        <td>{{ item.id.pad(6) }} - {{ item.name }}</td>
                                        <td>R$ {{ item.price.toFixed(2) }}</td>
                                        <td>{{ item.qty }}</td>
                                        <td>R$ {{ item.total.toFixed(2) }}</td>
                                        <td>
                                            <a href="#" @click.prevent="deleteItem(item)">
                                                <span class="el-icon-delete text-danger"></span>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr v-if="item.obs" :key="`obs_${i}`">
                                        <td colspan="5" class="text-primary pt-2 pb-3 pl-4">{{ item.obs }}</td>
                                    </tr>
                                </template>
                                <template v-if="items.length < 15">
                                    <tr v-for="(item, i) in 15 - items.length" :key="`fake_${i}`">
                                        <td colspan="5"></td>
                                    </tr>
                                </template>
                            </table>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <div class="bg-primary text-white py-2 d-flex justify-content-between px-2">
                                    <h5>
                                        <b>SUTOTAL</b>
                                    </h5>
                                    <h5 style="font-weight: 300">R$ {{ subtotal.toFixed(2) }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" v-if="!isProduct">
                    <div class="col-12 text-right">
                        <label> <input type="checkbox" v-model="generate_payment" /> Gerar URL de Pagamento </label>
                    </div>
                </div>
                <div class="row mt-auto">
                    <div class="col-12">
                        <div class="d-flex flex-row align-items-center justify-content-end">
                            <a class="text-danger mr-4" href="#" @click.prevent="$modal.hide('modal_sales')">Cancelar</a>
                            <button class="btn btn-primary" @click="submit" :disabled="!canCreate">Lançar {{ texts.singular.ucfirst() }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </modal>
</template>
<script>
export default {
    props: ['customer', 'customer_area', 'type', 'texts'],
    data() {
        return {
            loading: false,
            products: [],
            product_id: null,
            qty: null,
            obs: null,
            items: [],
            selected_price: null,
            generate_payment: true,
        }
    },
    computed: {
        isProduct() {
            return this.type == 'Produto'
        },
        canCreate() {
            if (this.generate_payment) return this.subtotal > 0 && this.items.length > 0
            return this.items.length > 0
        },
        selected_product() {
            if (!this.product_id) return this.cleanForm()
            this.qty = 1
            let p = this.products.find((x) => x.id === this.product_id)
            this.selected_price = p.price
            return p
        },
        total() {
            if (!this.product_id) return 0
            let product = this.products.find((x) => x.id === this.product_id)
            if (this.qty < 1) return (0).toFixed(2)
            return (Number(this.qty) * Number(this.selected_price)).toFixed(2)
        },
        subtotal() {
            let value = 0
            this.items.forEach((item) => (value += Number(item.total)))
            return value
        },
    },
    methods: {
        initializeProductMode() {
            this.generate_payment = false
        },
        deleteItem(item) {
            this.$confirm(`Deseja remover item (${item.name}) do lançamento ? `, 'Confirmação', {
                confirmButtonText: 'Sim',
                cancelButtonText: 'Não',
                type: 'warning',
            })
                .then(() => {
                    this.items = this.items.filter((x) => x.id != item.id)
                })
                .catch(() => false)
        },
        showModal() {
            this.$modal.show('modal_sales')
            this.getProducts()
        },
        push() {
            if (this.qty <= 0) return this.$message({ showClose: true, message: 'Quantidade do item deve ser maior que 0', type: 'warning' })
            let founded_item = this.items.find((x) => x.id == this.product_id && x.price == this.selected_price)
            if (founded_item) {
                founded_item.qty += Number(this.qty)
                founded_item.total = Number(this.selected_price) * Number(founded_item.qty)
            } else {
                this.items.push({
                    id: this.product_id,
                    qty: Number(this.qty),
                    price: Number(this.selected_price),
                    name: this.selected_product.name,
                    total: Number(this.qty) * Number(this.selected_price),
                    obs: this.obs,
                })
            }
            this.cleanForm()
        },
        cleanForm() {
            this.product_id = null
            this.obs = null
            this.qty = null
            this.selected_price = null
            this.generate_payment = true
            if (this.isProduct) this.initializeProductMode()
        },
        submit() {
            this.$confirm(`Confirma lançamento ?`, 'Confirmação', {
                confirmButtonText: 'Sim',
                cancelButtonText: 'Não',
                type: 'warning',
            })
                .then(() => {
                    let data = {
                        customer_id: this.customer.id,
                        items: this.items,
                        subtotal: this.subtotal,
                        payment: this.generate_payment,
                        type: this.type,
                        product_status: this.type == 'Produto' ? 'Aguardando' : null,
                    }
                    this._loading = this.$loading()
                    this.$http
                        .post(laravel.general.root_url + '/admin/customers/post_new_sale', data)
                        .then((res) => {
                            window.location.reload()
                        })
                        .catch((er) => {
                            this._loading.close()
                            console.log(er)
                            this.$message({ showClose: true, message: 'Erro ao salvar', type: 'error' })
                        })
                })
                .catch(() => false)
        },
        getProducts() {
            this.loading = true
            this.$http
                .post(`/admin/api/get-data/getProduct`, { type: this.type })
                .then((res) => {
                    res = res.data
                    this.products = res
                    this.loading = false
                })
                .catch((er) => {
                    this.loading = false
                    this.initialize()
                    console.log(er)
                })
        },
    },
}
</script>
<style scoped lang="scss">
.item-content {
    max-height: 300px;
    overflow-y: auto;
    .table-items {
        width: 100%;
        font-size: 12px;
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        td {
            height: 18px;
        }
    }
}
</style>