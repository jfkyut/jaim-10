<script setup>

import LoadingButton from '../buttons/LoadingButton.vue';
import Modal from '../Modal.vue';
import PrimaryButton from '../buttons/PrimaryButton.vue';
import SecondaryButton from '../buttons/SecondaryButton.vue';
import { useThemeStore } from '@/Stores/theme';
import { storeToRefs } from 'pinia';

const { theme } = storeToRefs(useThemeStore());

defineProps({
  show: {
    type: Boolean,
    default: true
  },
  isForm: {
    type: Boolean,
    default: true
  },
  title: {
    type: String,
    default: 'Title'
  },
  cancelButtonText: {
    type: String,
    default: 'Cancel'
  },
  confirmButtonText: {
    type: String,
    default: 'Confirm'
  },
  isLoading: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['onConfirm', 'onCancel']);

</script>

<template>
  <Modal :show="show" @close="emit('onCancel')">
    <form @submit.prevent="emit('onConfirm')" :class="theme" class="bg-white rounded-lg shadow dark:bg-zinc-700">
      <!-- Modal header -->
      <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-zinc-600 min-w-[300px] w-full max-w-2xl">
        <h3 class="text-xl font-semibold text-zinc-900 dark:text-white uppercase">
          {{ title }}
        </h3>
        <button @click="emit('onCancel')" type="button" class="text-zinc-400 bg-transparent hover:bg-zinc-200 hover:text-zinc-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-zinc-600 dark:hover:text-white" data-modal-hide="default-modal">
          <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
          </svg>
          <span class="sr-only">Close modal</span>
        </button>
      </div>
      <!-- Modal body -->
      <div class="p-4 md:p-5 space-y-4 text-base leading-relaxed text-zinc-500 dark:text-zinc-400 max-h-[90vh] overflow-y-auto">
        <slot/>
      </div>
      <!-- Modal footer -->
      <div class="flex items-center gap-3 p-4 md:p-5 border-t border-zinc-200 rounded-b dark:border-zinc-600">
        <div v-if="isForm">
          <PrimaryButton v-if="!isLoading" data-modal-hide="default-modal" type="submit">
            {{ confirmButtonText }}
          </PrimaryButton>
          <LoadingButton v-else />
        </div>
        <slot name="extra-button" />
        <SecondaryButton @click="emit('onCancel')" data-modal-hide="default-modal" type="button">
          {{ cancelButtonText }}
        </SecondaryButton>
      </div>
    </form>
  </Modal>
</template>
