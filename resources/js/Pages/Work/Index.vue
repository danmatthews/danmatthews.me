<script setup>
import {Head, usePage} from '@inertiajs/vue3';
import {computed} from 'vue';
import AppLayout from '../../Layouts/AppLayout.vue';
import PageTitle from '../../Components/PageTitle.vue';

const props = defineProps({
    projects: {
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
            <title>Work</title>
            <link rel="canonical" :href="canonical">
        </Head>

        <PageTitle class="mb-16" title="Work"/>

        <ul role="list" class="space-y-16">
            <li
                v-for="project in projects"
                :key="project.title"
                class="group relative gap-8 items-center "
            >
                <a class="block" :href="project.url">
                    <h2 class="text-xl flex gap-1 items-center">
                        <a :href="project.url">{{ project.title }}</a>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                             class="size-4 text-gray-400">
                            <path
                                d="M6.22 8.72a.75.75 0 0 0 1.06 1.06l5.22-5.22v1.69a.75.75 0 0 0 1.5 0v-3.5a.75.75 0 0 0-.75-.75h-3.5a.75.75 0 0 0 0 1.5h1.69L6.22 8.72Z"/>
                            <path
                                d="M3.5 6.75c0-.69.56-1.25 1.25-1.25H7A.75.75 0 0 0 7 4H4.75A2.75 2.75 0 0 0 2 6.75v4.5A2.75 2.75 0 0 0 4.75 14h4.5A2.75 2.75 0 0 0 12 11.25V9a.75.75 0 0 0-1.5 0v2.25c0 .69-.56 1.25-1.25 1.25h-4.5c-.69 0-1.25-.56-1.25-1.25v-4.5Z"/>
                        </svg>
                    </h2>
                    <p class="relative z-10 mt-2 text-base leading-6 text-slate-500">
                        {{ project.description }}
                    </p>
                </a>
            </li>
        </ul>
    </AppLayout>
</template>
