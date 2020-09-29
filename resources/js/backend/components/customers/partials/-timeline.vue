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
                        <div class="d-flex flex-column row justify-content-center align-items-center">
                            <div class="col-12 mb-3">
                                <el-timeline class="pl-0">
                                    <el-timeline-item>
                                        <div class="card">
                                            <div class="card-header">
                                                <b>
                                                    <span class="el-icon-s-operation mr-2" />Filtro
                                                </b>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-4 col-sm-12">
                                                        <label>Tipo de Registro</label>
                                                        <el-select
                                                            class="w-100"
                                                            v-model="filter.type"
                                                            filterable
                                                            multiple
                                                            placeholder="Selecione o Tipo de Registro"
                                                        >
                                                            <el-option
                                                                v-for="(item,i) in types"
                                                                :key="i"
                                                                :label="item"
                                                                :value="item"
                                                            >
                                                                <div v-html="item"></div>
                                                            </el-option>
                                                        </el-select>
                                                    </div>
                                                    <div class="col-md-4 col-sm-12">
                                                        <label>Descrição de Registro</label>
                                                        <el-input
                                                            class="w-100"
                                                            placeholder="Descrição do registro"
                                                            v-model="filter.description"
                                                            clearable
                                                        />
                                                    </div>
                                                    <div class="col-md-4 col-sm-12">
                                                        <label>Data do Registro</label>
                                                        <el-date-picker
                                                            class="w-100"
                                                            v-model="filter.range_data"
                                                            type="datetimerange"
                                                            range-separator="-"
                                                            start-placeholder="Início do Periodo"
                                                            end-placeholder="Fim do Periodo"
                                                            format="dd/MM/yyyy HH:mm:ss"
                                                            value-format="yyyy-MM-dd HH:mm:ss"
                                                            clearable
                                                        />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </el-timeline-item>
                                    <el-timeline-item
                                        :timestamp="t.datetime"
                                        placement="top"
                                        v-for="(t,i) in (_timeline ? _timeline : [])"
                                        :key="i"
                                    >
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <b>
                                                        <span class="el-icon-info mr-2" />
                                                        <span
                                                            v-html="t.title"
                                                            classs="capitalize"
                                                        />
                                                    </b>
                                                    <div>
                                                        <span class="el-icon-time mr-1" />
                                                        <span v-html="t.datetime" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                class="card-body capitalize"
                                                v-html="t.description"
                                            />
                                        </div>
                                    </el-timeline-item>
                                </el-timeline>
                            </div>
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
        customer: {
            type: Object,
            default: () => ({}),
        },
        activeOption: {
            type: Object,
            default: () => ({}),
        },
        active: {
            type: String,
            default: null,
        },
    },
    data() {
        return {
            loading: false,
            filter: {
                type: [],
                range_data: [],
                description: null,
            },
        }
    },
    computed: {
        types() {
            return Array.from(
                new Set(this.customer.timeline.map(({ title }) => title))
            )
        },
        _timeline() {
            let _timeline = this.customer.timeline
            if (this.filter.type.length > 0)
                _timeline = _timeline.filter((row) =>
                    this.filter.type.includes(row["title"])
                )
            if (this.filter.description != null)
                _timeline = _timeline.filter(
                    (row) =>
                        row.description
                            .toLowerCase()
                            .indexOf(this.filter.description.toLowerCase()) >= 0
                )
            if (this.filter.range_data) {
                if (this.filter.range_data.length >= 2)
                    _timeline = _timeline.filter((row) => {
                        let date_1 = new Date(this.filter.range_data[0])
                        let date_2 = new Date(this.filter.range_data[1])
                        let date = row.datetime.split(" - ")
                        let day = date[0].substring(0, 2)
                        let month = date[0].substring(3, 5)
                        let year = date[0].substring(6, 10)
                        date = new Date(`${year}-${month}-${day} ${date[1]}`)
                        if (date >= date_1 && date <= date_2) return true
                        return false
                    })
            }
            return _timeline
        },
    },
    created() {
        setInterval(() => {
            this.getTimeline()
        }, 7000)
    },
    methods: {
        getTimeline() {
            this.$http
                .post(`/admin/customers/${this.customer.code}/get-timeline`, {})
                .then((resp) => {
                    resp = resp.data
                    if (!resp.data.isEqual(this.customer.timeline)) {
                        this.loading = true
                        setTimeout(() => {
                            this.customer.timeline = resp.data
                            this.loading = false
                        }, 1000)
                    }
                })
        },
    },
}
</script>