<script setup>
import {Link} from '@inertiajs/vue3';

const props = defineProps({
    item: {
        type: Object,
        required: true,
    },
    mobile: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['navigate']);

const baseClasses = 'inline-block transition-colors';
</script>

<template>
    <Link
        :href="item.url"
        :class="[
            baseClasses,
            mobile ? 'block py-2' : 'font-sans text-sm antialiased font-medium',
            item.active ? 'text-black underline decoration-gray-300 underline-offset-4' : 'text-gray-600 hover:text-black',
        ]"
        @click="emit('navigate')"
    >
        {{ item.title }}
    </Link>
</template>
