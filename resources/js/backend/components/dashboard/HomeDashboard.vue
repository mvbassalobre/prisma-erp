<template>
    <div>
        <div class="d-flex flex-row align-items-end">
            <div>
                <h3>Dashboard</h3>
                <small>
                    Olá
                    <b>{{user.name}}</b>, aqui no dashboard você verá um informativo resumido a respeito de sua empresa
                </small>
            </div>
            <div class="ml-auto">
                <el-date-picker
                    class="w-100"
                    v-model="filter.daterange"
                    type="daterange"
                    range-separator="-"
                    start-placeholder="Início do Periodo"
                    end-placeholder="Fim do Periodo"
                    format="dd/MM/yyyy"
                />
            </div>
        </div>
        <div class="d-flex flex-column mt-3">
            <div class="row mt-3 d-flex flex-row flex-wrap cards">
                <products-card :user="user" :roles="roles" :filter="filter" />
                <customers-card :user="user" :roles="roles" :filter="filter" />
                <teams-card :user="user" :roles="roles" :filter="filter" />
                <users-card :user="user" :roles="roles" :filter="filter" />
            </div>
            <div class="row d-flex flex-row flex-wrap cards mt-3">
                <comp-sales :user="user" :roles="roles" :filter="filter" />
                <comp-sales :user="user" :roles="roles" :filter="filter" withPayment />
            </div>
            <div class="row mt-3 d-flex flex-row flex-wrap">
                <sales-payment :user="user" :roles="roles" :filter="filter" />
                <top-teams-new-customers :user="user" :roles="roles" :filter="filter" />
                <top-users-new-customers :user="user" :roles="roles" :filter="filter" />
            </div>
            <div class="row mt-3 d-flex flex-row flex-wrap">
                <meeting-statuses :user="user" :roles="roles" :filter="filter" />
                <top-teams-new-meeting :user="user" :roles="roles" :filter="filter" />
                <top-users-new-meeting :user="user" :roles="roles" :filter="filter" />
            </div>
            <div class="row mt-3 d-flex flex-row flex-wrap">
                <meeting-team :user="user" :roles="roles" :filter="filter" />
                <top-teams-with-payment :user="user" :roles="roles" :filter="filter" />
                <top-users-with-payment :user="user" :roles="roles" :filter="filter" />
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ['roles', 'user', 'params', 'default_range'],
    data() {
        return {
            filter: {
                daterange: this.default_range.split(",").map(date => new Date(date))
            }
        }
    },
    components: {
        "teams-card": require("./partials/-TeamsCard").default,
        "products-card": require("./partials/-ProductsCard").default,
        "customers-card": require("./partials/-CustomersCard").default,
        "users-card": require("./partials/-UsersCard").default,
        "top-users-new-customers": require("./partials/-TopUsersNewsCustomer.vue").default,
        "top-teams-new-customers": require("./partials/-TopTeamsNewsCustomer.vue").default,
        "top-teams-new-meeting": require("./partials/-TopTeamsNewMeeting.vue").default,
        "top-users-new-meeting": require("./partials/-TopUsersNewMeeting.vue").default,
        "meeting-statuses": require("./partials/-MeetingStatuses.vue").default,
        "meeting-team": require("./partials/-MeetingTeam.vue").default,
        "comp-sales": require("./partials/-Sales.vue").default,
        "sales-payment": require("./partials/-SalesPayment.vue").default,
    },
    created() {
        if (this.params.daterange) this.filter.daterange = this.params.daterange.split(",")
    },
    watch: {
        filter: {
            handler(val) {
                this.updateFilter()
            },
            deep: true
        },
    },
    methods: {
        updateFilter() {
            if (this.filter.daterange == null) {
                insertParam("daterange", [])
                return this.filter.daterange = []
            }
            let dates = this.filter.daterange.map(date => {
                if (!date) return null
                let d = new Date(date)
                const year = new Intl.DateTimeFormat('en', { year: 'numeric' }).format(d)
                const month = new Intl.DateTimeFormat('en', { month: '2-digit' }).format(d)
                const day = new Intl.DateTimeFormat('en', { day: '2-digit' }).format(d)
                return `${year}-${month}-${day}`
            })
            insertParam("daterange", dates.join(","))
        }
    }
}
</script>
<style scoped lang="scss">
.dashcard {
    height: 120px;
    max-height: 120px;
}
</style>