<script setup>
defineProps({
    bio: {
        type: String,
        default: "",
    },
});
</script>

<template>
    <aside class="lg:sticky lg:top-8">
        <img
            :src="'/images/about.jpeg'"
            alt="Dan Matthews"
            class="size-16 rounded-full object-cover"
            width="64"
            height="64"
        />
        <h2 class="mt-4 text-xl font-semibold">Hi, I'm Dan...</h2>
        <div
            class="mt-2 text-base leading-relaxed text-slate-600 underline-links-subtle dark:text-slate-400"
            v-html="bio"
        />
    </aside>
</template>
