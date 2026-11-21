<script setup>
import {Head, useForm} from "@inertiajs/vue3";
import AppLayout from "../../Layouts/AppLayout.vue";
import PageTitle from "../../Components/PageTitle.vue";

const form = useForm({password: ""});

function submit() {
    form.post("/minipainting/login", {onFinish: () => form.reset("password")});
}
</script>

<template>
    <AppLayout>
        <Head title="Manage miniatures">
            <meta name="robots" content="noindex"/>
        </Head>

        <PageTitle class="mb-10" title="Manage miniatures."/>

        <form class="flex max-w-sm flex-col gap-3" @submit.prevent="submit">
            <label for="password" class="font-medium">Password</label>
            <input
                id="password"
                v-model="form.password"
                type="password"
                autocomplete="current-password"
                autofocus
                required
                class="rounded-lg border border-slate-300 bg-white px-3 py-2 dark:border-zinc-700 dark:bg-zinc-900"
            />
            <p v-if="form.errors.password" class="text-sm text-accent-strong">{{ form.errors.password }}</p>
            <UButton type="submit" color="neutral" class="self-start" :loading="form.processing">Unlock</UButton>
        </form>
    </AppLayout>
</template>
