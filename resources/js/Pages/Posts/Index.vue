<script setup>
import {Head, Link, usePage} from '@inertiajs/vue3';
import AppLayout from '../../Layouts/AppLayout.vue';
import HomeArticles from '../../Components/HomeArticles.vue';
import {computed} from 'vue';
import {Deferred} from '@inertiajs/vue3'
import PageTitle from "../../Components/PageTitle.vue";
import PostList from "../../Components/PostList.vue";
import SingleBlogPostListItem from "../../Components/SingleBlogPostListItem.vue";
import SingleLinkListItem from "../../Components/SingleLinkListItem.vue";

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

        <PageTitle class="mb-12" title="Posts & Links"/>

        <PostList v-if="posts.data?.length">
            <template v-for="post in posts.data" :key="post.id">
                <SingleBlogPostListItem :post="post" v-if="post.type ==='post'"/>
                <SingleLinkListItem :post="post" v-if="post.type === 'link'"/>
            </template>
        </PostList>

    </AppLayout>
</template>
