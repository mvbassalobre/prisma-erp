<template>
    <div class="card">
        <div class="card-body">
            <h4 class="mb-3">Local e Horário</h4>
            <el-form-item  required
                    prop="form.meeting_room_id"
                    propname="Sala de reunião">
                <el-select
                    v-model="form.meeting_room_id"
                    placeholder="Selecione o local da Reunião"
                    
                >
                    <el-option
                        v-for="status in modelsData.meetingRooms"
                        :key="status.id"
                        :value="status.id"
                        :label="status.name"
                    />
                </el-select>
            </el-form-item>
            <div v-show="form.meeting_room_id">
                <div class="d-flex justify-content-center align-items-center flex-column">
                <el-form-item required prop="form.starts_at" propname="Data da reunião">
                    <el-date-picker
                        format="dd/MM/yyyy"
                        value-format="yyyy-MM-dd"
                        v-model="form.starts_at"
                        placeholder="Início"
                    ></el-date-picker>
                </el-form-item>
                <div>
                    <p class="text-center h3">
                        {{durationFormatter(extra.meeting_duration[0])}}
                        <small>até</small>
                        {{durationFormatter(extra.meeting_duration[1])}}
                    </p>
                </div>
            </div>
            <el-form-item>
                <div class="col-sm-12">
                    <el-slider
                        range
                        :marks="meeting_duration_marks"
                        v-model="extra.meeting_duration"
                        :step="0.5"
                        :max="24"
                        :format-tooltip="durationFormatter"
                    />
                </div>
            </el-form-item>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ["form","modelsData","extra","customer_id"],
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
        }
    }
}
</script>

<style>
</style>