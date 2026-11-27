<script setup>
import {Link} from "@inertiajs/vue3";
import ManageLayout from "../../Layouts/ManageLayout.vue";

defineProps({
    // {title, description, summary, url} for each part of the site that can be managed.
    sections: {type: Array, required: true},
});
</script>

<template>
    <ManageLayout title="Manage">
        <ul class="space-y-3">
            <li v-for="section in sections" :key="section.url">
                <Link
                    :href="section.url"
                    class="flex items-center gap-4 rounded-2xl bg-white p-4 ring-1 ring-slate-200 hover:ring-slate-400 active:bg-slate-100"
                >
                    <div class="min-w-0 flex-1">
                        <h2 class="text-lg font-semibold">{{ section.title }}</h2>
                        <p class="text-sm text-subtle">{{ section.description }}</p>
                        <p class="mt-1 text-sm font-medium text-slate-700">{{ section.summary }}</p>
                    </div>
                    <svg viewBox="0 0 24 24" aria-hidden="true" class="size-5 shrink-0 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 18l6-6-6-6"/>
                    </svg>
                </Link>
            </li>
        </ul>

        <a href="/" class="mt-8 inline-block text-sm text-subtle hover:text-black">View the site</a>
    </ManageLayout>
</template>
