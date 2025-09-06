<script setup>

// import Attachm
import FileAttachment from '@/Components/FileAttachment.vue';
import ExtraButton from '../buttons/ExtraButton.vue';
import { storeToRefs } from 'pinia';
import { useChatFormStore } from '@/Stores/messages/chatForm';

defineProps({ message: Object })

const { form } = storeToRefs(useChatFormStore());

const setParentMessage = (message) => {
  form.value.parent_message = message;

  console.log(form.value);
}

</script>

<template>
  

  <div
    :id="message?.id"
  >

    <!-- parent message -->
    <a :href="`#${message?.parent_message?.id}`" class="w-fit ml-auto block" v-if="message?.parent_message">
      <span>
        <i class="ri-reply-fill"></i>
        You replied to {{ message?.parent_message?.sender?.id === $page.props.auth.user?.id ? 'yourself' : message?.parent_message?.sender?.name }}
      </span>
      <div class="p-4 rounded-se-lg rounded-ss-lg bg-zinc-200 dark:bg-zinc-800 mb-2">
        {{ message?.parent_message?.text }}
      </div>
    </a>
    <!-- parent message end -->
    
    <div class="flex items-center justify-end gap-2.5">
      
      <div class="flex items-center">
        <ExtraButton @click="setParentMessage(message)">
          <i class="ri-reply-fill text-xl"></i>
        </ExtraButton>
      </div>

      <div class="flex flex-col gap-1 max-w-[300px] sm:max-w-[400px] md:max-w-[650px] lg:max-w-3xl">
        <div class="flex items-center space-x-2 justify-end">
          <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
            {{ message?.created_at }}
          </span>
          <span class="text-sm font-semibold text-gray-900 dark:text-white">
            {{ message?.sender?.name }}
            <!-- user -->
          </span>
        </div>
        <div class="flex flex-col leading-1.5 p-4 border-gray-200 bg-blue-200 rounded-ss-xl rounded-ee-xl rounded-es-xl dark:bg-blue-700 space-y-4">
            <p class="text-sm font-normal text-gray-900 dark:text-white whitespace-pre-wrap">
              {{ message?.text }}
                <!-- this is the message -->
            </p>
            <!-- {{ message?.attachments }} -->
            <!-- <ul class="list-disc list-inside">
              <li v-for="(attachment, index) in message?.attachments" :key="index">
                <FileAttachment :attachment="attachment" :delete-route="``" />
              </li>
            </ul> -->
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
        <!-- <span class="text-sm text-end font-normal text-gray-500 dark:text-gray-400">
          {{ message?.read_status !== 0 ? 'Seen' : 'Sent' }}
        </span> -->
      </div>
    </div>
  </div>

</template>