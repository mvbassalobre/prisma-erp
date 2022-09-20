<template>
    <div class="my-1">
        <div v-if="loading" class="loading-section" :style="{height:(h ? h : 20)}"></div>
        <slot v-else></slot>
    </div>
</template>
<script>
export default {
    props: ["loading", "h"]
}
</script>
<style lang="scss">
.loading-section {
    background: linear-gradient(267deg, #e9ecef, #dcdcdc, #ffffff, #dcdcdc);
    background-size: 400% 400%;
    -webkit-animation: animationGradient 3s ease infinite;
    -moz-animation: animationGradient 3s ease infinite;
    animation: animationGradient 3s ease infinite;
    background-color: #e5e5e5;
    height: 100%;
    width: 100%;
}
</style>