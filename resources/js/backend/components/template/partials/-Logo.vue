<template>
    <div class="sidebar-logo-container">
        <transition name="fade">
            <a
                :href="logo.href"
                v-if="!$root.sidebarCollapse"
                class="sidebar-logo d-flex flex-row align-items-center justify-content-center"
            >
                <img v-if="logo" :src="logo.src" style="width: 40px;" />
                <div class="title">Prisma</div>
            </a>
            <a :href="smalllogo.href" v-else class="sidebar-smalllogo">
                <img v-if="logo" :src="smalllogo.src" style="width: 30px;" />
            </a>
        </transition>
    </div>
</template>

<script>
export default {
    props: ["collapse", "logo", "smalllogo"],
}
</script>

<style lang="scss" scoped>
@import "../../../../../sass/backend/_variables.scss";
.sidebar-logo-container {
    position: relative;
    width: 100%;
    padding: 10px;
    line-height: 50px;
    background: $secondary;
    text-align: center;
    overflow: hidden;
    a {
        &:hover {
            text-decoration: unset;
        }
    }
    .title {
        color: #e5e5e5;
        font-size: 25px;
        margin-left: 10px;
        font-weight: 400;
        padding-top: 5px;
    }
}
</style>