<script setup>
import {Link} from "@inertiajs/vue3";
import TopicList from "./TopicList.vue";

const props = defineProps({
    post: {type: Object}
})
</script>

<template>
    <article
        class="py-8 group relative flex flex-col items-start space-y-6"
    >
        <div class="space-y-2">
            <p class="text-base text-slate-500 leading-none flex flex-wrap items-center gap-x-2 gap-y-1">
                <time :datetime="post.date.iso">
                    {{ post.date.formatted }}
                </time>
                <span aria-hidden="true">&middot;</span>
                <span>Blog Post</span>
                <template v-if="post.topics?.length">
                    <span aria-hidden="true">&middot;</span>
                    <TopicList :topics="post.topics" :limit="2" variant="summary" />
                </template>
            </p>
            <h2 class="text-xl font-semibold tracking-tight text-pretty">
                <Link @click="buzz" :href="post.url">{{ post.title }}</Link>
            </h2>

        </div>

        <p class="text-base text-subtle ">
            {{ post.excerpt }}
        </p>
    </article>
</template>

<style scoped>

</style>
