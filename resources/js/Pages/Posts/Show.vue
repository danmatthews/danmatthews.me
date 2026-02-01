<script setup>
import {computed} from "vue";
import {Head} from "@inertiajs/vue3";
import AppLayout from "../../Layouts/AppLayout.vue";

const props = defineProps({
    git_repo_url: {
        type: String
    },
    post: {
        type: Object,
        required: true,
    },
});

const structuredData = computed(() =>
    JSON.stringify(
        {
            "@context": "https://schema.org",
            "@type": "BlogPosting",
            headline: props.post.title,
            description: props.post.excerpt,
            image: props.post.og_image,
            datePublished: props.post.date.iso,
            dateModified: props.post.date.iso,
            author: {
                "@type": "Person",
                name: "Dan Matthews",
                url: `${window.location.origin}/about`,
            },
            publisher: {
                "@type": "Person",
                name: "Dan Matthews",
                url: window.location.origin,
                logo: {
                    "@type": "ImageObject",
                    url: `${window.location.origin}/icons/favicon.svg`,
                },
            },
            mainEntityOfPage: {
                "@type": "WebPage",
                "@id": props.post.url,
            },
        },
        null,
        2,
    ),
);
</script>

<template>
    <AppLayout>
        <Head>
            <title>{{ post.title }}</title>
        </Head>

        <article class="article-styling max-w-3xl">
            <header>

                <div
                    v-if="post.monthsAgo >= 6"
                    class="bg-gray-100 px-6 py-4 rounded mb-8"
                >
                    <h2 class="font-bold text-base">
                        This article is more than 6 months old.
                    </h2>
                    <p class="text-sm">
                        Please double check the validity of any information or
                        code you find in this article as it may be out of date.
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
                <div class=" flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                        <path
                            d="M13.488 2.513a1.75 1.75 0 0 0-2.475 0L6.75 6.774a2.75 2.75 0 0 0-.596.892l-.848 2.047a.75.75 0 0 0 .98.98l2.047-.848a2.75 2.75 0 0 0 .892-.596l4.261-4.262a1.75 1.75 0 0 0 0-2.474Z"/>
                        <path
                            d="M4.75 3.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h6.5c.69 0 1.25-.56 1.25-1.25V9A.75.75 0 0 1 14 9v2.25A2.75 2.75 0 0 1 11.25 14h-6.5A2.75 2.75 0 0 1 2 11.25v-6.5A2.75 2.75 0 0 1 4.75 2H7a.75.75 0 0 1 0 1.5H4.75Z"/>
                    </svg>

                    <p class="text-sm"><strong>Note: </strong>This post has been updated since it was first published,
                        luckily, you can find
                        the entire edit
                        history in the <a class="underline" v-bind:href="git_repo_url" target="_blank">git repo</a>.</p>
                </div>
            </header>

            <div
                class="mt-8 article-body"
                data-mdx-content="true"
                v-html="post.content"
            />
        </article>
    </AppLayout>
</template>
