<template>
    <div class="col-md-4 col-sm-12">
        <div class="card h-100">
            <div class="card-body d-flex flex-column">
                <span class="f-12 mb-3">
                    <b>TOP 5 TIMES</b> / Novos clientes no periodo do filtro
                </span>
                <loading-shimmer :loading="loading" :h="50">
                    <template v-if="!loading">
                        <table class="table table-striped table-sm f-12 my-0">
                            <tbody>
                                <template v-if="Object.keys(data).length>0">
                                    <template v-for="(p,i,y) in data">
                                        <tr :key="i">
                                            <td class="pt-3" width="1%">
                                                <template v-if="y<=2">
                                                    <span v-if="y==0">🥇</span>
                                                    <span v-if="y==1">🥈</span>
                                                    <span v-if="y==2">🥉</span>
                                                </template>
                                                <span v-else>{{y+1}}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <b>{{i}}</b>
                                                    <small
                                                        class="f-12"
                                                    >{{p}} {{(p>1) ? "Clientes Cadastrados" : "Cliente Cadastrado"}}</small>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                </template>
                                <div v-else class="text-center">
                                    <span class="f-12">Não há novos clientes ...</span>
                                </div>
                            </tbody>
                        </table>
                    </template>
                </loading-shimmer>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ["filter"],
    data() {
        return {
            loading: true,
            attempts: 0,
            data: {}
        }
    },
    watch: {
        filter: {
            handler(val) {
                this.attempts = 0
                this.loading = true
                this.init()
            },
            deep: true
        }
    },
    components: {
        "loading-shimmer": require("../../template/-loading-shimmer.vue").default
    },
    created() {
        this.init()
    },
    methods: {
        init() {
            this.attempts++
            this.$http.post(`${laravel.general.root_url}/admin/dashboard/get_info/topTeams`, { ...this.filter }).then(resp => {
                setTimeout(() => {
                    resp = resp.data
                    this.data = resp
                    this.loading = false
                }, 500)
            }).catch(er => {
                if (this.attempts <= 3) return this.init()
                console.log(er)
            })
        }
    }
}
</script>