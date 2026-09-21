<script setup>
import {Head, InfiniteScroll, usePage} from '@inertiajs/vue3';
import AppLayout from '../Layouts/AppLayout.vue';
import {computed} from 'vue';
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
    bio: {
        type: String,
        default: "",
    },
});

const page = usePage();
const canonical = computed(() => page.props.canonical);
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
                <InfiniteScroll data="posts">
                    <PostList v-if="posts.data?.length">
                        <template v-for="post in posts.data" :key="post.id">
                            <SingleBlogPostListItem :post="post" v-if="post.type === 'post'"/>
                            <SingleLinkListItem :post="post" v-if="post.type === 'link'"/>
                        </template>
                    </PostList>
                </InfiniteScroll>
            </div>

            <AuthorBio :bio="bio" class="shrink-0 lg:w-72"/>
        </div>
    </AppLayout>
</template>
