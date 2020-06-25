<template>
    <div style="visibility: hidden;" ref="content">
        <component-area v-if="logged" :customer="customer" :logo="logo" />
        <div v-loading="loading" v-else>
            <div class="login-page">
                <div class="row h-100 d-flex justify-content-center align-items-center">
                    <div
                        class="col-md-6 col-sm-12 d-flex align-items-center justify-content-center right-side"
                    >
                        <div class="card">
                            <div class="card-body">
                                <div
                                    class="brand-logo d-flex justify-content-center align-items-center mt-3"
                                >
                                    <img :src="logo" alt="logo" />
                                </div>
                                <h4>Bem-Vindo !</h4>
                                <h6
                                    class="font-weight-light"
                                >Preencha o formulário para acessar a area de cliente</h6>
                                <slot name="alerts"></slot>
                                <form
                                    class="needs-validation mt-4"
                                    novalidate
                                    v-on:submit.prevent="submit"
                                >
                                    <table class="w-100 my-3">
                                        <v-input
                                            class="mb-3"
                                            v-model="frm.username"
                                            placeholder="Digite aqui acesso  ..."
                                            :errors="errors.username ? errors.username : false"
                                        />
                                        <v-input
                                            class="mb-3"
                                            type="password"
                                            v-model="frm.password"
                                            placeholder="Digite aqui sua senha ..."
                                            :errors="errors.password ? errors.password : false"
                                        />
                                    </table>
                                    <div
                                        class="d-flex flex-row flex-wrap justify-content-between align-items-center"
                                    >
                                        <div>
                                            <el-checkbox
                                                class="d-flex align-items-center"
                                                v-model="frm.remember"
                                            >Manter-me Logado</el-checkbox>
                                        </div>
                                    </div>
                                    <button
                                        class="btn btn-secondary btn-block mt-4 mb-2"
                                        type="submit"
                                    >Login</button>
                                </form>
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
    props: ["logo"],
    data() {
        return {
            loading: false,
            errors: {},
            frm: {
                username: null,
                password: null,
                remember: true
            },
            logged: false,
            customer: {}
        }
    },
    components: {
        "component-area": require("./partials/-comp-area.vue").default
    },
    mounted() {
        let code = Cookies.get("customer_area_user")
        if (code) return this.logByCode(code)
        this.showContent()
    },
    methods: {
        showContent() {
            this.$refs.content.style.visibility = "visible"
        },
        logByCode(code) {
            this.$http.post("", { code }).then(res => {
                let data = res.data
                if (data.success) {
                    this.logged = true
                    let data = res.data
                    this.customer = data.customer
                    this.showContent()
                } else {
                    this.logged = false
                    this.customer = {}
                    return this.showContent()
                }
            }).catch(er => {
                let errors = er.response.data.errors
                this.errors = errors
                this.loading = false
            })
        },
        submit() {
            this.loading = true
            this.$http.post("", this.frm).then(res => {
                let data = res.data
                if (!data.success) {
                    this.logged = false
                    this.customer = {}
                    this.$message({ showClose: true, message: "Usuário ou senha incorreta", type: "danger" })
                    Cookies.remove("customer_area_user")
                    return this.loading = false
                } else {
                    this.logged = true
                    this.customer = data.customer
                    this.$message({ showClose: true, message: "Acesso Permitido", type: "success" })
                    if (this.frm.remember) Cookies.set("customer_area_user", data.customer.code)
                    return this.loading = false
                }
            }).catch(er => {
                this.customer = {}
                let errors = er.response.data.errors
                this.errors = errors
                this.loading = false
            })
        }
    }
}
</script>
<style scoped lang="scss">
.login-page {
    .right-side {
        .brand-logo {
            margin-bottom: 2rem;
            img {
                width: 200px;
            }
        }
    }
}
</style>