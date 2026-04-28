<script setup>
import {Head, InfiniteScroll, usePage} from '@inertiajs/vue3';
import {computed} from 'vue';
import AppLayout from '../../Layouts/AppLayout.vue';
import PageTitle from "../../Components/PageTitle.vue";
import PostList from "../../Components/PostList.vue";
import SingleBlogPostListItem from "../../Components/SingleBlogPostListItem.vue";
import SingleLinkListItem from "../../Components/SingleLinkListItem.vue";

const props = defineProps({
    topic: {
        type: Object,
        required: true,
    },
    posts: {
        type: Object,
        required: true,
    },
    pageTitle: {
        type: String,
        required: true,
    },
});

const page = usePage();
const canonical = computed(() => page.props.canonical);
</script>

<template>
    <AppLayout>
        <Head>
            <title>{{ pageTitle }}</title>
            <meta name="description" :content="`Posts and links tagged with ${topic.name}`">
            <link rel="canonical" :href="canonical">
        </Head>

        <PageTitle class="mb-4" :title="`#${topic.name}`" />
        <p class="text-slate-500 mb-12">
            {{ topic.count }} {{ topic.count === 1 ? 'entry' : 'entries' }} under this topic.
        </p>

        <InfiniteScroll data="posts">
            <PostList v-if="posts.data?.length">
                <template v-for="post in posts.data" :key="post.id">
                    <SingleBlogPostListItem :post="post" v-if="post.type === 'post'" />
                    <SingleLinkListItem :post="post" v-if="post.type === 'link'" />
                </template>
            </PostList>
        </InfiniteScroll>
    </AppLayout>
</template>
