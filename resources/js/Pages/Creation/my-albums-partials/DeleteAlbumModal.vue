<script setup>

import { Button } from 'primevue';
import Modal from '@/Components/Modal.vue';
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { useToast } from 'vue-toastification';

defineProps({
    album: Object
});

const isShowModal = ref(false);
const toast = useToast();

const form = useForm({});

const confirmDelete = (album) => {
    form.delete(route('album.destroy', album.id), {
        onSuccess: () => {
            isShowModal.value = false;
            form.reset();

            toast.success("Album deleted successfully!")
        },
        onError: () => {
            toast.error("Failed to delete album. Please try again.")
        },
        preserveScroll: true
    });
};

</script>


<template>
    <Button @click="isShowModal = true" severity="danger">
        <i class="ri-delete-bin-line"></i>
        <span>Delete</span>
    </Button>

    <Modal
        :show="isShowModal"
        @close="isShowModal = false"
    >
        <div class="p-4">
            <h3 class="text-lg font-medium text-zinc-900 dark:text-zinc-100">
                Confirm Deletion
            </h3>
            <p class="mt-2 text-zinc-700 dark:text-zinc-300">
                Are you sure you want to delete this album? This action cannot be undone.
            </p>
            <div class="mt-4 flex justify-end gap-2">
                <Button severity="secondary" @click="isShowModal = false" class="me-2">
                    Cancel
                </Button>
                <form @submit.prevent="confirmDelete(album)">
                    <Button type="submit" severity="danger">
                        Delete
                    </Button>
                </form>
            </div>
        </div>
    </Modal>
</template>