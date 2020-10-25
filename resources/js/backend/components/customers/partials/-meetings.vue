<template>
    <div class="card">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-sm-12 d-flex justify-content-between">
                    <div>
                        <h4>Reuniões</h4>
                    </div>
                    <div v-if="!customer_area">
                        <el-button round type="primary" icon="el-icon-plus" @click="openForm">Nova Reunião</el-button>
                    </div>
                </div>
            </div>
            <meetings-calendar-section />
        </div>

        <el-dialog :visible.sync="formVisible" width="95%" :append-to-body="true" title="Nova Reunião" class="meeting-form-dialog">
            <meeting-form :is-modal="true" :customer_id="customer.id" />
        </el-dialog>
    </div>
</template>
<script>
export default {
    props: ['info', 'customer', 'customer_area'],
    data() {
        return {
            formVisible: false,
        }
    },
    methods: {
        openForm() {
            this.formVisible = true
        },
    },
}
</script>
<style lang="scss">
.meeting-form-dialog {
    > .el-dialog {
        margin-top: 5vh !important;
    }
}
</style>