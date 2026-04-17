<script setup>
const props = defineProps({
    post: {type: Object}
})
</script>

<template>
    <div class="max-w-2xl border-t-8 divide-y-8">
        <slot/>
    </div>
</template>

<style scoped>

</style>
