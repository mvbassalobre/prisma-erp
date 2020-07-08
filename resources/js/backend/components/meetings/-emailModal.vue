<template>
    <el-dialog :visible.sync="visible" v-bind="modalSettings" ref="dialog">
        <div class="row-responsible-table">
            <table class="table table-striped">
                <v-input v-model="subject" label="Assunto" />
                <v-summernote v-model="body" v-bind="summernoteSettings" />
            </table>
        </div>
        <span slot="footer" class="dialog-footer">
            <el-button
                size="small"
                type="danger"
                @click="closeModalAndSend"
            >Atualizar e não enviar email</el-button>
            <el-button
                size="small"
                type="primary"
                @click="confirm"
                :disabled="!body || !subject"
            >Atualizar e enviar</el-button>
        </span>
    </el-dialog>
</template>

<script>
export default {
    props: ["form"],
    data() {
        return {
            visible: false,
            subject: "",
            body: "",
            modalSettings: {
                'close-on-click-modal': false,
                'close-on-press-escape': false,
                'before-close': this.close,
                'append-to-body': true,
                //'width': "30%",
                'title': "Enviar Email"
            },
            summernoteSettings: {
                label: "Corpo do Email",
                toolbar: [
                    ['style',['bold','italic','underline','clear']],
                    ['font',['strikethrough','superscript','subscript']],
                    ['para',['ul','ol','paragraph']],
                    ['fontsize',['fontsize']],
                    ['color',['color']],
                    ['insert',['link']],
                    ['misc',['codeview']],
                    ['height',[]]
                ]
            }
        }
    },
    methods: {
        open() {
            this.visible = true
        },
        openConfirmDialog(title) {
            return this.$confirm(title,{
                cancelButtonClass: "btn-danger",
                confirmButtonClass: ""
            })
        },
        confirm() {
            this.openConfirmDialog('Enviar email e atualizar dados?')
                .then(_ => {
                    this.$emit("email",{ subject: this.subject,body: this.body })
                    this.visible = false
                    this.$emit("send")
                })
                .catch(v => {
                    this.$emit("no-email")
                })
        },
        closeModalAndSend() {
            this.$confirm('Não enviar email e atualizar dados?')
                .then(_ => {
                    visible = false
                    this.emit("no-email")
                    this.$emit("send")
                })
                .catch(v => v)

        },
        close(done) {
            this.$confirm('Tem certeza que quer fechar esta janela?')
                .then(_ => {
                    done()
                })
                .catch(console.log)
        }
    }
}
</script>

<style>
</style>