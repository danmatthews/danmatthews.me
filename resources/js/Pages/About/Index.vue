<script setup>
import {Head, usePage} from '@inertiajs/vue3';
import {computed} from 'vue';
import AppLayout from '../../Layouts/AppLayout.vue';
import PageTitle from '../../Components/PageTitle.vue';
import ThreeImages from '../../Components/ThreeImages.vue';

const props = defineProps({
    about: {
        type: String,
        required: true,
    },
    resume: {
        type: Array,
        required: true,
    },
});

const page = usePage();
const canonical = computed(() => page.props.canonical);
const socials = computed(() => page.props.socialLinks ?? {});
</script>

<template>
    <AppLayout>
        <Head>
            <title>About</title>
            <link rel="canonical" :href="canonical">
        </Head>

        <PageTitle class="mb-16" title="About."/>

        <!--        <ThreeImages/>-->

        <div class="max-w-2xl text-xl">
            <div class="">
                <p>Hey there, I’m Dan.</p>
                <div class="mt-6 space-y-7 text-xl underline-links-subtle" v-html="about"/>
            </div>

            <h2 class="text-xs mt-16 mb-6 font-sans uppercase tracking-widest">Work History</h2>
            <div class="space-y-6 text-xl">
                <div
                    v-for="entry in resume"
                    :key="entry.companyName"
                    class="flex items-center justify-between"
                >
                    <div class="w-full ">
                        <p class="font-medium">
                            <a v-if="entry.url" :href="entry.url" target="_blank">{{ entry.companyName }}</a>
                            <span v-else>{{ entry.companyName }}</span>
                        </p>
                        <div class="flex items-center justify-between text-subtle">
                            <p class="">{{ entry.jobTitle }}</p>
                            <p class=" ">{{ entry.start }} - {{ entry.end }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <h2 class="text-xs mt-16 mb-6 font-sans uppercase tracking-widest">Socials</h2>
            <ul role="list" class="space-y-3 text-xl">
                <li v-if="socials.bluesky" class="flex">
                    <a class="group flex items-center gap-2" :href="socials.bluesky">
                        <span>Follow on Bluesky</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                             class="size-4 text-gray-400">
                            <path
                                d="M6.22 8.72a.75.75 0 0 0 1.06 1.06l5.22-5.22v1.69a.75.75 0 0 0 1.5 0v-3.5a.75.75 0 0 0-.75-.75h-3.5a.75.75 0 0 0 0 1.5h1.69L6.22 8.72Z"/>
                            <path
                                d="M3.5 6.75c0-.69.56-1.25 1.25-1.25H7A.75.75 0 0 0 7 4H4.75A2.75 2.75 0 0 0 2 6.75v4.5A2.75 2.75 0 0 0 4.75 14h4.5A2.75 2.75 0 0 0 12 11.25V9a.75.75 0 0 0-1.5 0v2.25c0 .69-.56 1.25-1.25 1.25h-4.5c-.69 0-1.25-.56-1.25-1.25v-4.5Z"/>
                        </svg>
                    </a>
                </li>
                <li v-if="socials.instagram" class="flex">
                    <a class="group flex items-center gap-2" :href="socials.instagram">
                        <span>Follow me on Instagram</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                             class="size-4 text-gray-400">
                            <path
                                d="M6.22 8.72a.75.75 0 0 0 1.06 1.06l5.22-5.22v1.69a.75.75 0 0 0 1.5 0v-3.5a.75.75 0 0 0-.75-.75h-3.5a.75.75 0 0 0 0 1.5h1.69L6.22 8.72Z"/>
                            <path
                                d="M3.5 6.75c0-.69.56-1.25 1.25-1.25H7A.75.75 0 0 0 7 4H4.75A2.75 2.75 0 0 0 2 6.75v4.5A2.75 2.75 0 0 0 4.75 14h4.5A2.75 2.75 0 0 0 12 11.25V9a.75.75 0 0 0-1.5 0v2.25c0 .69-.56 1.25-1.25 1.25h-4.5c-.69 0-1.25-.56-1.25-1.25v-4.5Z"/>
                        </svg>
                    </a>
                </li>
                <li v-if="socials.github" class="flex">
                    <a class="group flex items-center gap-2" :href="socials.github">
                        <span>Follow me on GitHub</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                             class="size-4 text-gray-400">
                            <path
                                d="M6.22 8.72a.75.75 0 0 0 1.06 1.06l5.22-5.22v1.69a.75.75 0 0 0 1.5 0v-3.5a.75.75 0 0 0-.75-.75h-3.5a.75.75 0 0 0 0 1.5h1.69L6.22 8.72Z"/>
                            <path
                                d="M3.5 6.75c0-.69.56-1.25 1.25-1.25H7A.75.75 0 0 0 7 4H4.75A2.75 2.75 0 0 0 2 6.75v4.5A2.75 2.75 0 0 0 4.75 14h4.5A2.75 2.75 0 0 0 12 11.25V9a.75.75 0 0 0-1.5 0v2.25c0 .69-.56 1.25-1.25 1.25h-4.5c-.69 0-1.25-.56-1.25-1.25v-4.5Z"/>
                        </svg>
                    </a>
                </li>
                <li v-if="socials.linkedin" class="flex">
                    <a class="group flex gap-2 items-center" :href="socials.linkedin">
                        <span>Follow me on LinkedIn</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                             class="size-4 text-gray-400">
                            <path
                                d="M6.22 8.72a.75.75 0 0 0 1.06 1.06l5.22-5.22v1.69a.75.75 0 0 0 1.5 0v-3.5a.75.75 0 0 0-.75-.75h-3.5a.75.75 0 0 0 0 1.5h1.69L6.22 8.72Z"/>
                            <path
                                d="M3.5 6.75c0-.69.56-1.25 1.25-1.25H7A.75.75 0 0 0 7 4H4.75A2.75 2.75 0 0 0 2 6.75v4.5A2.75 2.75 0 0 0 4.75 14h4.5A2.75 2.75 0 0 0 12 11.25V9a.75.75 0 0 0-1.5 0v2.25c0 .69-.56 1.25-1.25 1.25h-4.5c-.69 0-1.25-.56-1.25-1.25v-4.5Z"/>
                        </svg>
                    </a>
                </li>
                <li class="flex">
                    <a class="flex gap-2 items-center group" href="mailto:dan@danmatthews.me">
                        <span>Email me</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                             class="size-4 text-gray-400">
                            <path
                                d="M6.22 8.72a.75.75 0 0 0 1.06 1.06l5.22-5.22v1.69a.75.75 0 0 0 1.5 0v-3.5a.75.75 0 0 0-.75-.75h-3.5a.75.75 0 0 0 0 1.5h1.69L6.22 8.72Z"/>
                            <path
                                d="M3.5 6.75c0-.69.56-1.25 1.25-1.25H7A.75.75 0 0 0 7 4H4.75A2.75 2.75 0 0 0 2 6.75v4.5A2.75 2.75 0 0 0 4.75 14h4.5A2.75 2.75 0 0 0 12 11.25V9a.75.75 0 0 0-1.5 0v2.25c0 .69-.56 1.25-1.25 1.25h-4.5c-.69 0-1.25-.56-1.25-1.25v-4.5Z"/>
                        </svg>
                    </a>
                </li>
            </ul>
        </div>
    </AppLayout>
</template>
