<script setup>
import {Head, Link} from "@inertiajs/vue3";

// The /manage pages: an app-style bar instead of the site's header, so they
// work well opened from a phone's home screen.
defineProps({
    title: {type: String, required: true},
    // Where the back arrow goes; no arrow without it.
    back: {type: String, default: null},
    // Show the Lock button (hidden on the login page).
    lockable: {type: Boolean, default: true},
});
</script>

<template>
    <Head :title="title"/>

    <div class="min-h-dvh bg-slate-50 text-slate-900">
        <header class="sticky top-0 z-20 border-b border-slate-200 bg-white/90 pt-[env(safe-area-inset-top)] backdrop-blur">
            <div class="mx-auto flex h-14 max-w-2xl items-center gap-2 px-[max(1rem,env(safe-area-inset-left))]">
                <Link v-if="back" :href="back" aria-label="Back" class="-ml-2 flex size-10 items-center justify-center rounded-full text-slate-600 active:bg-slate-100">
                    <svg viewBox="0 0 24 24" aria-hidden="true" class="size-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 18l-6-6 6-6"/>
                    </svg>
                </Link>
                <h1 class="min-w-0 flex-1 truncate text-lg font-semibold">{{ title }}</h1>
                <Link
                    v-if="lockable"
                    href="/manage/logout"
                    method="post"
                    as="button"
                    class="-mr-2 cursor-pointer rounded-full px-3 py-2 text-sm text-slate-600 hover:text-black active:bg-slate-100"
                >
                    Lock
                </Link>
            </div>
        </header>

        <main class="mx-auto max-w-2xl px-[max(1rem,env(safe-area-inset-left))] pt-6 pb-[calc(env(safe-area-inset-bottom)+3rem)]">
            <slot/>
        </main>
    </div>
</template>
