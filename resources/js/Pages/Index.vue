<script setup>
import {Head, usePage} from '@inertiajs/vue3';
import AppLayout from '../Layouts/AppLayout.vue';
import HomeArticles from '../Components/HomeArticles.vue';
import {computed} from 'vue';
import PageTitle from "../Components/PageTitle.vue";

const props = defineProps({
    posts: {
        type: Object,
        required: true,
    },
    pageTitle: {
        type: String,
        required: true,
    },
    intro: {
        type: Object,
        default: () => ({}),
    },
});

const page = usePage();
const canonical = computed(() => page.props.canonical);
const showIntro = true;
</script>

<template>
    <AppLayout>
        <Head>
            <title>{{ pageTitle }}</title>
            <meta name="description" :content="intro?.subheading || 'Posts by Dan Matthews'">
            <link rel="canonical" :href="canonical">
        </Head>

        <PageTitle class="mb-12" title="Welcome"/>

        <div v-if="showIntro"
             class="max-w-2xl text-2xl mb-12 underline-links-subtle  text-slate-700">
            <p>
                I'm <strong>Dan Matthews</strong>, a full stack web
                developer living in
                <a
                    href="https://www.google.com/maps/place/Carlisle/@54.9000249,-2.9780525,13z/data=!3m1!4b1!4m6!3m5!1s0x487ce1df3eee6b0f:0x5c0a43b6ba15682d!8m2!3d54.892473!4d-2.932931!16zL20vMGdqOTU?entry=ttu&g_ep=EgoyMDI1MDUwMy4wIKXMDSoASAFQAw%3D%3D"
                    target="_blank"
                >
                    Carlisle, Cumbria.
                </a>
                I mostly blog about
                <a href="/tags/laravel">Laravel</a>,
                <a href="https://vuejs.org/" target="_blank">VueJS</a>,
                and
                <a href="/tags/svelte">Svelte</a>,
                but you can also find some more personal topics close to my heart here like cooking and more.
            </p>
        </div>
        <h2 class="text-xs mt-16 mb-6 font-sans uppercase tracking-widest">Recent Posts</h2>
        <HomeArticles :posts="posts"/>
        <template #fallback>
            Loading Posts...
        </template>
    </AppLayout>
</template>
