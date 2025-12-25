<script setup>
import {Link, usePage} from '@inertiajs/vue3';
import NavItem from './NavItem.vue';
import {computed} from 'vue';
import MobileNavItem from "./MobileNavItem.vue";

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
    <header class="relative py-12 mb-8 flex justify-between items-center">
        <Link href="/" class="flex items-center gap-4">
            <h1 class="font-bold text-slate-700 text-3xl">Dan Matthews</h1>
        </Link>

        <nav class="pointer-events-auto relative gap-6 items-center hidden md:flex">
            <NavItem
                v-for="item in navigation"
                :key="item.url"
                :item="item"

            />
        </nav>

        <UDrawer
            direction="top"
        >
            <UButton icon="i-lucide-menu" variant="link" color="neutral" class="block md:hidden"/>

            <template #body>
                <nav class="p-4">
                    <MobileNavItem
                        v-for="item in navigation"
                        :key="item.url"
                        :item="item"
                    />
                </nav>
            </template>
        </UDrawer>


    </header>
</template>
