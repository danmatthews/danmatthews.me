<script setup>
import {Head, usePage} from '@inertiajs/vue3';
import {computed} from 'vue';
import AppLayout from '../../Layouts/AppLayout.vue';
import HomeArticles from '../../Components/HomeArticles.vue';

const props = defineProps({
    tag: {
        type: String,
        required: true,
    },
    posts: {
        type: Object,
        required: true,
    },
});

const page = usePage();
const canonical = computed(() => page.props.canonical);
</script>

<template>
    <AppLayout>
        <Head>
            <title>Posts tagged with '{{ tag }}'</title>
            <link rel="canonical" :href="canonical">
        </Head>

        <PageTitle v-if="title" class="mb-12">{{ title }}</PageTitle>

        <HomeArticles :posts="posts" :title="`Posts tagged with '${tag}'`"/>
    </AppLayout>
</template>
