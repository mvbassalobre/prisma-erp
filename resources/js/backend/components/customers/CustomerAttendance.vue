<template>
    <div class="row">
        <div class="col-md-2 col-sm-12 pr-0">
            <div
                class="nav flex-column nav-pills"
                id="v-pills-tab"
                role="tablist"
                aria-orientation="vertical"
            >
                <a
                    v-for="(option,i) in options"
                    :key="i"
                    class="nav-link mb-2 text-center btn-primary"
                    v-bind:class="{'active' : option.active}"
                    :id="`v-pills-${option.name}-tab`"
                    data-toggle="pill"
                    :href="`#v-pills-${option.name}`"
                    role="tab"
                    :aria-controls="`v-pills-${option.name}`"
                    @click.prevent="setActive(option)"
                >{{option.label}}</a>
            </div>
        </div>
        <div class="col-md-10 col-sm-12">
            <div class="tab-content" id="v-pills-tabContent">
                <comp-info :info="data" :active="active" :customer="customer" />
                <comp-timeline :timeline="customer.timeline" :active="active" />
                <comp-sales
                    :sales="customer.sales"
                    :customer="customer"
                    :active="active"
                    :canaddsale="canaddsale"
                />
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ["customer", "data", "canaddsale"],
    data() {
        return {
            active: "info",
            options: [
                { name: "info", label: "Cliente", active: true },
                { name: "timeline", label: "Timeline", active: false },
                { name: "sales", label: "Financeiro", active: false },
                // { name: "flux", label: "Fluxo de Caixa", active: false },
            ]
        }
    },
    components: {
        "comp-info": require("./partials/-info").default,
        "comp-timeline": require("./partials/-timeline").default,
        "comp-sales": require("./partials/-sales").default
    },
    created() {
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