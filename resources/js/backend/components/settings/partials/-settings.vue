<template>
    <div class="row d-flex flex-wrap flex-row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row d-flex justify-content-center mb-3">
                        <div class="col-md-10 col-sm-12">
                            <template v-for="(setting,s) in settings">
                                <v-input
                                    :key="s"
                                    v-if="['text','integer','float'].includes(setting.type)"
                                    class="mb-3"
                                    :label="setting.name"
                                    :type="(['integer','float'].includes(setting.type) ? 'number' : 'text')"
                                    :description="setting.description"
                                    v-model="form[setting.id]"
                                />
                                <div
                                    :key="s"
                                    class="form-group d-flex align-items-center row mb-3 justify-content-center"
                                    v-if="['boolean'].includes(setting.type)"
                                >
                                    <label class="col-sm-2 col-form-label" v-html="setting.name"></label>
                                    <div class="col-sm-10">
                                        <el-switch v-model="form[setting.id]" />
                                        <template v-if="setting.description">
                                            <br />
                                            <small
                                                style="color:gray;"
                                                class="mt-1"
                                                v-html="setting.description"
                                            ></small>
                                        </template>
                                    </div>
                                </div>
                            </template>
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
        settings: {
            type: Array,
            default: () => []
        }
    },
    data() {
        return {
            form: {},
            initialized: false,
            timeout: null
        }
    },
    async created() {
        this.initValues().then(x => this.initialized = true)
    },
    watch: {
        form: {
            handler(val) {
                if (!this.initialized) return
                clearTimeout(this.timeout)
                this.timeout = setTimeout(() => {
                    this.update()
                }, 1000)
            },
            deep: true
        }
    },
    methods: {
        async initValues() {
            for (let i in this.settings) {
                this.$set(this.form, this.settings[i].id, this.settings[i].value)
            }
        },
        update() {
            this.$http.put(`${laravel.general.root_url}/admin/parameters`, this.form).then(res => { }).catch(er => {
                console.log(er)
            })
        }
    }
}
</script>