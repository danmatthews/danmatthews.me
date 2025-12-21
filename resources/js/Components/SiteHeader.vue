<script setup>
import {Link, usePage} from '@inertiajs/vue3';
import NavItem from './NavItem.vue';
import {computed} from 'vue';

defineProps({
    mobileOpen: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['toggle-mobile']);
const page = usePage();
const navigation = computed(() => page.props.navigation ?? []);
</script>

<template>
    <header class="relative z-40 py-12 mb-8 flex justify-between items-center">
        <Link href="/" class="flex items-center gap-4">
            <h1 class="font-bold text-slate-700 text-3xl">Dan Matthews</h1>
        </Link>

        <nav class="pointer-events-auto relative gap-6 items-center hidden md:flex">
            <NavItem
                v-for="item in navigation"
                :key="item.url"
                :item="item"
                @navigate="() => null"
            />
        </nav>

        <button class="block md:hidden cursor-pointer" @click="emit('toggle-mobile')">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                <path
                    fill-rule="evenodd"
                    d="M2 4.75A.75.75 0 0 1 2.75 4h10.5a.75.75 0 0 1 0 1.5H2.75A.75.75 0 0 1 2 4.75Zm0 6.5a.75.75 0 0 1 .75-.75h10.5a.75.75 0 0 1 0 1.5H2.75a.75.75 0 0 1-.75-.75Z"
                    clip-rule="evenodd"
                />
            </svg>
        </button>
    </header>
</template>
