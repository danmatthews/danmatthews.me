<script setup>
import {Link} from '@inertiajs/vue3';

defineProps({
    links: {
        type: Array,
        required: true,
    },
});
</script>

<template>
    <nav class="flex items-center gap-2 text-sm">
        <template v-for="(link, index) in links" :key="index">
            <span
                v-if="!link.url"
                class="px-3 py-1 rounded-md border border-gray-200 dark:border-zinc-700 opacity-50 cursor-not-allowed"
                v-html="link.label"
            />
            <Link
                v-else
                preserve-scroll
                :href="link.url"
                :class="[
                    'px-3 py-1 rounded-md border border-gray-200 dark:border-zinc-700',
                    link.active ? 'bg-black text-white border-black dark:bg-white dark:text-black dark:border-white' : 'bg-white text-gray-700 hover:border-black dark:bg-zinc-800 dark:text-gray-300 dark:hover:border-white',
                ]"
                v-html="link.label"
            />
        </template>
    </nav>
</template>
