<script setup>

import { Button, Select, Textarea } from 'primevue';
import Modal from '@/Components/Modal.vue';
import Container from '@/Components/containers/Container.vue';
import { ref, Text, toRefs, watch } from 'vue';
import { useToast } from 'vue-toastification';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    music: Object
});

const { music } = toRefs(props);

const toast = useToast();
const isShowModal = ref(false);
const isLoading = ref(false);
const submitButtonRef = ref(null);

const statusOptions = [
    { label: 'Pending', value: 'pending' },
    { label: 'Under Review', value: 'under review' },
    { label: 'Approved', value: 'approved' },
    { label: 'Rejected', value: 'rejected' }
];

const form = ref(
    useForm({
        status: '',
        review_comments: ''
    })
)

const submit = (music) => {

    if (isLoading.value) return;

    form.value.put(
        route('music-approval.update', music.id),
        {
            onStart: () => {
                isLoading.value = true;
            },
            onFinish: () => {
                isLoading.value = false;
            },
            onSuccess: () => {
                isShowModal.value = false;
                toast.success('Music approval updated successfully.');
            },
            onError: () => {
                toast.error('An error occurred while updating music approval.');
            }
        }
    );
}

watch(isShowModal, (isShowModal) => {
    if (isShowModal) {
        form.value.status = music.value.status;
        form.value.review_comments = music.value.review_comments || '';

        console.log(form.value);
        
    } else {
        form.value.reset();
    }
})

</script>

<template>
    <Button @click="isShowModal = true" severity="info" variant="outlined">
        <i class="ri-edit-line"></i>
        <span>Edit</span>
    </Button>

    <Modal
        :show="isShowModal"
        @close="isShowModal = false"
        max-width="4xl"
    >
        <Container>
            <template #header>
                <div class="flex justify-between items-center">
                    <header class="flex items-center">
                        <h3 class="text-xl font-semibold text-zinc-900 dark:text-white uppercase">
                            Edit Music Approval - {{ music.title }}
                        </h3>
                    </header>
                    <div class="flex items-center gap-2">
                        <Button @click="submitButtonRef.click()">
                            <i class="ri-save-line"></i>
                            <span>Save</span>
                        </Button>
                    </div>
                </div>
            </template>
            <template #body>
                <form @submit.prevent="submit(music)">
                    <div class="space-y-4">
                        <div class="space-y-2 w-full">
                            <label for="status" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">
                                Status
                            </label>
                            <Select
                                v-model="form.status"
                                :options="statusOptions"
                                option-value="value"
                                option-label="label"
                                placeholder="Select status"
                                class="w-full"
                            />
                        </div>
                        <div class="space-y-2 w-full">
                            <label for="">Review Comment</label>
                            <Textarea
                                id="review_comments"
                                v-model="form.review_comments"
                                rows="4"
                                class="w-full"
                                placeholder="Enter review comments..."
                                auto-resize
                            />
                        </div>
                    </div>
                    <button type="submit" ref="submitButtonRef" class="sr-only"></button>
                </form>
            </template>
        </Container>
    </Modal>
</template>