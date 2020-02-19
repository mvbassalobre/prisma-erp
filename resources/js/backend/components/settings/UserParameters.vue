<template>
    <div>
        <section-settings :settings="settings" />
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
    }
}
</script>