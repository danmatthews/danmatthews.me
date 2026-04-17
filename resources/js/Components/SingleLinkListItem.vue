<script setup>
const props = defineProps({
    post: {type: Object}
})
</script>

<template>
    <article

        class="py-8 group relative flex flex-col items-start space-y-3"
    >
        <h2 class="text-xl font-semibold tracking-tight text-pretty">
            <a
                :href="post.url"
                target="_blank"
                rel="noopener noreferrer"
                class="inline-flex items-baseline gap-1.5"
            >
                <span>{{ post.title }}</span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                    <path
                        d="M6.22 8.72a.75.75 0 0 0 1.06 1.06l5.22-5.22v1.69a.75.75 0 0 0 1.5 0v-3.5a.75.75 0 0 0-.75-.75h-3.5a.75.75 0 0 0 0 1.5h1.69L6.22 8.72Z"/>
                    <path
                        d="M3.5 6.75c0-.69.56-1.25 1.25-1.25H7A.75.75 0 0 0 7 4H4.75A2.75 2.75 0 0 0 2 6.75v4.5A2.75 2.75 0 0 0 4.75 14h4.5A2.75 2.75 0 0 0 12 11.25V9a.75.75 0 0 0-1.5 0v2.25c0 .69-.56 1.25-1.25 1.25h-4.5c-.69 0-1.25-.56-1.25-1.25v-4.5Z"/>
                </svg>


            </a>
        </h2>
        <p
            v-if="post.excerpt"
            class="text-base text-slate-500"
        >
            {{ post.excerpt }}
        </p>
    </article>
</template>

<style scoped>

</style>
