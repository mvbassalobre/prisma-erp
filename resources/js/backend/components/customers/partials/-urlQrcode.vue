<template>
    <div>
        <div class="d-flex justify-content-center align-content-center" v-loading="qrCodeLoading" style="width: 100%; height: 100%; border: 1px solid #cecece">
            <canvas ref="qrCode" />
        </div>
        <a class="link text-center" @click.prevent="clipboardPayment(url)" :href="url" target="_BLANK"> Copiar Link</a>
    </div>
</template>
<script>
import QRCode from 'qrcode'
export default {
    props: ['url'],
    data: () => ({
        qrCodeLoading: true,
    }),
    mounted() {
        this.generateQrCode()
    },
    methods: {
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