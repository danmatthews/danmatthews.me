<script setup>
defineProps({
    title: {type: String, required: true}
});

</script>
<template>
    <div class="mb-4">
        <h1 class="text-5xl  text-black text-zinc-800">
            {{ this.$props.title }}
        </h1>
    </div>
</template>
