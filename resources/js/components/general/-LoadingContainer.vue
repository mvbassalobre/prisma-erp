<template>
    <div>
        <img
            src="/assets/images/loading.gif"
            :style="{
            height : `${height}px`
        }"
            v-if="!loaded"
        />
        <transition name="fade">
            <slot v-if="loaded"></slot>
        </transition>
    </div>
</template>
<script>
export default {
    props: {
        height: {
            type: Number,
            default: 50
        },
        loaded: {
            type: Boolean,
            default: false
        }
    }
}
</script>
<style lang="scss" scoped>
img {
    -webkit-filter: grayscale(100%);
    filter: grayscale(100%);
    filter: gray;
    opacity: 0.5;
}
</style>