<template>
    <div
        class="tab-pane fade f-12"
        v-bind:class="{'show active' : active == 'timeline'}"
        id="v-pills-timeline"
        role="tabpanel"
        aria-labelledby="v-pills-timeline-tab"
    >
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>
                            <span class="el-icon-c-scale-to-original mr-2"></span>Timeline
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="block">
                            <el-timeline>
                                <el-timeline-item
                                    v-for="(t,i) in timeline"
                                    :timestamp="t.datetime"
                                    placement="top"
                                    :key="i"
                                >
                                    <el-card>
                                        <h5 v-html="t.title"></h5>
                                        <p v-html="t.description"></p>
                                    </el-card>
                                </el-timeline-item>
                            </el-timeline>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        timeline: {
            type: Array,
            default: () => ([])
        },
        activeOption: {
            type: Object,
            default: () => ({})
        },
        active: {
            type: String,
            default: null
        }
    }
}
</script>