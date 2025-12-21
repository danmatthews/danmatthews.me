<script setup>
import {Head, Link, usePage} from '@inertiajs/vue3';
import AppLayout from '../../Layouts/AppLayout.vue';
import HomeArticles from '../../Components/HomeArticles.vue';
import {computed} from 'vue';
import {Deferred} from '@inertiajs/vue3'
import PageTitle from "../../Components/PageTitle.vue";

const props = defineProps({
    posts: {
        type: Object,
        required: true,
    },
    pageTitle: {
        type: String,
        required: true,
    },
    intro: {
        type: Object,
        default: () => ({}),
    },
});

const page = usePage();
const canonical = computed(() => page.props.canonical);
const showIntro = true;
</script>

<template>
    <AppLayout>
        <Head>
            <title>{{ pageTitle }}</title>
            <meta name="description" :content="intro?.subheading || 'Posts by Dan Matthews'">
            <link rel="canonical" :href="canonical">
        </Head>

        <PageTitle class="mb-12" title="Posts"/>

        <div class="space-y-16">
            <template v-if="posts.data?.length">

                <Link
                    :href="post.url"
                    class=""
                    v-for="post in posts.data"
                    :key="post.id"
                >
                    <article

                        class="group relative flex flex-col gap-3"
                    >
                        <div class="space-y-2">
                            <h2 class="text-xl">
                                {{ post.title }}
                            </h2>
                            <time class="text-xl text-slate-500 leading-none block" :datetime="post.date.iso">
                                {{ post.date.formatted }}
                            </time>
                        </div>

                        <p class="text-xl text-subtle">
                            {{ post.excerpt }}
                        </p>
                    </article>

                </Link>
            </template>

        </div>

    </AppLayout>
</template>
