<template>
    <div class="row d-flex flex-wrap flex-row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="row d-flex justify-content-center">
                        <div class="col-12">
                            <slot></slot>
                            <div class="table-responsive">
                                <table class="table table-striped m-0">
                                    <tbody>
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
                                            <v-upload
                                                :key="s"
                                                v-if="['image'].includes(setting.type)"
                                                class="mb-3"
                                                :label="setting.name"
                                                :uploadroute="uploadRoute"
                                                v-model="form[setting.id]"
                                                :multiple="false"
                                                :preview="true"
                                                :limit="1"
                                                listtype="picture-card"
                                                accept="image/*"
                                            />
                                            <tr v-if="['boolean'].includes(setting.type)" :key="s">
                                                <td v-html="setting.name" />
                                                <td>
                                                    <el-switch v-model="form[setting.id]" />
                                                    <template v-if="setting.description">
                                                        <br />
                                                        <small
                                                            style="color:gray;"
                                                            class="mt-1"
                                                            v-html="setting.description"
                                                        ></small>
                                                    </template>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
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
    computed: {
        uploadRoute() {
            return laravel.vstack.default_upload_route
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
                let value = this.settings[i].value
                if (this.settings[i].type == "image") value = JSON.parse(value)
                this.$set(this.form, this.settings[i].id, value)
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