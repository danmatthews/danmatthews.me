<script setup>
defineProps({
    title: {type: String, required: true}
});

</script>
<template>
    <div class="mb-4">
        <h1 class="text-4xl font-semibold tracking-tight text-pretty text-gray-900 sm:text-5xl dark:text-white">
            {{ title }}</h1>
       
    </div>
</template>
