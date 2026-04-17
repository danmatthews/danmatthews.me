<script setup>
import { Link } from "@inertiajs/vue3";
import PageTitle from "./PageTitle.vue";
import Pagination from "./Pagination.vue";
import { useWebHaptics } from "web-haptics/vue";
const { trigger } = useWebHaptics();

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
        <PageTitle v-if="title" class="mb-12" :title="title" />

        <div class="max-w-2xl space-y-8 grid">
            <template v-if="posts?.length">
                <template v-for="item in posts" :key="item.id">
                    <article
                        v-if="item.type === 'post'"
                        class="first:pt-0 group relative flex flex-col items-start space-y-6"
                    >
                        <div class="space-y-2">
                            <h2 class="text-xl">
                                <Link @click="buzz" :href="item.url">{{
                                    item.title
                                }}</Link>
                            </h2>
                            <time
                                class="text-xl text-slate-500 leading-none block"
                                :datetime="item.date.iso"
                            >
                                {{ item.date.formatted }}
                            </time>
                        </div>

                        <p class="text-xl hidden">
                            {{ item.excerpt }}
                        </p>
                    </article>

                    <article
                        v-else
                        class="first:pt-0 group relative flex flex-col items-start space-y-3"
                    >
                        <h2 class="text-xl">
                            <a
                                :href="item.url"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-baseline gap-1.5"
                            >
                                <span>{{ item.title }}</span>
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 16 16"
                                    fill="currentColor"
                                    class="size-4 self-center text-slate-400 shrink-0"
                                    aria-hidden="true"
                                >
                                    <path fill-rule="evenodd" d="M4.25 5.5a.75.75 0 0 0-.75.75v6.5c0 .414.336.75.75.75h6.5a.75.75 0 0 0 .75-.75v-2.5a.75.75 0 0 1 1.5 0v2.5A2.25 2.25 0 0 1 10.75 15h-6.5A2.25 2.25 0 0 1 2 12.75v-6.5A2.25 2.25 0 0 1 4.25 4h5a.75.75 0 0 1 0 1.5h-5Z" clip-rule="evenodd" />
                                    <path fill-rule="evenodd" d="M6.194 12.753a.75.75 0 0 0 1.06.053L13 7.44v2.81a.75.75 0 0 0 1.5 0v-4.5a.75.75 0 0 0-.75-.75h-4.5a.75.75 0 0 0 0 1.5h2.553l-5.056 4.654a.75.75 0 0 0-.053 1.06Z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </h2>
                        <p
                            v-if="item.excerpt"
                            class="text-xl text-slate-500"
                        >
                            {{ item.excerpt }}
                        </p>
                    </article>
                </template>
            </template>
            <Link
                href="/posts"
                class="hover:underline text-xl underline decoration-gray-300 underline-offset-4"
                >More articles &rarr;
            </Link>
        </div>
    </div>
</template>
