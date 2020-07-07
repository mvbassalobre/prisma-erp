<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <b class="f-12">
                        <span class="el-icon-s-data mr-2"></span>
                        Relatório de Reuniões por Time
                    </b>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <filter-section
                                ref="filter"
                                @filter="f => refreshResult(f)"
                                :rooms="rooms"
                                :teams="teams"
                                :status="status"
                            />
                            <result-section ref="result" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ["teams", "rooms", 'status'],
    components: {
        "filter-section": require("./partials/-filter-section.vue").default,
        "result-section": require("./partials/-result-section.vue").default
    },
    methods: {
        refreshResult(f) {
            this.$refs.result.refresh(f)
        }
    }
}
</script>