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