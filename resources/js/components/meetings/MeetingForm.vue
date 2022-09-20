<template>
    <el-form ref="form" :model="{ form, extra }" class="row" label-position="left"
        :class="{ 'creating-meeting': !form.id }" v-loading.fullscreen.lock="sending"
        :element-loading-text="sendingText">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <h4>Informações</h4>
                    <div class="row">
                        <div class="col-sm-6">
                            <el-form-item label="Assunto da Reunião" prop="form.subject" required>
                                <el-input v-model="form.subject" placeholder="Assunto" />
                            </el-form-item>
                            <div class="row">
                                <div class="col-lg-7">
                                    <el-form-item prop="form.customer_id" label="Cliente" required>
                                        <el-select v-model="form.customer_id" :disabled="customer_id" filterable>
                                            <el-option v-for="status in modelsData.customers" :key="status.id"
                                                :value="status.id" :label="status.name" />
                                        </el-select>
                                    </el-form-item>
                                </div>
                                <div class="col-lg-5">
                                    <el-form-item label="Tipo" required prop="form.type">
                                        <el-select v-model="form.type" placeholder="Orçamento, pesquisa etc" filterable>
                                            <el-option label="Análise" value="analise" />
                                            <el-option label="Consultoria" value="consultoria" />
                                            <el-option label="Serviço" value="servico" />
                                        </el-select>
                                    </el-form-item>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm">
                            <status-card @send="submit" v-bind="{ form, modelsData, extra }" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm mt-3">
            <location-card v-bind="{ form, modelsData, extra }" />
        </div>
        <div class="col-12 mt-3 justify-content-end d-flex align-items-center flex-wrap" v-if="!form.id">
            <a v-if="!isModal" href="/admin/meetings" class="mr-5 text-danger link d-none d-lg-block">
                <b>Cancelar</b>
            </a>
            <button class="btn btn-primary btn-sm-block" :disabled="sending" @click.prevent="submit">Cadastrar</button>
        </div>
        <before-send-dialog @email="putEmailContent" @send="send" @no-email="removeEmailContent" ref="modal"
            v-bind="{ form }" />
    </el-form>
</template>

<script>
import LocationCard from './-locationCard'
import StatusCard from './-statusCard'
import BeforeSendDialog from './dialog/-beforeSendDialog'
export default {
    components: { LocationCard, BeforeSendDialog, StatusCard },
    props: ['config', 'isModal', 'customer_id'],
    data() {
        return {
            sending: false,
            form: {
                id: false,
                subject: null,
                type: null,
                status_id: '',
                starts_at: '',
                ends_at: '',
                customer_id: this.customer_id ? this.customer_id : '',
                feedback_url: null,
                meeting_room_id: null,
                google_event_id: 0,
                observations: '',
            },
            extra: {
                meeting_duration: ['12', '14'],
                scheduleLinkButton: true,
                create_event: true,
                sendUpdateEmail: true,
                customEmail: false,
                email: { subject: '', body: '' },
                updateMessage: '',
            },
            modelsData: {
                meetingRooms: [],
                meetingStatuses: [],
                customers: [],
            },
            attempts: {
                meetingRooms: 0,
                meetingStatuses: 0,
                customers: 0,
            },
        }
    },
    created() {
        this.loadCustomers()
        this.loadMeetingRooms()
        this.loadMeetingStatuses()
        if (this.config) {
            this.form = this.config.form
            this.extra.meeting_duration = this.config.meeting_duration
        }
    },
    computed: {
        getPostUrl() {
            return this.isModal ? '/admin/meetings/create' : ''
        },
        sendingText() {
            let exists = !this.form.id ? 'Criando reunião' : 'Atualizando reunião ',
                sending = this.extra.sendUpdateEmail ? ' e enviando Email' : ''

            return exists + sending + '...'
        },
    },
    methods: {
        putEmailContent(content) {
            this.extra.customEmail = true
            this.extra.email = content
        },
        removeEmailContent() {
            this.extra.customEmail = false
            this.extra.email = { subject: '', body: '' }
        },
        loadMeetingStatuses() {
            this.attempts.meetingStatuses++
            this.$http
                .get('/vstack/json-api', {
                    params: { model: `\\App\\Http\\Models\\MeetingStatus` }
                })
                .then(({ data }) => (this.modelsData.meetingStatuses = data))
                .catch((er) => {
                    if (this.attempts.meetingStatuses <= 3) return this.loadMeetingStatuses()
                    console.log(er)
                })
        },
        loadMeetingRooms() {
            this.attempts.meetingRooms++
            this.$http
                .get('/vstack/json-api', { params: { model: `\\App\\Http\\Models\\MeetingRoom` } })
                .then(({ data }) => (this.modelsData.meetingRooms = data))
                .catch((er) => {
                    if (this.attempts.meetingRooms <= 3) return this.loadMeetingRooms()
                    console.log(er)
                })
        },
        loadCustomers() {
            this.attempts.customers++
            this.$http
                .get('/vstack/json-api', {
                    params: {
                        model: `\\App\\Http\\Models\\Customer`
                    }
                })
                .then(({ data }) => (this.modelsData.customers = data))
                .catch((er) => {
                    if (this.attempts.customers <= 3) return this.loadCustomers()
                    console.log(er)
                })
        },
        submit() {
            this.$refs.form.validate((valid) => {
                if (!valid) return

                if (this.extra.sendUpdateEmail) {
                    this.$refs.modal.open()
                } else {
                    this.send()
                }
            })
        },
        validationErrors(errors) {
            let list = []
            for (let bag of Object.values(errors)) {
                list.push(...bag)
            }
            return list
        },
        alertErrors(messages) {
            let bg = document.createElement('div')
            bg.setAttribute('id', 'overlei')
            document.body.appendChild(bg)
            let notification = {}

            let el = this.$createElement
            let closeNotification = (v) => notification.close()

            notification = this.$message.error({
                message: el('div', [el('p', { class: 'notification-message' }, messages)]),
                showClose: true,
                customClass: 'senpai-notice-meee',
                onClose: (v) => {
                    bg.remove()
                },
            })
        },
        send() {
            this.sending = true
            this.$http
                .post(this.getPostUrl, {
                    model: this.form,
                    time: this.extra.meeting_duration,
                    extra: this.extra,
                })
                .finally((v) => (this.sending = false))
                .then(({ data }) => {
                    if (this.isModal) return window.location.reload()
                    location.replace('/admin/meetings')
                })
                .catch(({ response }) => {
                    if (response.status === 422) {
                        this.alertErrors(this.validationErrors(response.data.errors))
                    } else {
                        this.alertErrors(response.data.message)
                    }
                })
        },
    },
}
</script>

<style lang="scss">
.creating-meeting {
    .status-card {
        border: none;
        box-shadow: none;

        >.card-body {
            padding-top: 0;
        }
    }
}

.el-range-separator {
    width: 10% !important;
}

.el-form {
    .el-select {
        width: 100%;
    }
}

.el-slider__marks {
    >div {
        word-break: normal;
    }
}
</style>