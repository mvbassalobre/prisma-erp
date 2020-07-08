<template>
    <div
        class="tab-pane fade"
        v-bind:class="{'show active' : active == 'meetings'}"
        id="v-pills-meetings"
        role="tabpanel"
        aria-labelledby="v-pills-meetings-tab"
    >
        <div class="card">
            <div class="card-body gantt-chart-wrapper">
                <div class="row mb-3">
                    <div class="col-sm-12 d-flex justify-content-between">
                        <div>
                            <h4>Reuniões</h4>
                        </div>
                        <div>
                            <el-button
                                round
                                type="primary"
                                icon="el-icon-plus"
                                @click="openForm"
                            >Nova Reunião</el-button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 position-absolute gantt-chart">
                        <gantt :items="items" />
                    </div>
                </div>
            </div>
        </div>
        <el-dialog
            :visible.sync="formVisible"
            width="95%"
            :append-to-body="true"
            title="Nova Reunião"
            class="meeting-form-dialog"
        >
            <meeting-form :is-modal="true" />
        </el-dialog>
    </div>
</template>
<script>
import Gantt from "../../meetings/viewer/-gantt"
export default {
    components: { Gantt },
    props: ["info","active","customer","meetings"],
    data() {
        return {
            formVisible: false,
            items: this.meetings
        }
    },
    methods: {
        openForm() {
            this.formVisible = true
        }
    }
}
</script>
<style lang="scss">
.gantt-chart-wrapper {
    //max-width: calc(100% - 80px)!important;
    height: 400px;
}
.meeting-form-dialog {
    > .el-dialog {
        margin-top: 5vh !important;
    }
}
</style>