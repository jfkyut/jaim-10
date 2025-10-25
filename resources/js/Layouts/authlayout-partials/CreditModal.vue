<script setup>

import DropdownButton from '@/Components/dropdowns/DropdownButton.vue';
import Modal from '@/Components/Modal.vue';
import Container from '@/Components/containers/Container.vue';
import { ref } from 'vue';
import { Tag, Button } from 'primevue';
import CreateRedeemModal from './credit-modal-partials/CreateRedeemModal.vue';

const isShowModal = ref(false);

</script>

<template>
    <DropdownButton @click="isShowModal = true" class="flex justify-between items-center">
        <span>Credits</span>
        <Tag 
            :value="$page.props.auth.user.credits" 
            severity="info" 
            rounded 
        />
    </DropdownButton>

    <Modal
        :show="isShowModal"
        @close="isShowModal = false"
    >
        <Container>
            <template #header>
                <div class="flex items-center justify-between">
                    <header>
                        <h3 class="text-xl font-semibold text-zinc-900 dark:text-white uppercase">
                            Your Credits
                        </h3>
                    </header>
                    <CreateRedeemModal />
                </div>
                
            </template>
            <template #body>
                <div class="text-center">
                    You have <strong>{{ $page.props.auth.user.credits }}</strong> credits.
                </div>

                <!-- Note about conversion eligibility -->
                <div class="mt-4 text-center text-sm text-gray-600">
                    <template v-if="$page.props.auth.user.credits >= 1000">
                        You have at least 1,000 credits — you can convert them. 
                    </template>
                    <template v-else>
                        You need at least <strong>1,000</strong> credits to convert.
                    </template>
                </div>
            </template>
        </Container>
    </Modal>
</template>