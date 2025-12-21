<script setup>
import {Head, usePage} from '@inertiajs/vue3';
import {computed} from 'vue';
import AppLayout from '../../Layouts/AppLayout.vue';
import PageTitle from '../../Components/PageTitle.vue';

const props = defineProps({
    tags: {
        type: Array,
        required: true,
    },
});

const page = usePage();
const canonical = computed(() => page.props.canonical);
</script>

<template>
    <AppLayout>
        <Head>
            <title>Post Tags</title>
            <link rel="canonical" :href="canonical">
        </Head>
        <PageTitle class="mb-12">Post Tags</PageTitle>

        <div class="space-y-4">
            <a
                v-for="tag in tags"
                :key="tag.tag"
                class="block"
                :href="`/tags/${tag.tag}`"
            >
                {{ tag.tag }} <span class="text-gray-500">({{ tag.count }})</span>
            </a>
        </div>
    </AppLayout>
</template>
