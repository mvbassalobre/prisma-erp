<template>
    <div
        class="tab-pane fade f-12"
        v-bind:class="{'show active' : active == 'access'}"
        id="v-pills-access"
        role="tabpanel"
        aria-labelledby="v-pills-access-tab"
    >
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h5>
                            <span class="el-icon-s-finance mr-2"></span>Configuração de Acesso
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <table class="table table-striped mb-0">
                                    <tbody>
                                        <tr>
                                            <td class="pt-3">
                                                <b>Usuário</b>
                                            </td>
                                            <td>
                                                <input
                                                    class="form-control"
                                                    type="text"
                                                    v-model="form.username"
                                                    disabled
                                                />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="pt-3">
                                                <b>Nova Senha</b>
                                            </td>
                                            <td>
                                                <input
                                                    class="form-control"
                                                    type="password"
                                                    v-model="form.newpass"
                                                />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="pt-3">
                                                <b>Confirme a Senha</b>
                                            </td>
                                            <td>
                                                <input
                                                    class="form-control"
                                                    type="password"
                                                    v-model="form.confirm"
                                                />
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-12 text-right">
                                <button
                                    class="btn btn-primary"
                                    tyoe="button"
                                    @click="savePass"
                                >Alterar Senha</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
    data() {
        return {
            loading: false,
            form: {
                username: this.customer.username,
                newpass: null,
                confirm: null,
            }
        }
    },
    methods: {
        savePass(p) {
            if (!this.form.newpass || !this.form.confirm) return this.$message.error('Senha inválida')
            if (this.form.newpass != this.form.confirm) return this.$message.error('Senha e confirmação não são iguais')
            this.$confirm(`Confirma Alteração de Senha ?`, "Confirmação", {
                confirmButtonText: "Sim",
                cancelButtonText: "Não",
                type: 'warning'
            }).then(() => {
                this.loading = this.$loading()
                this.$http.post(laravel.general.root_url + "/admin/customers/change_pass", { customer_id: this.customer.id, ...this.form }).then(res => {
                    window.location.reload()
                }).catch(er => {
                    this.loading.close()
                    console.log(er)
                    this.$message({ showClose: true, message: "Erro ao alterar", type: "error" })
                })
            })
        },
    }
}
</script>