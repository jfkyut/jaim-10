<script setup>

import { ref } from 'vue';
import { Button, Select } from 'primevue';
import Modal from '@/Components/Modal.vue';
import { useForm } from '@inertiajs/vue3';
import { useToast } from 'vue-toastification';
import { storeToRefs } from 'pinia';
import Container from '../containers/Container.vue';

const isShowModal = ref(false);
const submitButtonRef = ref(null);
const toast = useToast();

const props = defineProps({
    musicId: {
        type: Number,
        required: true
    }
});

const form = ref(useForm({
    playlist_id: '',
    music_id: props.musicId
}));

const submit = () => {
    form.value.post(route('playlist.add-music', { playlist: form.value.playlist_id }), {
        onSuccess: () => {
            isShowModal.value = false;
            form.value.reset();

            toast.success("Music added to playlist successfully!")
        },
        onError: () => {
            toast.error("Failed to add music to playlist. Please try again.")
        },
        preserveScroll: true
    });
};

</script>

<template>
    <Button 
        @click="isShowModal = true"
        severity="secondary"
        icon="ri-add-line"
        text
        class="w-full sm:w-auto hover:bg-neutral-100 dark:hover:bg-neutral-700"
        title="Add to playlist"
    >
        <i class="ri-add-line"></i>
    </Button>

    <Modal
        :show="isShowModal"
        @close="isShowModal = false"
    >
        <Container>
            <template #header>
                <div class="flex justify-between items-center">
                    <header>
                        <h3 class="text-lg font-medium uppercase text-zinc-900 dark:text-zinc-100">
                            Add to Playlist
                        </h3>
                    </header>
                    <div class="flex gap-2 items-center">
                        <Button @click="submitButtonRef.click()">
                            <i class="ri-save-line"></i>
                            <span class="ms-2">Add</span>
                        </Button>

                    </div>
                </div>
            </template>
            <template #body>
                <form @submit.prevent="submit">
                    <div class="mb-4">
                        <label for="playlist" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                            Select Playlist
                        </label>
                        
                        <Select 
                            filter
                            v-model="form.playlist_id"
                            :options="$page.props.auth.user.playlists"
                            option-label="name"
                            option-value="id"
                            placeholder="Select a playlist"
                            class="w-full"
                        />
                    </div>
                    <button type="submit" ref="submitButtonRef" class="hidden"></button>
                </form>
            </template>
        </Container>
    </Modal>
</template>