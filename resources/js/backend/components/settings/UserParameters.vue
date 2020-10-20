<template>
    <section-settings :pagseguro_url_notification="pagseguro_url_notification" :settings="settings" />
</template>
<script>
export default {
    props: {
        settings: {
            type: Array,
            default: () => [],
        },
        pagseguro_url_notification: {
            type: String,
            default: '',
        },
    },
    components: {
        'section-settings': require('./partials/-settings.vue').default,
    },
}
</script>