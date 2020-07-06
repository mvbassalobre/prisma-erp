<template>
    <el-form :inline="true" class="row" label-position="top" size="medium">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <h4>Informações</h4>
                    <div class="row">
                        <div class="col-sm-6">
                            <el-form-item label="Assunto da Reunião" required>
                                <el-input v-model="form.subject" placeholder="Assunto" />
                            </el-form-item>
                            <el-form-item label="Local da Reunião" required>
                                <el-select v-model="form.meeting_room_id">
                                    <el-option
                                        v-for="status in modelsData.meetingRooms"
                                        :key="status.id"
                                        :value="status.id"
                                        :label="status.name"
                                    />
                                </el-select>
                            </el-form-item>
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
                            <el-form-item label="Data" required>
                                <el-date-picker v-model="form.starts_at" placeholder="Início"></el-date-picker>
                            </el-form-item>
                            <el-form-item
                                :label="`Horário: de ${durationFormatter(meeting_duration[0])} até ${durationFormatter(meeting_duration[1])}`"
                            >
                                <div class="col-sm-12">
                                    <el-slider
                                        range
                                        :marks="meeting_duration_marks"
                                        v-model="meeting_duration"
                                        :step="0.5"
                                        :max="24"
                                        :format-tooltip="durationFormatter"
                                    />
                                </div>
                            </el-form-item>
                        </div>
                        <div class="col-sm-6">
                            <div class="card">
                                <div class="card-body">
                                    <h4>Detalhes</h4>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <el-form-item label="Tipo de Reunião" required>
                                                <el-input
                                                    v-model="form.type"
                                                    placeholder="Orçamento, pesquisa etc"
                                                />
                                            </el-form-item>
                                        </div>
                                        <div class="col-sm-6">
                                            <el-form-item label="Status" required>
                                                <el-select v-model="form.status_id">
                                                    <el-option
                                                        v-for="status in modelsData.meetingStatuses"
                                                        :key="status.id"
                                                        :value="status.id"
                                                        :label="status.name"
                                                    />
                                                </el-select>
                                            </el-form-item>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <el-form-item label="Observações">
                                                <el-input
                                                    type="textarea"
                                                    v-model="form.observations"
                                                />
                                            </el-form-item>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <el-switch
                                                v-model="showGFormField"
                                                active-text="Enviar formulário de feedback"
                                            ></el-switch>
                                            <el-input
                                                class="mt-3"
                                                v-if="showGFormField"
                                                v-model="form.feedback_url"
                                                placeholder="Url do formulário"
                                            />
                                        </div>
                                        <div class="col-sm-6">
                                            <el-switch
                                                v-model="form.google_event_id"
                                                active-text="Criar evento no Google Calendar"
                                            ></el-switch>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 mt-3 justify-content-end d-flex align-items-center flex-wrap">
            <a href="/admin/meetings" class="mr-5 text-danger link d-none d-lg-block">
                <b>Cancelar</b>
            </a>
            <button type="sumit" class="btn btn-primary btn-sm-block">Cadastrar</button>
        </div>
    </el-form>
</template>

<script>
export default {
    data() {
        return {
            form: {
                subject: null,
                type: null,
                status_id: "",
                starts_at: +(new Date()),
                customer_id: "",
                feedback_url: null,
                meeting_room_id: null,
                resource_id: "meetings",
                google_event_id: true
            },
            modelsToLoad: [["Customer","customers"],["MeetingRoom","meetingRooms"],["MeetingStatus","meetingStatuses"]],
            starts_at_time: "12:00",
            meeting_duration: ["12","14"],
            meeting_duration_marks: {
                0: "00:00",
                6: "06:00",
                12: "12:00",
                18: "18:00",
                24: "00:00"
            },
            modelsData: {
                meetingRooms: [],
                meetingStatuses: [],
                customers: []
            },
            showGFormField: false

        }
    },
    created() {
        this.loadModelData()
    },
    methods: {
        loadModelData() {
            for (let modelData of this.modelsToLoad) {
                const [model,field] = modelData
                this.$http.post("/admin/inputs/option_list",{ model: `App\\Http\\Models\\${model}` })
                    .then(r => this.modelsData[field] = r.data.data)
            }
        },
        durationFormatter(value) {
            let hours = Math.floor(value),
                minutes = String((value - hours) * 60).padStart(2,"0")
            return `${hours}:${minutes}`
        },
        send() {

        }
    }
}
</script>

<style lang="scss">
.el-range-separator {
    width: 10% !important;
}
.el-form {
    .el-form-item,
    .el-select {
        width: 100%;
    }
    .el-form-item__content {
        width: 100%;
    }
}
</style>