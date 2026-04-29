<script setup>
import { Link } from "@inertiajs/vue3";
import { computed } from "vue";

const props = defineProps({
    topics: {
        type: Array,
        default: () => [],
    },
    limit: {
        type: Number,
        default: null,
    },
    variant: {
        type: String,
        default: "hashtag",
        validator: (value) => ["hashtag", "summary"].includes(value),
    },
});

const visible = computed(() =>
    props.limit ? props.topics.slice(0, props.limit) : props.topics,
);
</script>

<template>
    <span
        v-if="visible.length"
        class="inline-flex flex-wrap items-center gap-x-1"
    >
        <template v-if="variant === 'summary'">
            <template v-for="(topic, index) in visible" :key="topic.slug">
                <Link
                    :href="topic.url"
                    class="hover:text-slate-700 hover:underline"
                    >#{{ topic.name }}</Link
                ><span v-if="index < visible.length - 1" aria-hidden="true"
                    >,</span
                >
            </template>
        </template>
        <template v-else>
            <template v-for="(topic, index) in visible" :key="topic.slug">
                <Link
                    :href="topic.url"
                    class="hover:text-slate-700 hover:underline"
                    >#{{ topic.name }}</Link
                >
                <span v-if="index < visible.length - 1" aria-hidden="true"
                    >·</span
                >
            </template>
        </template>
    </span>
</template>
