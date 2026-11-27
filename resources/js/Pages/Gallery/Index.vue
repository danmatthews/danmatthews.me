<script setup>
import {Head, router, usePage} from "@inertiajs/vue3";
import {computed, onUnmounted, ref} from "vue";
import AppLayout from "../../Layouts/AppLayout.vue";
import PageTitle from "../../Components/PageTitle.vue";

const props = defineProps({
    intro: {
        type: String,
        required: true,
    },
    photos: {
        type: Array,
        required: true,
    },
    total: {
        type: Number,
        required: true,
    },
    nextPage: {
        type: Number,
        default: null,
    },
});

const page = usePage();
const canonical = computed(() => page.props.canonical);

// "2026-11-21" → "21 November 2026", read as a calendar day, not a UTC instant.
function longDate(date) {
    const [y, m, d] = date.split("-").map(Number);
    return new Date(y, m - 1, d).toLocaleDateString("en-GB", {day: "numeric", month: "long", year: "numeric"});
}

// One column, then two from Tailwind's sm breakpoint and three from md.
const breakpoints = [window.matchMedia("(min-width: 40rem)"), window.matchMedia("(min-width: 48rem)")];
const columnCount = ref(0);
function countColumns() {
    columnCount.value = 1 + breakpoints.filter((query) => query.matches).length;
}
countColumns();
breakpoints.forEach((query) => query.addEventListener("change", countColumns));
onUnmounted(() => breakpoints.forEach((query) => query.removeEventListener("change", countColumns)));

// Each photo goes in the shortest column so far, so "Load more" only adds
// photos below the ones shown. CSS columns rebalanced every photo instead.
const columns = computed(() => {
    const columns = Array.from({length: columnCount.value}, () => ({height: 0, photos: []}));
    props.photos.forEach((photo, index) => {
        const shortest = columns.reduce((a, b) => (b.height < a.height ? b : a));
        shortest.photos.push({photo, index});
        shortest.height += photo.height / photo.width;
    });
    return columns.map((column) => column.photos);
});

// Index of the photo open in the lightbox, or null.
const open = ref(null);
const dialog = ref(null);
const current = computed(() => (open.value === null ? null : props.photos[open.value]));

function show(index) {
    open.value = index;
    dialog.value.showModal();
}

function step(by) {
    open.value = (open.value + by + props.photos.length) % props.photos.length;
}

function onKey(event) {
    if (event.key === "ArrowRight") step(1);
    if (event.key === "ArrowLeft") step(-1);
}

function loadMore() {
    router.reload({
        data: {page: props.nextPage},
        only: ["photos", "nextPage"],
        preserveUrl: true,
    });
}
</script>

<template>
    <AppLayout>
        <Head title="Gallery">
            <link rel="canonical" :href="canonical"/>
        </Head>

        <PageTitle class="mb-10" title="Miniature Painting."/>

        <div
            class="max-w-2xl space-y-7 text-xl underline-links-subtle"
            v-html="intro"
        />

        <section v-if="photos.length" class="mt-16 flex items-start gap-3">
            <div v-for="(column, c) in columns" :key="c" class="min-w-0 flex-1 space-y-3">
            <figure
                v-for="{photo, index} in column"
                :key="photo.src"
                class="group relative overflow-hidden rounded-2xl bg-zinc-900"
            >
                <button
                    type="button"
                    class="block w-full cursor-zoom-in"
                    :aria-label="`View ${photo.caption || 'photo'} full screen`"
                    @click="show(index)"
                >
                <img
                    :src="photo.src"
                    :alt="photo.alt ?? photo.caption"
                    :width="photo.width"
                    :height="photo.height"
                    loading="lazy"
                    class="block h-auto w-full"
                />
                </button>
                <figcaption
                    v-if="photo.caption || photo.date"
                    class="pointer-events-none absolute inset-x-0 bottom-0 bg-linear-to-b from-transparent to-zinc-950/80 px-4 pt-12 pb-3.5 text-sm text-white opacity-0 transition-opacity group-hover:opacity-100"
                    aria-hidden="true"
                >
                    {{ photo.caption }}
                    <time v-if="photo.date" :datetime="photo.date" class="mt-1 block text-xs text-white/60">{{ longDate(photo.date) }}</time>
                </figcaption>
            </figure>
            </div>
        </section>

        <dialog
            ref="dialog"
            class="m-0 h-dvh max-h-none w-screen max-w-none bg-zinc-950 p-0 text-white backdrop:bg-transparent"
            aria-label="Photo viewer"
            @close="open = null"
            @keydown="onKey"
            @click.self="dialog.close()"
        >
            <figure
                v-if="current"
                class="pointer-events-none flex h-full flex-col items-center justify-center gap-4 px-4 py-16 sm:px-20"
            >
                <img
                    :src="current.src"
                    :alt="current.alt ?? current.caption"
                    class="pointer-events-auto max-h-[calc(100dvh-10rem)] w-auto max-w-full rounded-2xl"
                />
                <figcaption v-if="current.caption || current.date" class="pointer-events-auto max-w-2xl text-center text-white/80">
                    {{ current.caption }}
                    <time v-if="current.date" :datetime="current.date" class="mt-1 block text-sm text-white/50">{{ longDate(current.date) }}</time>
                </figcaption>
            </figure>

            <button
                type="button"
                class="absolute top-4 right-4 cursor-pointer rounded-full p-2 text-white/70 hover:bg-white/10 hover:text-white"
                aria-label="Close"
                @click="dialog.close()"
            >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="size-6" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                </svg>
            </button>
            <template v-if="photos.length > 1">
                <button
                    type="button"
                    class="absolute top-1/2 left-2 -translate-y-1/2 cursor-pointer rounded-full p-2 text-white/70 hover:bg-white/10 hover:text-white sm:left-4"
                    aria-label="Previous photo"
                    @click="step(-1)"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="size-7" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
                    </svg>
                </button>
                <button
                    type="button"
                    class="absolute top-1/2 right-2 -translate-y-1/2 cursor-pointer rounded-full p-2 text-white/70 hover:bg-white/10 hover:text-white sm:right-4"
                    aria-label="Next photo"
                    @click="step(1)"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="size-7" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                    </svg>
                </button>
            </template>
        </dialog>

        <div v-if="photos.length" class="mt-10 flex items-center justify-between text-subtle">
            <span>Showing {{ photos.length }} of {{ total }} photos</span>
            <button
                v-if="nextPage"
                type="button"
                class="inline-flex cursor-pointer items-center gap-1.5 font-semibold text-slate-900 dark:text-slate-200"
                @click="loadMore"
            >
                Load more
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4" aria-hidden="true">
                    <path fill-rule="evenodd" d="M8 2a.75.75 0 0 1 .75.75v8.69l3.22-3.22a.75.75 0 1 1 1.06 1.06l-4.5 4.5a.75.75 0 0 1-1.06 0l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.22 3.22V2.75A.75.75 0 0 1 8 2Z" clip-rule="evenodd"/>
                </svg>
            </button>
        </div>
    </AppLayout>
</template>
