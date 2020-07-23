<template>
    <section-settings :settings="settings" />
</template>
<script>
export default {
    props: {
        settings: {
            type: Array,
            default: () => ([])
        },
    },
    components: {
        "section-settings": require("./partials/-settings.vue").default,
    }
}
</script>