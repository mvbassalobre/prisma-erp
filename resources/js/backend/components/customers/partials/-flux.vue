<template>
    <div
        class="tab-pane fade"
        v-bind:class="{'show active' : active == 'flux'}"
        id="v-pills-flux"
        role="tabpanel"
        aria-labelledby="v-pills-flux-tab"
    >
        <div class="row">
            <div class="col-12">
                <el-tabs type="border-card">
                    <el-tab-pane label="Objetivos">
                        <comp-goals :customer="customer" />
                    </el-tab-pane>
                    <el-tab-pane label="Fluxo de Caixa">
                        <comp-entries v-model="entries" :months="months" />
                    </el-tab-pane>
                </el-tabs>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ["active", "customer"],
    data() {
        return {
            months: this.$getMoths(),
            entries: ['teste'],
            flux: []
        }
    },
    components: {
        'comp-goals': require("./-goals.vue").default,
        'comp-entries': require("./partials/-entries.vue").default,
    }
}
</script>
<style lang="scss">
.table {
    .bordered {
        border-right: 2px solid #23282d;
    }
}
</style>