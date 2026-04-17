<script setup>
import {Head, usePage} from "@inertiajs/vue3";
import {computed} from "vue";
import AppLayout from "../../Layouts/AppLayout.vue";
import PageTitle from "../../Components/PageTitle.vue";

const props = defineProps({
    currentPosition: {
        type: Object,
        required: true,
    },
    openSource: {
        type: Object,
        required: true,
    },
    everythingElse: {
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
            <link rel="canonical" :href="canonical"/>
        </Head>

        <PageTitle class="mb-16" title="Work"/>

        <div class="max-w-2xl">
            <div class="">
                <h2
                    class="text-xs mb-6 font-sans uppercase text-gray-500 tracking-widest"
                >
                    Current full-time Position
                </h2>
                <div class="">
                    <p class="text-xl text-subtle">
                        <span class="text-black">{{ currentPosition.job_title }}</span> at <a
                        :href="currentPosition.company_url" class="text-black">{{ currentPosition.company }}</a>
                    </p>


                    <p
                        v-if="currentPosition.description"
                        class="mt-4 text-xl leading-6 text-slate-500"
                    >
                        {{ currentPosition.description }}
                    </p>
                </div>
            </div>

            <hr class="my-16 border-t border-slate-200 dark:border-slate-800"/>

            <h2 class="text-xs mb-6 font-sans uppercase tracking-widest">
                Open Source
            </h2>
            <div
                class="text-xl underline-links-subtle text-slate-500"
                v-html="openSource.description"
            />
            <ul role="list" class="mt-8 space-y-8">
                <li
                    v-for="pkg in openSource.packages"
                    :key="pkg.title"
                    class="group relative gap-8 items-center"
                >
                    <a class="block" :href="pkg.url">
                        <h3 class="text-xl flex gap-1 items-center">
                            <span>{{ pkg.title }}</span>
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 16 16"
                                fill="currentColor"
                                class="size-4 text-gray-400"
                            >
                                <path
                                    d="M6.22 8.72a.75.75 0 0 0 1.06 1.06l5.22-5.22v1.69a.75.75 0 0 0 1.5 0v-3.5a.75.75 0 0 0-.75-.75h-3.5a.75.75 0 0 0 0 1.5h1.69L6.22 8.72Z"
                                />
                                <path
                                    d="M3.5 6.75c0-.69.56-1.25 1.25-1.25H7A.75.75 0 0 0 7 4H4.75A2.75 2.75 0 0 0 2 6.75v4.5A2.75 2.75 0 0 0 4.75 14h4.5A2.75 2.75 0 0 0 12 11.25V9a.75.75 0 0 0-1.5 0v2.25c0 .69-.56 1.25-1.25 1.25h-4.5c-.69 0-1.25-.56-1.25-1.25v-4.5Z"
                                />
                            </svg>
                        </h3>
                        <p
                            class="relative z-10 mt-2 text-base leading-6 text-slate-500"
                        >
                            {{ pkg.description }}
                        </p>
                    </a>
                </li>
            </ul>

            <hr class="my-16 border-t border-slate-200 dark:border-slate-800"/>

            <h2 class="text-xs mb-6 font-sans uppercase tracking-widest">
                Everything Else
            </h2>
            <ul role="list" class="space-y-8">
                <li
                    v-for="project in everythingElse"
                    :key="project.title"
                    class="group relative gap-8 items-center"
                >
                    <a class="block" :href="project.url">
                        <h3 class="text-xl flex gap-1 items-center">
                            <span>{{ project.title }}</span>
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 16 16"
                                fill="currentColor"
                                class="size-4 text-gray-400"
                            >
                                <path
                                    d="M6.22 8.72a.75.75 0 0 0 1.06 1.06l5.22-5.22v1.69a.75.75 0 0 0 1.5 0v-3.5a.75.75 0 0 0-.75-.75h-3.5a.75.75 0 0 0 0 1.5h1.69L6.22 8.72Z"
                                />
                                <path
                                    d="M3.5 6.75c0-.69.56-1.25 1.25-1.25H7A.75.75 0 0 0 7 4H4.75A2.75 2.75 0 0 0 2 6.75v4.5A2.75 2.75 0 0 0 4.75 14h4.5A2.75 2.75 0 0 0 12 11.25V9a.75.75 0 0 0-1.5 0v2.25c0 .69-.56 1.25-1.25 1.25h-4.5c-.69 0-1.25-.56-1.25-1.25v-4.5Z"
                                />
                            </svg>
                        </h3>
                        <p
                            class="relative z-10 mt-2 text-base leading-6 text-slate-500"
                        >
                            {{ project.description }}
                        </p>
                    </a>
                </li>
            </ul>
        </div>
    </AppLayout>
</template>
