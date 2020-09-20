<template>
    <div class="card mb-2">
        <div class="card-header cursor-pointer" @click="opened = !opened">
            <div class="w-100 d-flex flex-row justify-content-between align-items-center">
                <span class="el-icon-menu mr-2"></span>Detalhes da Listagem Clientes
                <span
                    :class="`el-icon-arrow-${!opened ? 'down' : 'up'} ml-auto`"
                />
            </div>
        </div>
        <div class="card-body" v-if="opened">
            <template v-if="initialized">
                <div class="row mb-4">
                    <div
                        class="col-md-3 col-sm-12"
                        element-loading-text="Carregando quantificadores ..."
                        v-loading="loading.total"
                    >
                        <h4 class="mt-4">
                            <b>Qtde :</b>
                            {{total}}
                            <template v-if="total>1">resultados</template>
                            <template v-else>resultado</template>
                        </h4>
                    </div>
                    <div
                        class="col-md-3 col-sm-12"
                        element-loading-text="Carregando Times ..."
                        v-loading="loading.teams"
                    >
                        <pie-chart
                            legend="top"
                            :discrete="true"
                            height="200px"
                            :data="teams"
                            suffix=" clientes(s)"
                        />
                    </div>
                    <div
                        class="col-md-5 col-sm-12"
                        element-loading-text="Carregando Usuários ..."
                        v-loading="loading.users"
                    >
                        <bar-chart
                            :donut="true"
                            :legend="false"
                            :discrete="true"
                            height="200px"
                            :data="users"
                            suffix=" clientes(s)"
                        />
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>
<script>
export default {
    props: ["qty_result", "get_params"],
    data() {
        return {
            opened: false,
            initialized: false,
            loading: {
                total: true,
                teams: true,
                users: true,
            },
            teams: {},
            users: {},
            total: 0,
            attempts: {
                total: 0,
                teams: 0,
                users: 0
            }
        }
    },
    watch: {
        opened(val) {
            if (val && !this.initialized) return this.initialize()
        }
    },
    methods: {
        initialize() {
            if (!this.initialized) {
                this.initialized = true
                this.getTotal()
                this.getTeams()
                this.getUsers()
            }
        },
        getTotal() {
            this.attempts.total++
            this.$http.post(`/admin/customers/metrics/total`, { ...this.get_params }).then(resp => {
                this.total = resp.data
                this.loading.total = false
            }).catch(er => {
                if (this.attempts.total <= 3) return this.getTotal()
                this.loading.total = false
                console.log(er)
            })
        },
        getTeams() {
            this.attempts.teams++
            this.$http.post(`/admin/customers/metrics/teams`, { ...this.get_params }).then(resp => {
                this.teams = resp.data
                this.loading.teams = false
            }).catch(er => {
                if (this.attempts.teams <= 3) return this.getTeams()
                this.loading.teams = false
                console.log(er)
            })
        },
        getUsers() {
            this.attempts.users++
            this.$http.post(`/admin/customers/metrics/users`, { ...this.get_params }).then(resp => {
                this.users = resp.data
                this.loading.users = false
            }).catch(er => {
                if (this.attempts.users <= 3) return this.getUsers()
                this.loading.users = false
                console.log(er)
            })
        },
    }
}
</script>