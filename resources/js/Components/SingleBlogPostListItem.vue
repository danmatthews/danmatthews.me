<script setup>
import {Link} from "@inertiajs/vue3";

const props = defineProps({
    post: {type: Object}
})
</script>

<template>
    <article
        class="py-8 group relative flex flex-col items-start space-y-6"
    >
        <div class="space-y-2">
            <p class="text-base text-slate-500 leading-none block">
                <time
                    :datetime="post.date.iso"
                >
                    {{ post.date.formatted }}
                </time> &middot; Blog Post
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
