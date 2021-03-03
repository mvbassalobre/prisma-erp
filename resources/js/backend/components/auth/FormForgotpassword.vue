<template>
    <div class="col-md-7 col-sm-12 p-3" v-loading="loading">
        <div class="card">
            <div class="card-body">
                <div class="brand-logo d-flex justify-content-center align-items-center mt-3">
                    <img src="/assets/images/logo.png" alt="logo" />
                    <div class="d-flex flex-column title">
                        <b class="name">Prisma</b>
                        <div class="sub">ezCore</div>
                    </div>
                </div>
                <h4>Esqueceu sua senha ?</h4>
                <h6 class="font-weight-light">Digite seu email e enviaremos um email explicando como proceder.</h6>
                <slot name="alerts"></slot>
                <form class="needs-validation mt-4" novalidate v-on:submit.prevent="submit">
                    <v-input
                        class="mb-3"
                        label="Email"
                        type="email"
                        v-model="frm.email"
                        placeholder="Digite aqui seu email ..."
                        :errors="errors.email ? errors.email : false"
                    />
                    <button class="btn btn-secondary btn-block mt-4 mb-2" type="submit">Enviar email de renovação de senha</button>
                    <div class="text-center">
                        Voltar para a página de
                        <a href="login" class="link ml-2">Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            loading: false,
            errors: {},
            frm: {
                email: null,
                password: null,
                remember: false,
            },
        }
    },
    methods: {
        submit() {
            this.loading = true
            this.$http
                .post('', this.frm)
                .then((res) => {
                    let data = res.data
                    if (data.message) this.$message({ showClose: true, message: data.message.text, type: data.message.type })
                    if (data.success) return (window.location.href = data.route)
                    this.loading = false
                })
                .catch((er) => {
                    let errors = er.response.data.errors
                    this.errors = errors
                    this.loading = false
                })
        },
    },
}
</script>
<style scoped lang="scss">
.login-page {
    .right-side {
        .brand-logo {
            margin-bottom: 2rem;
            img {
                width: 80px;
            }
            .title {
                position: relative;
                .name {
                    font-size: 45px;
                }
                .sub {
                    font-size: 12px;
                    color: rgb(182, 182, 182);
                    top: 50px;
                    right: 0;
                    font-weight: 500;
                    position: absolute;
                }
            }
        }
    }
}
</style>