<template>
    <div>
        <div class="row mb-2">
            <div class="col-12">
                <nav class="nav nav-pills flex-column flex-sm-row">
                    <a
                        class="flex-sm text-sm-center nav-link mr-1"
                        v-for="(option,i) in options"
                        :key="i"
                        v-bind:class="{'active' : option.active}"
                        :href="`#v-pills-${option.name}`"
                        @click.prevent="setActive(option)"
                    >{{option.label}}</a>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-12">
                        <div class="row f-12">
                            <div class="col-12">
                                <div class="card mb-3" :id="`${infoData.label}`">
                                    <div class="card-body p-0">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <table class="table table-striped mb-0">
                                                    <tbody>
                                                        <tr
                                                            v-for="(field, i) in infoData.inputs"
                                                            :key="i"
                                                        >
                                                            <td
                                                                style="width:25%;"
                                                                v-if="i.indexOf('IGNORE__')<0"
                                                            >
                                                                <span v-html="`<b>${i}</b>`"></span>
                                                            </td>
                                                            <td>
                                                                <v-runtime-template
                                                                    :key="i"
                                                                    :template="`<span>${field===null ? '' : field}</span>`"
                                                                />
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-content" id="v-pills-tabContent">
                    <comp-info :info="data" :active="active" :customer="customer" />
                    <comp-timeline :timeline="customer.timeline" :active="active" />
                    <comp-sales
                        :sales="customer.sales"
                        :customer="customer"
                        :active="active"
                        :canaddsale="canaddsale"
                    />
                    <comp-flux :customer="customer" :active="active" />
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import VRuntimeTemplate from "v-runtime-template"
export default {
    props: ["customer", "data", "canaddsale"],
    data() {
        return {
            backup_collapse: null,
            active: "info",
            options: [
                { name: "info", label: "Dados do Cliente", active: true },
                { name: "timeline", label: "Timeline", active: false },
                { name: "sales", label: "Financeiro", active: false },
                { name: "flux", label: "Planejamento", active: false },
            ]
        }
    },
    components: {
        "comp-info": require("./partials/-info").default,
        "comp-timeline": require("./partials/-timeline").default,
        "comp-sales": require("./partials/-sales").default,
        "comp-flux": require("./partials/-flux").default,
        "v-runtime-template": VRuntimeTemplate,
    },
    computed: {
        infoData() {
            let info = this.data.fields.filter(({ label }) => label == "Informações")[0]
            return info
        }
    },
    created() {
        this.$root.sidebarCollapse = true
        this.initHash()
    },
    methods: {
        initHash() {
            let hash = window.location.hash
            if (hash) {
                let option = this.options.find(x => x.name == hash.replace("#", ""))
                if (option) this.setActive(option)
            }
        },
        setActive(option) {
            for (let i in this.options) {
                if (option.name == this.options[i].name) {
                    this.active = option.name
                    this.options[i].active = true
                    window.location.hash = `#${this.active}`
                } else this.options[i].active = false
            }
        }
    }
}
</script>
<style lang="scss" scoped>
.nav {
    .nav-link {
        background-color: silver;
        color: white;
        &.active {
            background-color: #9e6de0;
            color: white;
        }
    }
}
</style>