<script setup>
import {computed, ref} from 'vue';
import {Head, usePage} from '@inertiajs/vue3';
import SiteHeader from '../Components/SiteHeader.vue';
import NavItem from '../Components/NavItem.vue';
import {DrawerContent, DrawerOverlay, DrawerPortal, DrawerRoot, DrawerTrigger} from 'vaul-vue'


const mobileMenuOpen = ref(false);
const page = usePage();


const canonical = computed(() => page.props.canonical);
const appName = computed(() => page.props.appName ?? 'Dan Matthews');
const navigation = computed(() => page.props.navigation ?? []);
</script>

<template>
    <div class="min-h-screen bg-slate-50 text-slate-900">
        <Head>
            <link rel="canonical" :href="canonical">
        </Head>

        <div
            v-if="mobileMenuOpen"
            class="fixed inset-0 z-40 bg-zinc-800/40 backdrop-blur-xs duration-150 data-closed:opacity-0 data-enter:ease-out data-leave:ease-in"
            aria-hidden="true"
        />

        <div
            v-if="mobileMenuOpen"
            class="fixed inset-x-4 top-8 z-50 origin-top rounded-3xl bg-white p-8 ring-1 ring-zinc-900/5 duration-150 data-closed:scale-95 data-closed:opacity-0 data-enter:ease-out data-leave:ease-in dark:ring-zinc-800"
        >
            <div class="flex flex-row-reverse items-center justify-between">
                <button @click.prevent="mobileMenuOpen = !mobileMenuOpen" aria-label="Close menu" class="-m-1 p-1">
                    <svg viewBox="0 0 24 24" aria-hidden="true" class="h-6 w-6 text-zinc-500 dark:text-zinc-400">
                        <path
                            d="m17.25 6.75-10.5 10.5M6.75 6.75l10.5 10.5"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                    </svg>
                </button>
                <h2 class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Navigation</h2>
            </div>
            <nav class="mt-6">
                <ul class="-my-2 divide-y divide-zinc-100 text-base text-zinc-800 dark:divide-zinc-100/5 dark:text-zinc-300">
                    <li v-for="item in navigation" :key="item.url">
                        <NavItem :item="item" mobile @navigate="mobileMenuOpen = false"/>
                    </li>
                </ul>
            </nav>
        </div>

        <div class="relative mx-auto max-w-4xl px-4 pb-32">
            <SiteHeader :mobile-open="mobileMenuOpen" @toggle-mobile="mobileMenuOpen = !mobileMenuOpen"/>
            <main class="w-full">
                <slot/>
            </main>
        </div>
    </div>
</template>
