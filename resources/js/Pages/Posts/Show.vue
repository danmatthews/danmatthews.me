<script setup>
import {computed} from 'vue';
import {Head} from '@inertiajs/vue3';
import AppLayout from '../../Layouts/AppLayout.vue';

const props = defineProps({
    post: {
        type: Object,
        required: true,
    },
});

const structuredData = computed(() => JSON.stringify({
    '@context': 'https://schema.org',
    '@type': 'BlogPosting',
    headline: props.post.title,
    description: props.post.excerpt,
    image: props.post.og_image,
    datePublished: props.post.date.iso,
    dateModified: props.post.date.iso,
    author: {
        '@type': 'Person',
        name: 'Dan Matthews',
        url: `${window.location.origin}/about`,
    },
    publisher: {
        '@type': 'Person',
        name: 'Dan Matthews',
        url: window.location.origin,
        logo: {
            '@type': 'ImageObject',
            url: `${window.location.origin}/icons/favicon.svg`,
        },
    },
    mainEntityOfPage: {
        '@type': 'WebPage',
        '@id': props.post.url,
    },
}, null, 2));
</script>

<template>
    <AppLayout>
        <Head>
            <title>{{ post.title }}</title>
            <meta name="description" :content="post.excerpt">
            <meta property="og:site_name" content="danmatthews.me">
            <meta property="og:type" content="article">
            <meta property="og:title" :content="post.title">
            <meta property="og:description" :content="post.excerpt">
            <meta property="og:image" :content="post.og_image">
            <meta name="twitter:title" :content="post.title">
            <meta name="twitter:description" :content="post.excerpt">
            <meta name="twitter:image" :content="post.og_image">
            <link rel="canonical" :href="post.url">
            <component is="script" type="application/ld+json">{{ structuredData }}</component>
        </Head>

        <article class="article-styling max-w-3xl">
            <header>
                <div v-if="post.monthsAgo >= 6" class="bg-gray-100 px-6 py-4 rounded mb-8">
                    <h2 class="font-bold text-base">This article is more than 6 months old.</h2>
                    <p class="text-sm">
                        Please double check the validity of any information or code you find in this article as it may
                        be out of date.
                    </p>
                </div>

                <h2 class="mb-3 text-5xl post-title">{{ post.title }}</h2>
                <div>
                    <time
                        :datetime="post.date.iso"
                        class="text-xl mb-16 text-base items-center text-slate-500 mb-4 w-full block"
                    >
                        {{ post.date.formatted }}
                    </time>
                </div>
            </header>

            <div class="mt-8 article-body" data-mdx-content="true" v-html="post.content"/>
        </article>
    </AppLayout>
</template>
