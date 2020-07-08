<template>
    <GSTC :config="config" @state="onState" />
</template>

<script>
import GSTC from "vue-gantt-schedule-timeline-calendar"
import CalendarScroll from "gantt-schedule-timeline-calendar/dist/CalendarScroll.plugin.js"
import clickActions from "./clickActions"
let subs = []
let start = new Date()
let end = new Date()
start.setDate(start.getDate() - 1)
end.setDate(start.getDate() + 200)
export default {
    props: ["items"],
    mixins: [clickActions],
    components: {
        GSTC
    },
    data() {
        return {
            config: {
                locale: require("./locale").default,
                height: 300,
                actions: {
                    'chart-timeline-items-row-item': [this.clickAction]
                },
                list: {
                    rows: {
                        "1": {
                            id: "1",
                            label: "Reuniões"
                        }
                    }
                },
                chart: {
                    time: {
                        from: start.getTime(),
                        to: end.getTime(),
                        period: "hour"
                    },
                    items: this.items
                },
                plugins: [
                    CalendarScroll({
                        speed: 0.02,
                        hideScroll: true
                    })
                ]
            }
        }
    },
    methods: {
        onState(state) {
            this.state = state
            subs.push(
                state.subscribe("config.chart.items.1", item => {
                    console.log("item 1 changed", item)
                })
            )
        }
    },
    mounted() {

    },
    beforeDestroy() {
        subs.forEach(unsub => unsub())
    }
};
</script>
<style lang="scss">
.gantt-schedule-timeline-calendar__list-toggle {
    display: none;
}
.gantt-schedule-timeline-calendar__chart-calendar-dates.gantt-schedule-timeline-calendar__chart-calendar-dates--level-0 {
    > div {
        padding-bottom: 10px;
    }
}
.gantt-schedule-timeline-calendar__chart-timeline-items-row-item {
    &,
    * {
        transition: 0.25s;
    }
    cursor: pointer;
    &:hover {
        width: initial !important;
    }
}
</style>