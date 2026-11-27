<script setup>
import {useForm} from "@inertiajs/vue3";
import ManageLayout from "../../Layouts/ManageLayout.vue";

const form = useForm({password: ""});

function submit() {
    form.post("/manage/login", {onFinish: () => form.reset("password")});
}
</script>

<template>
    <ManageLayout title="Manage" :lockable="false">
        <form class="mx-auto flex max-w-sm flex-col gap-3 pt-8" @submit.prevent="submit">
            <label for="password" class="font-medium">Password</label>
            <input
                id="password"
                v-model="form.password"
                type="password"
                autocomplete="current-password"
                autofocus
                required
                class="rounded-xl border border-slate-300 bg-white px-4 py-3 text-base"
            />
            <p v-if="form.errors.password" class="text-sm text-accent-strong">{{ form.errors.password }}</p>
            <UButton type="submit" color="neutral" size="xl" block :loading="form.processing">Unlock</UButton>
            <p class="text-sm text-subtle">This device stays unlocked until you lock it.</p>
        </form>
    </ManageLayout>
</template>
