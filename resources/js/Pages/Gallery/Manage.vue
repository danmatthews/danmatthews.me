<script setup>
import {Head, Link, router} from "@inertiajs/vue3";
import {ref, watch} from "vue";
import AppLayout from "../../Layouts/AppLayout.vue";
import PageTitle from "../../Components/PageTitle.vue";

const props = defineProps({
    photos: {
        type: Array,
        required: true,
    },
});

// Photos picked but not uploaded yet: {file, preview, caption, date, takenAt, fromPhoto, status}.
const queue = ref([]);
const uploading = ref(false);
// Unsaved caption and date edits, by photo id.
const edits = ref({});

watch(
    () => props.photos,
    (photos) => {
        edits.value = Object.fromEntries(photos.map((p) => [p.id, {caption: p.caption, date: p.date ?? ""}]));
    },
    {immediate: true},
);

// Y-m-d in local time, as <input type="date"> expects.
function ymd(time) {
    const d = new Date(time);
    return [d.getFullYear(), d.getMonth() + 1, d.getDate()].map((n) => String(n).padStart(2, "0")).join("-");
}

// When a JPEG was taken, from its EXIF DateTimeOriginal (or DateTime), as a
// local-time Date. Shrinking drops EXIF, so this reads the original file.
// Returns null when the file has none.
async function exifTakenAt(file) {
    try {
        const view = new DataView(await file.slice(0, 256 * 1024).arrayBuffer());
        if (view.getUint16(0) !== 0xffd8) return null;

        let offset = 2;
        while (offset + 4 < view.byteLength) {
            const marker = view.getUint16(offset);
            if (marker === 0xffda) return null; // Image data starts; no EXIF before it.
            if (marker === 0xffe1 && view.getUint32(offset + 4) === 0x45786966) {
                return readTiffDate(view, offset + 10);
            }
            offset += 2 + view.getUint16(offset + 2);
        }
    } catch {
        // Truncated or malformed metadata: fall back to the file's date.
    }
    return null;
}

function readTiffDate(view, tiff) {
    const little = view.getUint16(tiff) === 0x4949;
    const entries = (ifd) => {
        const found = {};
        const count = view.getUint16(ifd, little);
        for (let i = 0; i < count; i++) {
            const entry = ifd + 2 + i * 12;
            found[view.getUint16(entry, little)] = view.getUint32(entry + 8, little);
        }
        return found;
    };
    const ascii = (offset) => String.fromCharCode(...new Uint8Array(view.buffer, tiff + offset, 19));

    const ifd0 = entries(tiff + view.getUint32(tiff + 4, little));
    const exif = ifd0[0x8769] ? entries(tiff + ifd0[0x8769]) : {};
    const offset = exif[0x9003] ?? exif[0x9004] ?? ifd0[0x0132];
    const match = offset && ascii(offset).match(/^(\d{4}):(\d{2}):(\d{2}) (\d{2}):(\d{2}):(\d{2})$/);
    if (!match) return null;

    const [, y, mo, d, h, mi, sec] = match.map(Number);
    return new Date(y, mo - 1, d, h, mi, sec);
}

async function pick(event) {
    const files = [...event.target.files];
    event.target.value = "";
    for (const file of files) {
        const taken = await exifTakenAt(file);
        // Without EXIF, the file's own date is usually close to when it was taken.
        const takenAt = taken?.getTime() ?? file.lastModified;
        queue.value.push({
            file,
            preview: URL.createObjectURL(file),
            caption: "",
            date: ymd(takenAt),
            takenAt,
            fromPhoto: Boolean(taken),
            status: "",
        });
    }
}

function unqueue(index) {
    URL.revokeObjectURL(queue.value[index].preview);
    queue.value.splice(index, 1);
}

// Phone photos are often 5–12 MB; shrink to 2000px on the long edge before upload.
async function shrink(file, maxEdge = 2000) {
    const bitmap = await createImageBitmap(file, {imageOrientation: "from-image"});
    const scale = Math.min(1, maxEdge / Math.max(bitmap.width, bitmap.height));
    const canvas = document.createElement("canvas");
    canvas.width = Math.round(bitmap.width * scale);
    canvas.height = Math.round(bitmap.height * scale);
    canvas.getContext("2d").drawImage(bitmap, 0, 0, canvas.width, canvas.height);
    const blob = await new Promise((resolve) => canvas.toBlob(resolve, "image/jpeg", 0.85));
    return new File([blob], file.name.replace(/\.\w+$/, "") + ".jpg", {type: "image/jpeg"});
}

function post(url, data) {
    return new Promise((resolve, reject) =>
        router.post(url, data, {
            forceFormData: true,
            preserveScroll: true,
            preserveState: true,
            onSuccess: resolve,
            onError: (errors) => reject(Object.values(errors)[0]),
        }),
    );
}

// The server files each photo by date, newest first, putting a later upload
// above an earlier one from the same day. Uploading oldest first therefore
// keeps photos from one day in the order they were taken.
async function uploadAll() {
    uploading.value = true;
    const oldestFirst = [...queue.value].sort((a, b) => a.date.localeCompare(b.date) || a.takenAt - b.takenAt);
    for (const item of oldestFirst) {
        item.status = "Uploading…";
        try {
            await post("/minipainting/photos", {image: await shrink(item.file), caption: item.caption, date: item.date});
            unqueue(queue.value.indexOf(item));
        } catch (error) {
            item.status = error ?? "Upload failed";
        }
    }
    uploading.value = false;
}

const visit = {preserveScroll: true, preserveState: true};

function save(photo) {
    const edit = edits.value[photo.id];
    if (edit.caption !== photo.caption || edit.date !== (photo.date ?? "")) {
        router.patch(`/minipainting/photos/${photo.id}`, edit, visit);
    }
}

function move(index, by) {
    const ids = props.photos.map((p) => p.id);
    [ids[index], ids[index + by]] = [ids[index + by], ids[index]];
    router.put("/minipainting/photos/order", {ids}, visit);
}

function remove(photo) {
    if (confirm("Delete this photo? This can't be undone.")) {
        router.delete(`/minipainting/photos/${photo.id}`, visit);
    }
}

const input = "w-full rounded-lg border border-slate-300 bg-white px-3 py-2 dark:border-zinc-700 dark:bg-zinc-900";
const small = "cursor-pointer text-sm text-subtle hover:text-black disabled:cursor-default disabled:opacity-40 dark:hover:text-white";
</script>

<template>
    <AppLayout>
        <Head title="Manage miniatures">
            <meta name="robots" content="noindex"/>
        </Head>

        <div class="mb-10 flex flex-col gap-4 sm:flex-row sm:items-baseline sm:justify-between">
            <PageTitle class="mb-0" title="Manage miniatures."/>
            <div class="flex shrink-0 gap-6 text-sm">
                <Link href="/minipainting" class="text-subtle hover:text-black dark:hover:text-white">View gallery</Link>
                <Link href="/minipainting/logout" method="post" as="button" class="cursor-pointer text-subtle hover:text-black dark:hover:text-white">Lock</Link>
            </div>
        </div>

        <section class="space-y-4">
            <h2 class="text-2xl font-semibold">Add photos</h2>
            <label class="inline-flex cursor-pointer rounded-lg border border-dashed border-slate-400 px-4 py-3 hover:border-slate-900 dark:hover:border-white">
                Choose photos…
                <input type="file" accept="image/*" multiple class="sr-only" @change="pick"/>
            </label>

            <ul v-if="queue.length" class="space-y-3">
                <li v-for="(item, index) in queue" :key="item.preview" class="flex items-start gap-3 sm:gap-4">
                    <img :src="item.preview" alt="" class="size-16 shrink-0 rounded-2xl object-cover sm:size-24"/>
                    <div class="flex-1 space-y-1">
                        <textarea v-model="item.caption" rows="2" placeholder="Caption" :class="input"/>
                        <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                            <input v-model="item.date" type="date" aria-label="Date" :class="[input, 'sm:w-48']"/>
                            <span class="text-sm text-subtle">{{ item.fromPhoto ? "Taken date from the photo" : "No taken date in the photo, using the file's date" }}</span>
                        </div>
                        <p v-if="item.status" class="text-sm text-subtle">{{ item.status }}</p>
                    </div>
                    <button type="button" :class="small" :disabled="uploading" @click="unqueue(index)">Remove</button>
                </li>
            </ul>

            <UButton v-if="queue.length" color="neutral" :loading="uploading" @click="uploadAll">
                Upload {{ queue.length }} {{ queue.length === 1 ? "photo" : "photos" }}
            </UButton>
        </section>

        <section class="mt-16 space-y-4">
            <h2 class="text-2xl font-semibold">In the gallery ({{ photos.length }})</h2>
            <p v-if="!photos.length" class="text-subtle">No photos yet.</p>
            <ul class="space-y-3">
                <li v-for="(photo, index) in photos" :key="photo.id" class="flex items-start gap-3 sm:gap-4">
                    <img :src="photo.src" alt="" class="size-16 shrink-0 rounded-2xl object-cover sm:size-24" loading="lazy"/>
                    <div class="flex-1 space-y-1">
                        <textarea v-model="edits[photo.id].caption" rows="3" placeholder="Caption" :class="input" @blur="save(photo)"/>
                        <input v-model="edits[photo.id].date" type="date" aria-label="Date" :class="[input, 'sm:w-48']" @change="save(photo)"/>
                    </div>
                    <div class="flex shrink-0 flex-col items-start gap-1">
                        <button type="button" :class="small" :disabled="index === 0" @click="move(index, -1)">Move up</button>
                        <button type="button" :class="small" :disabled="index === photos.length - 1" @click="move(index, 1)">Move down</button>
                        <button type="button" :class="small" @click="remove(photo)">Delete</button>
                    </div>
                </li>
            </ul>
        </section>
    </AppLayout>
</template>
