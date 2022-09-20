<template>
    <div class="schedule">
        <div class="row">
            <div class="col-12 flex-col flex__center mx-0">
                <div class="btn-group mb-1 btn-sm px-0" role="group">
                    <button type="button" class="btn btn-primary btn-sm" @click="prevWeek">
                        <i class="el-icon-arrow-left mr-2" />
                        Semana Anterior
                    </button>
                    <button type="button" class="btn btn-primary btn-sm" @click="nextWeek">
                        Próxima Semana
                        <i class="el-icon-arrow-right ml-2" />
                    </button>
                </div>
                <div class="card" v-loading="loadingTable">
                    <div class="card-body p-0">
                        <table class="table table-sm table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th rowspan="2" class="table-col text-center f-12" />
                                    <template v-for="day in dayOfWeek">
                                        <th v-html="day" :key="day" class="text-center f-12 table-col" />
                                    </template>
                                </tr>
                                <tr>
                                    <template v-for="day in dayOfWeek">
                                        <th :key="day" v-html="getWeekDay(day)" class="text-center f-12 table-col" />
                                    </template>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="(room, r) in listRoom">
                                    <tr v-if="roomHasMeet(room)" :key="r">
                                        <td class="text-center table-col">
                                            <b class="f-12">{{ room.name }}</b>
                                        </td>
                                        <template v-for="day in dayOfWeek">
                                            <td :key="day" class="table-col">
                                                <div v-for="(event, index) in eventsListSection(room, day)" :key="index" class="listEvent mb-1">
                                                    <div class="event" @click="getEvent(event)">
                                                        <el-alert effect="dark" class="meetbadge f-12" :type="getEventColor(event)" :closable="false">
                                                            <template slot="title">
                                                                <b class="f-12">{{ event.customer.name }}</b>
                                                            </template>
                                                            <p class="mb-0">{{ event.f_starts_at }}</p>
                                                            <p class="mb-0">{{ event.f_ends_at }}</p>
                                                        </el-alert>
                                                    </div>
                                                </div>
                                            </td>
                                        </template>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import moment, { months } from 'moment'
export default {
    props: {
        listRoom: {
            default: () => [],
            type: Array,
        },
        listEvent: {
            default: () => [],
            type: Array,
        },
        heightTable: {
            default: 450,
            type: Number,
        },
        widthSessionOfTheDay: {
            default: 250,
            type: Number,
        },
        loadingTable: {
            default: true,
            type: Boolean,
        },
    },
    computed: {
        dateCurrentFormatUTC: {
            get() {
                return this.dateCurrent
            },
        },
        dayOfWeek() {
            return this.listDateofWeek
        },
    },
    watch: {
        date(newValue) {
            return newValue && (this.dateCurrentFormatUTC = newValue)
        },
        listDateofWeek(dates) {
            this.$emit('loadData', dates)
        },
    },
    methods: {
        roomHasMeet(room) {
            return this.listEvent.filter((e) => e.meeting_room_id == room.id).length
        },
        eventsListSection(room, day) {
            return this.listEvent.filter((e) => this.showItem(e, room, day))
        },
        getWeekDay(day) {
            day = day.split('/')
            let days = ['Segunda-Feira', 'Terça-Feira', 'Quarta-Feira', 'Quinta-Feira', 'Sexta-Feira', 'Sábado', 'Domingo']
            return days[moment(`${day[2]}-${day[1]}-${day[0]}`).isoWeekday() - 1]
        },
        getEventColor(event) {
            let today = moment(moment().format('YYYY-MM-DD HH:mm:ss'))
            let date = moment(event.date + ' ' + event.starts_at)
            if (date.isSame(today, 'day')) {
                if (date.isAfter(today)) return 'warning'
                return 'error'
            }
            if (date.isAfter(today)) return 'success'
            return 'error'
        },
        showItem(event, room, date) {
            return event?.meeting_room_id === room?.id && this.getDateFormatFromDate(event.starts_at) == date
        },
        pad(num) {
            return ('0' + num).slice(-2)
        },
        getHoursFromDate(strtime) {
            let date = new Date(moment().format('YYYY-MM-DD') + ' ' + strtime)
            let hours = date.getHours()
            return this.pad(hours)
        },
        getMinutesFromDate(timestamp) {
            let date = new Date(timestamp * 1000)
            let minutes = date.getMinutes()
            return this.pad(minutes)
        },
        getDateFormatFromDate(strdate) {
            return moment(strdate).format('DD/MM/YYYY')
        },
        getEvent(event) {
            this.$emit('eventClick', event)
        },
        prevWeek() {
            let pastDate = this.dateWeeken.getDate() - 7
            this.dateWeeken.setDate(pastDate)
            this.getDayofWeek(this.dateWeeken)
        },
        nextWeek() {
            let pastDate = this.dateWeeken.getDate() + 7
            this.dateWeeken.setDate(pastDate)
            this.getDayofWeek(this.dateWeeken)
        },
        getDayofWeek(date) {
            let weekDates = []
            for (let i = 1; i <= 7; i++) weekDates.push(moment(date).day(i).format('DD/MM/YYYY'))
            this.listDateofWeek = weekDates
        },
    },
    data() {
        return {
            dateCurrent: moment().utc()._d,
            dateWeeken: moment()._d,
            listDateofWeek: [],
        }
    },
    created() {
        this.getDayofWeek(this.dateWeeken)
    },
}
</script>

<style scoped>
.meetbadge {
    cursor: pointer;
    max-width: 200px;
    width: 200px;
}
.table-col {
    width: 12.5% !important;
}
</style>