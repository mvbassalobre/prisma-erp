<template>
    <div class="d-flex flex-column mt-3">
        <div class="d-flex flex-row flex-wrap cards">
            <customers-card :user="user" :roles="roles" />
        </div>
    </div>
</template>
<script>
export default {
    props: ['roles', 'user'],
    components: {
        "customers-card": require("./partials/-CustomersCard").default
    }
}
</script>
<style scoped lang="scss">
.dashcard {
    height: 120px;
    max-height: 120px;
}
</style>