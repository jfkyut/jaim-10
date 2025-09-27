<script setup>

import Modal from '@/Components/Modal.vue';
import Container from '@/Components/containers/Container.vue';
import { inject, ref, toRefs } from 'vue';
import NavButton from '@/Components/NavButton.vue';
import { storeToRefs } from 'pinia';
import { useAuthLayoutStore } from '@/Stores/authlayout';
import { Button, InputText, Textarea } from 'primevue';
import { useForm } from '@inertiajs/vue3';
import { useToast } from 'vue-toastification';

const props = defineProps({
    album: Object
})

const { album } = toRefs(props);

const { sidebarOpen } = storeToRefs(useAuthLayoutStore());

const toast = useToast()

const submitButtonRef = ref(null);

const isShowModal = ref(false);

const form = ref(useForm({
    music: null,
    title: '',
    generated_by: '',
    generated_at: '',
    description: '',
    lyrics: '',
    album_id: album.value ? album.value.id : null
}));

const onChangeFile = (event) => {
    form.value.music = event.target.files[0];
};

const submit = () => {
    form.value.post(route('music.store'), {
        onSuccess: () => {
            isShowModal.value = false;
            form.value.reset();

            toast.success("Music uploaded successfully!")
        },
        onError: () => {
            toast.error("Failed to upload music. Please try again.")
        },
        preserveScroll: true
    });
};

</script>

<template>
    <Button @click="isShowModal = true">
        <i class="ri-upload-line"></i>
        <span v-if="sidebarOpen" class="ms-2">Upload Music</span>
    </Button>

    <Modal
        :show="isShowModal"
        @close="isShowModal = false"
        class="max-w-3xl"
    >
        <Container>
            <template #header>
                <div class="flex justify-between items-center">
                    <header>
                        <h3 class="text-lg font-medium uppercase text-zinc-900 dark:text-zinc-100">
                            Upload Your Music
                        </h3>
                    </header>
                    <Button @click="submitButtonRef.click()">
                        <i class="ri-upload-line"></i>
                        <span>Upload</span>
                    </Button>
                </div>
            </template>
            <template #body>
                <form @submit.prevent="submit" class="space-y-4 p-6 text-zinc-900 dark:text-zinc-100">
                    <div class="space-y-2">
                        <label for="music">Music</label>
                        <InputText 
                            @change="onChangeFile"
                            name="music"
                            type="file" 
                            class="block w-full" 
                            accept="audio/*" 
                        />
                    </div>
                    <div class="space-y-2">
                        <label for="">Title</label>
                        <InputText 
                            v-model="form.title"
                            required
                            name="title"
                            type="text" 
                            class="block w-full" 
                            placeholder="Title of the track"
                        />
                    </div>
                    <div class="space-y-2">
                        <label for="generated_by">Where was the song generated?</label>
                        <InputText 
                            v-model="form.generated_by"
                            required
                            name="generated_by"
                            type="text" 
                            class="block w-full" 
                            placeholder="E.g: Stable Audio, Suno AI, MusicLM, AudioCraft, Mubert, SoundDraw Etc."
                        />
                    </div>
                    <div class="space-y-2">
                        <label for="generated_at">When was the song generated?</label>
                        <InputText 
                            v-model="form.generated_at"
                            required
                            name="generated_at"
                            type="date" 
                            class="block w-full" 
                        />
                    </div>
                    <div class="space-y-2">
                        <label for="description">Description</label>
                        <Textarea 
                            v-model="form.description"
                            name="description"
                            class="block w-full" 
                            rows="3"
                            auto-resize
                            placeholder="Describe your track..."
                        />
                    </div>
                    <div class="space-y-2">
                        <label for="">Lyrics</label>
                        <Textarea 
                            v-model="form.lyrics"
                            name="lyrics"
                            class="block w-full" 
                            rows="3"
                            placeholder="Lyrics of the track..."
                            auto-resize
                        />
                    </div>
                    <button ref="submitButtonRef" class="sr-only"></button>
                </form>
            </template>
        </Container>
    </Modal>
</template>