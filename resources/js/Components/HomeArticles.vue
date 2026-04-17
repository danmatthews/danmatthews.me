<script setup>
import {Link} from "@inertiajs/vue3";
import PageTitle from "./PageTitle.vue";
import Pagination from "./Pagination.vue";
import {useWebHaptics} from "web-haptics/vue";
import PostList from "./PostList.vue";
import SingleBlogPostListItem from "./SingleBlogPostListItem.vue";
import SingleLinkListItem from "./SingleLinkListItem.vue";

const {trigger} = useWebHaptics();

const props = defineProps({
    title: {
        type: String,
        default: "",
    },
    posts: {
        type: Object,
        required: true,
    },
});

function buzz() {
    trigger();
}
</script>

<template>
    <div>
        <PostList v-if="posts?.length">
            <template v-for="post in posts" :key="post.id">
                <SingleBlogPostListItem :post="post" v-if="post.type ==='post'"/>
                <SingleLinkListItem :post="post" v-if="post.type === 'link'"/>
            </template>
        </PostList>

    </div>
</template>
