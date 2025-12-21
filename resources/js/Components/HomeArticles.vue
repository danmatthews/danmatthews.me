<script setup>
import {Link} from '@inertiajs/vue3';
import PageTitle from './PageTitle.vue';
import Pagination from './Pagination.vue';

const props = defineProps({
    title: {
        type: String,
        default: '',
    },
    posts: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <div>
        <PageTitle v-if="title" class="mb-12" :title="title"/>

        <div class="max-w-2xl space-y-8 grid">
            <template v-if="posts?.length">
                <article
                    v-for="post in posts"
                    :key="post.id"
                    class="first:pt-0 group relative flex flex-col items-start space-y-6"
                >
                    <div class="space-y-2">
                        <h2 class="text-xl">
                            <Link :href="post.url" class="">{{ post.title }}</Link>
                        </h2>
                        <time class="text-xl text-slate-500 leading-none block" :datetime="post.date.iso">
                            {{ post.date.formatted }}
                        </time>
                    </div>

                    <p class="text-xl hidden">
                        {{ post.excerpt }}
                    </p>
                </article>
            </template>
            <Link href="/posts" class="hover:underline text-xl underline decoration-gray-300 underline-offset-4">More
                articles
                &rarr;
            </Link>

        </div>


    </div>
</template>
