<template>
    <el-dialog :visible.sync="visible" v-bind="modalSettings" ref="dialog">
        <div class="row-responsible-table">
            <table class="table table-striped">
                <v-input v-model="subject" label="Assunto" />
                <v-summernote v-model="body" v-bind="summernoteSettings" />
            </table>
        </div>
        <span slot="footer" class="dialog-footer">
            <el-button size="small" type="danger" @click="$refs.dialog.close()">Voltar</el-button>
            <el-button
                size="small"
                type="primary"
                @click="confirm"
                :disabled="!body || !subject"
            >Inserir dados e Enviar Email</el-button>
        </span>
    </el-dialog>
</template>

<script>
export default {
    props: ["form"],
    data() {
        return {
            visible: false,
            subject: "Reunião: " + this.form.subject,
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
                label: "Conteúdo",
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
        confirm() {
            this.$emit("email",{ subject: this.subject,body: this.body })
            this.visible = false
            this.$emit("send")
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