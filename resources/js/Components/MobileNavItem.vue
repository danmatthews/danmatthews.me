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

const baseClasses = 'block transition-colors text-xl text-right py-2';
</script>

<template>
    <a
        v-if="item.external"
        target="_blank"
        :href="item.url"
        :class="[
            baseClasses,
            mobile ? 'block py-2' : 'antialiased',
            item.active ? 'text-black underline decoration-gray-300 underline-offset-4' : 'text-gray-600 hover:text-black',
        ]"
    >{{ item.title }}</a>
    <Link
        v-else
        :href="item.url"
        :class="[
            baseClasses,
            mobile ? 'block py-2' : 'antialiased',
            item.active ? 'text-black underline decoration-gray-300 underline-offset-4' : 'text-gray-600 hover:text-black',
        ]"
    >
        {{ item.title }}
    </Link>

</template>
