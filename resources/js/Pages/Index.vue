<script setup>
import {Head, InfiniteScroll, usePage} from '@inertiajs/vue3';
import AppLayout from '../Layouts/AppLayout.vue';
import {computed} from 'vue';
import PageTitle from "../Components/PageTitle.vue";
import PostList from "../Components/PostList.vue";
import SingleBlogPostListItem from "../Components/SingleBlogPostListItem.vue";
import SingleLinkListItem from "../Components/SingleLinkListItem.vue";
import AuthorBio from "../Components/AuthorBio.vue";

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

        <div class="flex flex-col-reverse gap-12 lg:flex-row lg:items-start lg:gap-24">
            <div class="min-w-0 flex-1">
                <PageTitle class="mb-12" title="Hello there..."/>

                <div v-if="showIntro"
                     class="font-display max-w-2xl text-3xl mb-12 underline-links-subtle  text-slate-700 dark:text-slate-300">
                    <p>
                        I'm <strong>Dan Matthews</strong>, a full stack web
                        developer living in
                        <a
                            href="https://www.google.com/maps/place/Carlisle/@54.9000249,-2.9780525,13z/data=!3m1!4b1!4m6!3m5!1s0x487ce1df3eee6b0f:0x5c0a43b6ba15682d!8m2!3d54.892473!4d-2.932931!16zL20vMGdqOTU?entry=ttu&g_ep=EgoyMDI1MDUwMy4wIKXMDSoASAFQAw%3D%3D"
                            target="_blank"
                        >
                            Carlisle, Cumbria.
                        </a>
                        I mostly blog about Laravel,
                        <a href="https://vuejs.org/" target="_blank">VueJS</a>,
                        and Svelte,
                        but you can also find some more personal topics close to my heart here like cooking and more.
                    </p>
                </div>

                <InfiniteScroll data="posts">
                    <PostList v-if="posts.data?.length">
                        <template v-for="post in posts.data" :key="post.id">
                            <SingleBlogPostListItem :post="post" v-if="post.type === 'post'"/>
                            <SingleLinkListItem :post="post" v-if="post.type === 'link'"/>
                        </template>
                    </PostList>
                </InfiniteScroll>
            </div>

            <AuthorBio class="hidden shrink-0 lg:block lg:w-72"/>
        </div>
    </AppLayout>
</template>
