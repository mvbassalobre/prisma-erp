<template>
    <el-dialog
        class="pick-options-dialog"
        :visible.sync="visible"
        v-bind="modalSettings"
        ref="dialog"
    >
        <div class="pick-option">
            <div class="btn btn-secondary" @click="close">
                <i class="el-icon-error" />
                <p>Voltar e Continuar Editando</p>
            </div>
            <div class="btn btn-info" @click="send">
                <i class="el-icon-s-promotion" />
                <p>Enviar Email Normal</p>
            </div>
            <div class="btn btn-primary" @click="openEmailComposer">
                <i class="el-icon-edit-outline" />
                <p>Customizar Email</p>
            </div>
        </div>
        <email-composer-dialog ref="emailComposer" :form="form" @send="send" />
    </el-dialog>
</template>

<script>
import EmailComposerDialog from "./-emailComposerDialog"
export default {
    components: { EmailComposerDialog },
    props: ["form"],
    data() {
        return {
            visible: false,
            modalSettings: {
                'append-to-body': true,
                'show-close': false
            }
        }
    },
    methods: {
        send(content) {
            this.visible = false
            this.$emit("email",content)
            this.$emit("send")
        },
        openEmailComposer() {
            this.$refs.emailComposer.open()
        },
        open() {
            this.visible = true
        },
        close(done) {
            this.visible = false
            this.$emit("no-email")
        }
    }
}
</script>

<style lang="scss">
.pick-options-dialog {
    .el-dialog__header {
        padding: 0px;
    }
}
.pick-option {
    display: flex;
    flex-direction: row;
    justify-content: center;
    flex-wrap: wrap;
    > div {
        margin: 10px;
        min-width: 210px;
        height: 210px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        > i {
            font-size: 160px;
        }
        > p {
        }
    }
}
</style>