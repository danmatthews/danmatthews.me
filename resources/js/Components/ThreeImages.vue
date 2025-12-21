<script setup>
import {computed} from 'vue';

const sources = [
    '/images/home_images/1.jpg',
    '/images/home_images/2.jpg',
    '/images/home_images/3.jpg',
    '/images/home_images/4.jpg',
    '/images/home_images/5.jpg',
];

const shuffled = computed(() => {
    return [...sources]
        .sort(() => Math.random() - 0.5)
        .slice(0, 3);
});
</script>

<template>
    <div>
        <div class="inline-flex items-center group hover:gap-3 mb-8">
            <img
                v-for="image in shuffled"
                :key="image"
                class="aspect-square w-24 rounded-sm -ml-3 first:ml-0 group-hover:ml-3 -rotate-3 group-hover:rotate-0 transition-all"
                :src="image"
                alt=""
            />
        </div>
    </div>
</template>
