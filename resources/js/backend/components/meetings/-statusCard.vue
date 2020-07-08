<template>
    <div class="card status-card">
        <div class="card-body">
            <h5 class="d-flex align-items-center">
                {{cardTitle}}
                <el-form-item class="mb-0 ml-3" propname="Status" prop="form.status_id" required>
                    <el-select
                        v-model="form.status_id"
                        class="select-status w-auto"
                        placeholder="Selecione um status"
                    >
                        <el-option
                            v-for="status in modelsData.meetingStatuses"
                            :key="status.id"
                            :value="status.id"
                            :label="status.name"
                        >
                            <span
                                is-dot
                                class="badge badge-pill"
                                v-text="' '"
                                :style="{backgroundColor:status.color}"
                            />
                            {{status.name}}
                        </el-option>
                    </el-select>
                </el-form-item>
            </h5>
            <div class="row">
                <div class="col-sm-12" v-if="form.id">
                    <el-form-item
                        label="Descrição da Atualização"
                        required
                        prop="extra.updateMessage"
                    >
                        <el-input type="textarea" v-model="extra.updateMessage" />
                    </el-form-item>
                </div>
                <div class="col-sm-12 my-3">
                    <el-switch v-model="extra.sendUpdateEmail" :active-text="sendEmailSwitchText"></el-switch>
                </div>
                <div class="col-sm-12" v-if="form.id">
                    <el-button class="w-100 btn-primary" @click="$emit('send')">Salvar atualizações</el-button>
                </div>
            </div>
            <div v-if="!form.id" class="row mt-5">
                <div class="col-sm-12" v-show="false && !form.google_event_id">
                    <el-switch
                        v-model="extra.create_event"
                        active-text="Criar evento no Google Calendar"
                    ></el-switch>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ["form","modelsData","extra"],
    data() {
        return {
            meeting_duration_marks: {
                0: "00:00",
                6: "06:00",
                12: "12:00",
                18: "18:00",
                24: "00:00"
            },
        }
    },
    methods: {
        durationFormatter(value) {
            let hours = Math.floor(value),
                minutes = String((value - hours) * 60).padStart(2,"0")
            return `${hours}:${minutes}`
        },
    },
    computed: {
        cardTitle() {
            return this.form.id ? "Reunião está " : "Criar com status "
        },
        sendEmailSwitchText() {
            return this.form.id ? "Enviar email ao atualizar" : "Enviar email ao criar"
        }
    }
}
</script>

<style lang="scss">
.select-status {
    > .el-input {
        > .el-input__inner {
            font-size: 1.3em;
            border-left: none;
            border-right: none;
            border-top: none;
            text-align: right;
            min-width: 110px;
            &::placeholder {
                color: black;
            }
        }
    }
}
</style>