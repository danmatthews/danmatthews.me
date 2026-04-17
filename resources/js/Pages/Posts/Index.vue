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

        <div class="space-y-16 ">
            <template v-if="posts.data?.length">
                <template v-for="item in posts.data" :key="item.id">
                    <Link
                        v-if="item.type === 'post'"
                        :href="item.url"
                        class="block"
                    >
                        <article
                            class="group relative flex flex-col gap-3"
                        >
                            <div class="space-y-2">
                                <h2 class="text-xl">
                                    {{ item.title }}
                                </h2>
                                <time class="text-xl text-slate-500 leading-none block" :datetime="item.date.iso">
                                    {{ item.date.formatted }}
                                </time>
                            </div>

                            <p class="text-xl text-subtle hidden">
                                {{ item.excerpt }}
                            </p>
                        </article>
                    </Link>

                    <article
                        v-else
                        class="group relative flex flex-col gap-3"
                    >
                        <h2 class="text-xl">
                            <a
                                :href="item.url"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-baseline gap-1.5"
                            >
                                <span>{{ item.title }}</span>
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 16 16"
                                    fill="currentColor"
                                    class="size-4 self-center text-slate-400 shrink-0"
                                    aria-hidden="true"
                                >
                                    <path fill-rule="evenodd" d="M4.25 5.5a.75.75 0 0 0-.75.75v6.5c0 .414.336.75.75.75h6.5a.75.75 0 0 0 .75-.75v-2.5a.75.75 0 0 1 1.5 0v2.5A2.25 2.25 0 0 1 10.75 15h-6.5A2.25 2.25 0 0 1 2 12.75v-6.5A2.25 2.25 0 0 1 4.25 4h5a.75.75 0 0 1 0 1.5h-5Z" clip-rule="evenodd" />
                                    <path fill-rule="evenodd" d="M6.194 12.753a.75.75 0 0 0 1.06.053L13 7.44v2.81a.75.75 0 0 0 1.5 0v-4.5a.75.75 0 0 0-.75-.75h-4.5a.75.75 0 0 0 0 1.5h2.553l-5.056 4.654a.75.75 0 0 0-.053 1.06Z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </h2>
                        <p v-if="item.excerpt" class="text-xl text-slate-500">
                            {{ item.excerpt }}
                        </p>
                    </article>
                </template>
            </template>

        </div>

    </AppLayout>
</template>
