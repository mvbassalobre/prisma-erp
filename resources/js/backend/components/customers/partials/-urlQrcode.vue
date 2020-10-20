<template>
    <div>
        <div class="d-flex justify-content-center align-content-center" v-loading="qrCodeLoading" style="width: 100%; height: 100%; border: 1px solid #cecece">
            <canvas ref="qrCode" />
        </div>
        <div class="d-flex justify-content-between">
            <a class="link text-center" @click.prevent="clipboardPayment(url)" :href="url" target="_BLANK"> Copiar Link</a>
            <a class="link text-center" @click.prevent="openEmailModal" href="#"> Enviar Link por Email</a>
        </div>

        <el-dialog title="Enviar URL de cobrança para o email" :visible.sync="modalEmail" width="30%" center>
            <input class="form-control" v-model="email" placeholder="Digite o endereço de email" />
            <template slot="footer">
                <div class="d-flex justify-content-end">
                    <button class="btn btn-primary" :disabled="!email" @click="sendEmail" type="button">Enviar</button>
                </div>
            </template>
        </el-dialog>
    </div>
</template>
<script>
import QRCode from 'qrcode'
export default {
    props: ['url', 'default_email', 'customer'],
    data() {
        return {
            qrCodeLoading: true,
            email: this.default_email ? this.default_email : '',
            modalEmail: false,
            loading: null,
        }
    },
    mounted() {
        this.generateQrCode()
    },
    methods: {
        openEmailModal() {
            this.modalEmail = true
            this.email = this.default_email ? this.default_email : ''
        },
        sendEmail() {
            this.$confirm(`Enviar Email ?`, 'Confirmação', {
                confirmButtonText: 'Sim',
                cancelButtonText: 'Não',
                type: 'warning',
            }).then(() => {
                this.loading = this.$loading()
                this.$http
                    .post('/admin/sales/send-url-email', {
                        email: this.email,
                        customer_name: this.customer.name,
                        url: this.url,
                    })
                    .then((res) => {
                        this.loading.close()
                        this.modalEmail = false
                    })
            })
        },
        generateQrCode() {
            QRCode.toCanvas(this.$refs.qrCode, this.url, () => {
                this.qrCodeLoading = false
            })
        },
        clipboardPayment(str) {
            String(str).toClipboard((res) => {
                this.$message({ showClose: true, message: 'Url copiada !!!!', type: 'info' })
            })
        },
    },
}
</script>