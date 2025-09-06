<script setup>

import { storeToRefs } from 'pinia';
import ExtraButton from '../buttons/ExtraButton.vue';
import { useChatFormStore } from '@/Stores/messages/chatForm';

defineProps({ message: Object });

const { form } = storeToRefs(useChatFormStore());

const setParentMessage = (message) => {
  form.value.parent_message = message;

  console.log(form.value);
}

</script>

<template>
  
  <!-- parent message -->
  <a :href="`#${message?.parent_message?.id}`" class="w-fit mr-auto ml-10 block" v-if="message?.parent_message">
    <span>
      <i class="ri-reply-fill"></i>
      Replied to {{ message?.parent_message?.sender?.id === message?.sender?.id ? 'themself' : message?.parent_message?.sender?.name }}
    </span>
    <div class="p-4 rounded-se-lg rounded-ss-lg bg-zinc-200 dark:bg-zinc-800 mb-2">
      {{ message?.parent_message?.text }}
    </div>
  </a>
  <!-- parent message end -->

  <div 
    class="flex gap-2.5" 
    :id="message?.id"

  >

    <div>
      <div
        v-if="message?.sender?.latest_photo?.length > 0"
        class="w-8 h-8 rounded-full bg-cover"
        :style="{ backgroundImage: `url('/storage/${message?.sender?.latest_photo[0]?.path}')` }"
      >
      </div>
      <img v-else class="w-8 h-8 rounded-full" src="https://t3.ftcdn.net/jpg/06/33/54/78/360_F_633547842_AugYzexTpMJ9z1YcpTKUBoqBF0CUCk10.jpg" alt="Jese image">
    </div>
    <div       class="flex flex-col gap-1 max-w-[300px] sm:max-w-[400px] md:max-w-[650px] lg:max-w-3xl">
      <div class="flex items-center space-x-2 rtl:space-x-reverse">
          <span class="text-sm font-semibold text-zinc-900 dark:text-white">
            {{ message?.sender?.name }}
            <!-- replier name here -->
          </span>
          <span class="text-sm font-normal text-zinc-500 dark:text-zinc-400">
            {{ message?.created_at }}
          </span>
      </div>
      <div class="flex flex-col leading-1.5 p-4 border-zinc-200 bg-zinc-100 rounded-e-xl rounded-es-xl dark:bg-zinc-700 space-y-4">
          <p class="text-sm font-normal text-zinc-900 dark:text-white whitespace-pre-wrap">
            {{ message?.text }}
            <!-- this is a sample response message -->
          </p>
          <ul class="list-disc list-inside break-words" v-if="message?.attachments?.length > 0">
            <li v-for="(attachment, index) in message?.attachments" :key="index">
              <a :href="`/storage/${attachment?.path}`" target="_blank" rel="noopener noreferrer" class="hover:text-red-500">
                {{ attachment?.file_name }}
              </a>
            </li>
          </ul>

          <ul class="list-inside break-words font-bold dark:text-gray-100">
            <li v-for="(user, index) in message?.tagged_users" :key="index">
              @{{ user?.name }}
            </li>
          </ul>
      </div>
      <!-- <span class="text-sm font-normal text-zinc-500 dark:text-zinc-400">
        {{ message?.read_status !== 0 ? 'Seen' : 'Sent' }}
      </span> -->
    </div>
    <div class="flex items-center">
      <ExtraButton @click="setParentMessage(message)">
        <i class="ri-reply-fill text-xl"></i>
      </ExtraButton>
    </div>
  </div>

</template>