<template>
    <modal
        name="modal"
        :clickToClose="false"
        :height="'80%'"
        :width="'80%'"
        :resizable="false"
        :adaptive="true"
    >
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div>
                    <span class="el-icon-plus mr-2"></span>
                    Adicionar lançamento ao cliente
                    <b>{{customer.name}}</b>
                </div>
                <a @click.prevent="$modal.hide('modal')">
                    <i class="el-icon-close"></i>
                </a>
            </div>
            <div class="card-body d-flex flex-column" v-loading="loading">
                <div class="row">
                    <div class="col-md-7 col-sm-12">
                        <div class="d-flex flex-column">
                            <div class="d-flex flex-column">
                                <el-select
                                    v-model="product_id"
                                    filterable
                                    placeholder="Selecione o produto"
                                >
                                    <el-option :key="0" label value />
                                    <el-option
                                        v-for="p in products"
                                        :key="p.id"
                                        :label="`${p.id.pad(6)} - ${p.name}`"
                                        :value="p.id"
                                    ></el-option>
                                </el-select>
                                <small>Selecione um produto / serviço ao lançamento</small>
                            </div>
                            {{selected_product}}
                        </div>
                    </div>
                    <div class="col-md-5 col-sm-12">items</div>
                </div>
                <div class="row mt-auto">
                    <div class="col-12">
                        <div class="d-flex flex-row align-items-center justify-content-end">
                            <a
                                class="text-danger mr-3"
                                href="#"
                                @click.prevent="$modal.hide('modal')"
                            >Cancelar</a>
                            <button class="btn btn-primary" @click="submit">Salvar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </modal>
</template>
<script>
export default {
    props: ["customer"],
    data() {
        return {
            loading: false,
            products: [],
            product_id: null
        }
    },
    computed: {
        selected_product() {
            if (!this.product_id) return
            return this.products.find(x => x.id === this.product_id)
        }
    },
    methods: {
        showModal() {
            this.$modal.show('modal')
            this.getProducts()
        },
        submit() {
            console.log("submiteu")
            // if (!this.form.product_id) return this.$message({ showClose: true, message: "Selecione o produto antes de confirmar", type: "error" })
            // this.loading = this.$loading()
            // this.$http.post(laravel.general.root_url + "/admin/customers/post_new_product", this.form).then(res => {
            //     window.location.reload()
            // }).catch(er => {
            //     this.loading.close()
            //     console.log(er)
            //     this.$message({ showClose: true, message: "Erro ao salvar", type: "error" })
            // })
        },
        getProducts() {
            this.loading = true
            this.$http.post(`${laravel.general.root_url}/admin/inputs/option_list`, { model: '\\App\\Http\\Models\\Product' }).then(res => {
                res = res.data
                this.products = res.data
                this.loading = false
            }).catch(er => {
                this.loading = false
                this.initialize()
                console.log(er)
            })
        }
    }
}
</script>