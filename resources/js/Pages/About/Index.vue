<script setup>
import {Head, usePage} from "@inertiajs/vue3";
import {computed} from "vue";
import AppLayout from "../../Layouts/AppLayout.vue";
import PageTitle from "../../Components/PageTitle.vue";

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
        <Head title="About">
            <link rel="canonical" :href="canonical"/>
        </Head>

        <PageTitle class="mb-16" title="About."/>

        <div class="flex flex-col gap-10 text-xl">
            <div class="max-w-2xl">
                <p>Hey there, I’m Dan.</p>
                <div
                    class="mt-6 space-y-7 text-xl underline-links-subtle"
                    v-html="about"
                />
            </div>

            <div class="grid min-h-0 grid-cols-4 gap-3 md:order-first md:aspect-[2/1] md:grid-rows-2">
                <img
                    :src="'/images/about-portrait.webp'"
                    alt="Dan Matthews holding his dog by the harbour"
                    width="768"
                    height="1024"
                    class="col-span-4 aspect-square h-full min-h-0 w-full min-w-0 rounded-2xl object-cover object-[45%_28%] md:col-span-2 md:row-span-2 md:aspect-auto"
                />
                <img
                    :src="'/images/about-work-dog.webp'"
                    alt="Dan Matthews working at a laptop with a dog on his lap"
                    width="1024"
                    height="768"
                    loading="lazy"
                    class="col-span-4 aspect-[2/1] h-full min-h-0 w-full min-w-0 rounded-2xl object-cover object-top md:col-span-2 md:aspect-auto"
                />
                <img
                    :src="'/images/about-work.webp'"
                    alt="Dan Matthews sitting at his desk in the office"
                    width="1088"
                    height="722"
                    loading="lazy"
                    class="col-span-2 aspect-square h-full min-h-0 w-full min-w-0 rounded-2xl object-cover md:col-span-1 md:aspect-auto"
                />
                <img
                    :src="'/images/about-conference.webp'"
                    alt="Dan Matthews at a conference wearing a lanyard and backpack"
                    width="724"
                    height="1086"
                    loading="lazy"
                    class="col-span-2 aspect-square h-full min-h-0 w-full min-w-0 rounded-2xl object-cover md:col-span-1 md:aspect-auto"
                />
            </div>
        </div>

        <div class="max-w-2xl text-xl">
            <h2 class="text-3xl mt-16 mb-6 font-semibold text-pretty">
                Work History
            </h2>

            <div class="space-y-6 text-lg">
                <div
                    v-for="entry in resume"
                    :key="entry.companyName"
                    class="flex items-center justify-between"
                >
                    <div class="w-full">
                        <p class="font-medium">
                            <a
                                v-if="entry.url"
                                :href="entry.url"
                                target="_blank"
                            >{{ entry.companyName }}</a
                            >
                            <span v-else>{{ entry.companyName }}</span>
                        </p>
                        <div
                            class="flex items-center justify-between text-subtle"
                        >
                            <p class="">{{ entry.jobTitle }}</p>
                            <p class=" ">{{ entry.start }} - {{ entry.end }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <h2 class="text-3xl mt-16 mb-6 font-semibold text-pretty">
                Socials
            </h2>
            <ul role="list" class="space-y-3 text-lg">
                <li v-if="socials.bluesky" class="flex">
                    <a
                        class="group flex items-center gap-2"
                        :href="socials.bluesky"
                    >
                        <span>Follow on Bluesky</span>
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
                    </a>
                </li>
                <li v-if="socials.instagram" class="flex">
                    <a
                        class="group flex items-center gap-2"
                        :href="socials.instagram"
                    >
                        <span>Follow me on Instagram</span>
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
                    </a>
                </li>
                <li v-if="socials.github" class="flex">
                    <a
                        class="group flex items-center gap-2"
                        :href="socials.github"
                    >
                        <span>Follow me on GitHub</span>
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
                    </a>
                </li>
                <li v-if="socials.linkedin" class="flex">
                    <a
                        class="group flex gap-2 items-center"
                        :href="socials.linkedin"
                    >
                        <span>Follow me on LinkedIn</span>
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
                    </a>
                </li>
                <li class="flex">
                    <a
                        class="flex gap-2 items-center group"
                        href="mailto:dan@danmatthews.me"
                    >
                        <span>Email me</span>
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
                    </a>
                </li>
            </ul>
        </div>
    </AppLayout>
</template>
