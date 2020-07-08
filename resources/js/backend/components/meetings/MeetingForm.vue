<template>
    <el-form ref="form" class="row" label-position="left" :class="{'creating-meeting': !form.id}">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <h4>Informações</h4>
                    <div class="row">
                        <div class="col-sm-6">
                            <el-form-item label="Assunto da Reunião" required>
                                <el-input v-model="form.subject" placeholder="Assunto" />
                            </el-form-item>
                            <div class="row">
                                <div class="col-lg-7">
                                    <el-form-item label="Cliente" required>
                                        <el-select v-model="form.customer_id">
                                            <el-option
                                                v-for="status in modelsData.customers"
                                                :key="status.id"
                                                :value="status.id"
                                                :label="status.name"
                                            />
                                        </el-select>
                                    </el-form-item>
                                </div>
                                <div class="col-lg-5">
                                    <el-form-item label="Tipo" required>
                                        <el-input
                                            v-model="form.type"
                                            placeholder="Orçamento, pesquisa etc"
                                        />
                                    </el-form-item>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm">
                            <status-card @send="submit" v-bind="{form,modelsData,extra}" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm mt-3">
            <location-card v-bind="{form,modelsData,extra}" />
        </div>
        <div
            class="col-12 mt-3 justify-content-end d-flex align-items-center flex-wrap"
            v-if="!form.id"
        >
            <a href="/admin/meetings" class="mr-5 text-danger link d-none d-lg-block">
                <b>Cancelar</b>
            </a>
            <button
                class="btn btn-primary btn-sm-block"
                :disabled="sending"
                @click.prevent="submit"
            >Cadastrar</button>
        </div>
        <before-send-dialog
            @email="putEmailContent"
            @send="send"
            @no-email="removeEmailContent"
            ref="modal"
            v-bind="{form}"
        />
    </el-form>
</template>

<script>
import LocationCard from './-locationCard'
import StatusCard from './-statusCard'
import BeforeSendDialog from './dialog/-beforeSendDialog'
export default {
    components: { LocationCard,BeforeSendDialog,StatusCard },
    props: ["config"],
    data() {
        return {
            sending: false,
            form: {
                id: false,
                subject: null,
                type: null,
                status_id: "",
                starts_at: "",
                ends_at: "",
                customer_id: "",
                feedback_url: null,
                meeting_room_id: null,
                google_event_id: 0,
                observations: ""
            },
            extra: {
                meeting_duration: ["12","14"],
                create_event: true,
                sendUpdateEmail: true,
                customEmail: false,
                email: { subject: "",body: "" },
            },
            modelsToLoad: [["Customer","customers"],["MeetingRoom","meetingRooms"],["MeetingStatus","meetingStatuses"]],
            modelsData: {
                meetingRooms: [],
                meetingStatuses: [],
                customers: []
            }

        }
    },
    created() {
        this.loadModelData()
        if (this.config) {
            this.form = this.config.form
            this.extra.meeting_duration = this.config.meeting_duration
        }
    },
    methods: {
        putEmailContent(content) {
            this.extra.customEmail = true
            this.extra.email = content
        },
        removeEmailContent() {
            this.extra.customEmail = false
            this.extra.email = { subject: "",body: "" }
        },
        loadModelData() {
            for (let modelData of this.modelsToLoad) {
                const [model,field] = modelData
                this.$http.post("/admin/inputs/option_list",{ model: `App\\Http\\Models\\${model}` })
                    .then(r => this.modelsData[field] = r.data.data)
            }
        },
        submit() {
            //this.$refs.form.validate(valid => {
            //    if (!valid) return
            this.$refs.modal.open()
            //this.send()
            //})
        },
        send() {
            this.sending = true
            this.$http.post("",{
                model: this.form,
                time: this.extra.meeting_duration,
                extra: this.extra
            })
                .finally(v => this.sending = false)
                .then(({ data }) => {
                    if (!this.form.id && data.id) {
                        location.replace("/admin/meetings")
                    } else if (this.form.id && data.id) {
                        location.reload()
                    }
                })
                .catch((response) => {
                    console.log(response)
                })
        }
    }
}
</script>

<style lang="scss">
.creating-meeting {
    .status-card {
        border: none;
        box-shadow: none;
        > .card-body {
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
</style>