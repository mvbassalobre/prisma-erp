<template>
    <div class="row mb-3">
        <div class="col-12">
            <CalendarSchedule
                :listRoom="listRoom"
                :listEvent="listEvent"
                :heightTable="heightTable"
                :loadingTable="loadingTable"
                :widthSessionOfTheDay="widthSessionOfTheDay"
                @before-close="() => (showing_event = {})"
                @eventClick="eventClick"
                @loadData="loadData"
            />
            <el-dialog title="Reunião Agendada" :visible.sync="dialog" width="50%" center>
                <table class="table table-striped mb-0">
                    <tbody>
                        <tr>
                            <td class="w-25">Cliente da Reunião*</td>
                            <td v-html="showing_event.customer ? showing_event.customer.name : ''" />
                        </tr>
                        <tr>
                            <td class="w-25">Assunto da Reunião*</td>
                            <td v-html="showing_event ? showing_event.subject : ''" />
                        </tr>
                        <tr>
                            <td class="w-25">Tipo de Reunião*</td>
                            <td v-html="showing_event ? showing_event.type : ''" />
                        </tr>
                        <tr>
                            <td class="w-25">Status*</td>
                            <td v-html="showing_event.status ? showing_event.status.name : ''" />
                        </tr>
                        <tr>
                            <td class="w-25">Local da Reunião*</td>
                            <td v-html="showing_event.room ? showing_event.room.name : ''" />
                        </tr>
                        <tr>
                            <td class="w-25">Horário</td>
                            <td>{{ showing_event ? showing_event.f_starts_at : '' }} - {{ showing_event ? showing_event.f_ends_at : '' }}</td>
                        </tr>
                    </tbody>
                </table>
                <span slot="footer" class="dialog-footer">
                    <div class="d-flex flex-row justify-content-end">
                        <a :href="`/admin/meetings/${showing_event.code}/edit`">Alterar Reunião</a>
                    </div>
                </span>
            </el-dialog>
        </div>
    </div>
</template>

<script>
export default {
    components: {
        CalendarSchedule: require('./-calendar-schedule.vue').default,
    },
    data() {
        return {
            dialog: false,
            showing_event: {},
            attempts: {
                rooms: 0,
                meetings: 0,
            },
            listRoom: [],
            listEvent: [],
            heightTable: 350,
            widthSessionOfTheDay: 160,
            loadingTable: true,
        }
    },
    mounted() {
        this.getRooms()
    },
    methods: {
        getRooms() {
            this.attempts.rooms++
            this.$http
                .post('/admin/inputs/option_list', { model: `\\App\\Http\\Models\\MeetingRoom` })
                .then((resp) => {
                    this.listRoom = resp.data.data
                    this.attempts.rooms = 0
                })
                .catch((er) => {
                    if (this.attempts.rooms <= 3) return this.getRooms()
                })
        },
        getMeetings(dates) {
            this.attempts.meetings++
            this.$http
                .post('/admin/meetings/get-calendar', { dates })
                .then((resp) => {
                    this.listEvent = resp.data
                    this.attempts.meetings = 0
                    this.loadingTable = false
                })
                .catch((er) => {
                    if (this.attempts.meetings <= 3) return this.getMeetings()
                    this.loadingTable = false
                })
        },
        eventClick(event) {
            this.dialog = true
            this.showing_event = event
        },
        loadData(dates) {
            this.listEvent = []
            this.loadingTable = true
            this.getMeetings(dates)
        },
    },
}
</script>

<style lang="css">
.content {
    max-width: 100%;
    margin: 0 24px;
}
</style>