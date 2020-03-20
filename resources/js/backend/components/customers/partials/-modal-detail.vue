<template>
    <modal
        name="modal_detail"
        :clickToClose="false"
        :height="530"
        :width="'80%'"
        :resizable="false"
        :adaptive="true"
    >
        <div class="card h-100" v-if="sale">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div>
                    Lançamento
                    <b>{{sale.f_code}}</b> em detalhes
                </div>
                <a @click.prevent="$modal.hide('modal_detail')">
                    <i class="el-icon-close"></i>
                </a>
            </div>
            <div class="card-body d-flex flex-column" v-loading="loading" style="overflow-y: auto;">
                <a class="link" :href="sale.payment.url" target="_BLANK">{{sale.payment.url}}</a>
                <div v-loading="qrCodeLoading" style="width: 196px;height: 196px;">
                    <canvas ref="qrCode" />
                </div>
            </div>
        </div>
    </modal>
</template>
<script>
import QRCode from 'qrcode'
export default {
    props: ["customer"],
    data() {
        return {
            loading: null,
            sale: null,
            qrCodeLoading: true
        }
    },
    methods: {
        showModal(s) {
            this.sale = s
            this.$modal.show('modal_detail')
            this.generateQrCode()
        },
        generateQrCode() {
            setTimeout(() => {
                QRCode.toCanvas(this.$refs.qrCode, this.sale.payment.url, (res) => {
                    this.qrCodeLoading = false
                })
            }, 100)
        }
    }
}
</script>