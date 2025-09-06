<script setup>
import LoadingButton from '../buttons/LoadingButton.vue';
import Modal from '../Modal.vue';
import DangerButton from '../buttons/DangerButton.vue';
import SecondaryButton from '../buttons/SecondaryButton.vue';
import { useThemeStore } from '@/Stores/theme';
import { storeToRefs } from 'pinia';

defineProps({
  show: {
    type: Boolean,
    default: true
  },
  title: {
    type: String,
    default: 'Are you sure you want to delete this product?'
  },
  isLoading: {
    type: Boolean,
    default: false
  }
})

const { theme } = storeToRefs(useThemeStore());

const emit = defineEmits(['onConfirm', 'onCancel']);
</script>

<template>
  <Modal :show="show" @close="emit('onCancel')" max-width="md">
    <div class="relative bg-white rounded-lg shadow dark:bg-zinc-700 z-[2000000]" :class="theme">
      <button @click="emit('onCancel')" type="button" class="absolute top-3 end-2.5 text-zinc-400 bg-transparent hover:bg-zinc-200 hover:text-zinc-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-zinc-600 dark:hover:text-white" data-modal-hide="popup-modal">
        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
        </svg>
        <span class="sr-only">Close modal</span>
      </button>
      <div class="p-4 md:p-5 text-center">
        <svg class="mx-auto mb-4 text-zinc-400 w-12 h-12 dark:text-zinc-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
        </svg>
        <h3 class="mb-5 text-lg font-normal text-zinc-500 dark:text-zinc-400">{{ title }}</h3>
        <div class="mb-5 text-start">
          <slot />
        </div>
        <div class="flex justify-center gap-3">
            <DangerButton v-if="!isLoading" @click="emit('onConfirm')" data-modal-hide="popup-modal" type="button">
                Yes, I'm sure
              </DangerButton>
              <LoadingButton button-type="danger" v-else />
              <SecondaryButton @click="emit('onCancel')" data-modal-hide="popup-modal" type="button">No, cancel</SecondaryButton>
        </div>
      </div>
    </div>
  </Modal>
</template>

<style scoped>
#popup-modal {
  z-index: 100000 !important;
}

#popup-modal .relative {
  z-index: 2000000 !important;
}
</style>
