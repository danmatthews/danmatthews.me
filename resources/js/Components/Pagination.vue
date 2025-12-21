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
                class="px-3 py-1 rounded-md border border-gray-200 opacity-50 cursor-not-allowed"
                v-html="link.label"
            />
            <Link
                v-else
                preserve-scroll
                :href="link.url"
                :class="[
                    'px-3 py-1 rounded-md border border-gray-200',
                    link.active ? 'bg-black text-white border-black' : 'bg-white text-gray-700 hover:border-black',
                ]"
                v-html="link.label"
            />
        </template>
    </nav>
</template>
