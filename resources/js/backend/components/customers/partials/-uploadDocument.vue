<template>
    <div class="mt-3">
        <b :class="`${qtyFiles > 0 ? 'text-success' : 'text-danger'}`">Transferência Bancária</b>
        <el-upload
            :headers="header"
            :action="uploadroute"
            :on-success="handleAvatarSuccess"
            :on-preview="handlePreview"
            :on-remove="handleRemove"
            :before-remove="beforeRemove"
            multiple
            :limit="1"
            :file-list="fileList"
        >
            <el-button size="small" type="primary" v-if="qtyFiles <= 0">Enviar Comprovante</el-button>
        </el-upload>
    </div>
</template>
<script>
export default {
    props: ['customer', 'sale'],
    data: () => ({
        fileList: [],
        header: { 'X-CSRF-TOKEN': laravel.general.csrf_token ? laravel.general.csrf_token : '' },
        uploadroute: laravel.vstack.default_upload_route ? laravel.vstack.default_upload_route : '',
    }),
    computed: {
        qtyFiles() {
            return this.fileList.length
        },
    },
    created() {
        if (!this.sale.data) return
        if (!this.sale.data.documents) return
        this.fileList = this.sale.data.documents
    },
    methods: {
        update() {
            this.$http
                .post('/admin/sales/update-document', {
                    sale_id: this.sale.id,
                    files: this.fileList,
                })
                .then((res) => {
                    this.loading.close()
                    this.modalEmail = false
                })
        },
        handleAvatarSuccess(res, file) {
            this.fileList.push(file)
            this.update()
        },
        handleRemove(file, fileList) {
            this.fileList = this.fileList.filter((x) => x.uid != file.uid)
            this.update()
        },
        handlePreview(file) {
            window.open(file.response.path, '_blank')
        },
        beforeRemove(file, fileList) {
            return this.$confirm(`Excluir comprovante de pagamento ?`)
        },
    },
}
</script>