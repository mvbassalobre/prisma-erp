<template>
    <div>
        <section-settings :settings="settings">
            <section-whitelist :whitelist="whitelist" />
        </section-settings>
    </div>
</template>
<script>
export default {
    props: {
        settings: {
            type: Array,
            default: () => ([])
        },
        whitelist: {
            type: Array,
            default: () => ([])
        }
    },
    components: {
        "section-settings": require("./partials/-settings.vue").default,
        "section-whitelist": require("./partials/-whitelist.vue").default,
    }
}
</script>