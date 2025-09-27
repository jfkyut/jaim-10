<script setup>

import { Button, InputText, Textarea } from 'primevue';
import Modal from '@/Components/Modal.vue';
import { ref, toRefs, watch } from 'vue';
import Container from '@/Components/containers/Container.vue';
import { useToast } from 'vue-toastification';
import { useForm } from '@inertiajs/vue3';
import UploadModal from '@/Pages/Creation/my-albums-partials/edit-album-partials/UploadModal.vue';

const props = defineProps({
    album: Object
});

const { album } = toRefs(props);

const isShowModal = ref(false);

const form = ref(useForm({
    _method: 'put',
    name: album.value.name,
    description: album.value.description,
    photo: null
}));

watch(
    () => isShowModal.value,
    (isShowModal) => {
        if (isShowModal) {
            form.value.name = album.value.name;
            form.value.description = album.value.description;
            form.value.photo = null;
        }
    }
)

const setPhoto = (event) => {
    form.value.photo = event.target.files[0];
};

const submitButtonRef = ref(null);
const toast = useToast();

const submit = (album) => {
    
    form.value.post(route('album.update', album.id), {
        onSuccess: () => {
            isShowModal.value = false;
            form.value.reset();

            toast.success("Album updated successfully!")
        },
        onError: () => {
            toast.error("Failed to update album. Please try again.")
        },
        preserveScroll: true
    });
}

</script>

<template>
    <Button @click="isShowModal = true">
        <i class="ri-edit-line"></i>
        <span>Edit</span>
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
                            Edit Album - {{ album.name }}
                        </h3>
                    </header>
                    <div class="flex gap-2 items-center">
                        <UploadModal :album="album" />
                        <Button @click="submitButtonRef.click()">
                            <i class="ri-save-line"></i>
                            <span>Save</span>
                        </Button>
                    </div>
                </div>
            </template>
            <template #body>
                <form @submit.prevent="submit(album)" class="space-y-4">
                    <div class="space-y-2">
                        <label for="">Album Name</label>
                        <InputText 
                            v-model="form.name"
                            class="w-full" 
                            placeholder="Album Name"
                        />
                    </div>
                    <div class="space-y-2">
                        <label for="">Description</label>
                        <Textarea 
                            v-model="form.description"
                            class="w-full" 
                            placeholder="Description"
                            rows="3"
                            auto-resize
                        />
                    </div>
                    <div class="space-y-2">
                        <label for="">Album Cover (optional)</label>
                        <InputText 
                            @change="setPhoto"
                            type="file" 
                            class="block w-full" 
                            accept="image/*" 
                        />
                    </div>
                    <button type="submit" class="sr-only" ref="submitButtonRef"></button>
                </form>
            </template>
        </Container>
    </Modal>
</template>