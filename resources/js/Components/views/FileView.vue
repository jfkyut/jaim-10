<script setup>

defineProps({ attachments: Object })

</script>

<template>
    <div class="grid grid-cols-6 gap-2" v-if="attachments.length > 0">
        <div v-for="(attachment, index) in attachments">

            
            <a
                v-if="!attachment.file_name.includes('doc')"
                target="_blank"
                :href="`/storage/${attachment.path}`"
                :key="index" class="p-2 hover:text-blue-500"
            >
                <!-- display if the file type is document -->
                <div class="flex justify-center" v-if="attachment.file_type.name === 'document'">
                    <i class="pi pi-file text-6xl"></i>
                </div>

                <!-- display if file type is equal to photo -->
                <div class="bg-gray-200 dark:bg-gray-500 flex justify-center min-h-[100px]" v-if="attachment.file_type.name === 'photo'">
                    <img :src="`/storage/${attachment.path}`" class="w-[100px]" alt="">
                </div>

                <!-- display if file type is equal to audio -->
                <div v-if="attachment.file_type.name === 'audio'">
                    <audio controls :src="`/storage/${attachment.path}`">
                        Your browser does not support audio element.
                    </audio>
                </div>

                <!-- display if file type is video -->
                <div v-if="attachment.file_type.name === 'video'">
                    <video controls :src="`/storage/${attachment.path}`">
                        Your browser does not support audio element.
                    </video>
                </div>

                <div class="text-center mt-2">
                    <p>{{ attachment.file_name }}</p>
                </div>
            </a>

            <a
                v-else  
                target="_blank"
                :href="route('document.preview.docx', { _query: { url: `/storage/${attachment.path}`, file_name: attachment.file_name } })" 
                class="p-2 hover:text-blue-500"
            >
                <!-- display if the file type is document -->
                <div class="flex justify-center" v-if="attachment.file_type.name === 'document'">
                    <i class="pi pi-file text-6xl"></i>
                </div>

                <!-- display if file type is equal to photo -->
                <div class="bg-gray-200 dark:bg-gray-500 flex justify-center min-h-[100px]" v-if="attachment.file_type.name === 'photo'">
                    <img :src="`/storage/${attachment.path}`" class="w-[100px]" alt="">
                </div>

                <!-- display if file type is equal to audio -->
                <div v-if="attachment.file_type.name === 'audio'">
                    <audio controls :src="`/storage/${attachment.path}`">
                        Your browser does not support audio element.
                    </audio>
                </div>

                <!-- display if file type is video -->
                <div v-if="attachment.file_type.name === 'video'">
                    <video controls :src="`/storage/${attachment.path}`">
                        Your browser does not support audio element.
                    </video>
                </div>

                <div class="text-center mt-2">
                    <p>{{ attachment.file_name }}</p>
                </div>
            </a>
        </div>    
    </div>

    <div v-else class="flex justify-center items-center min-h-[20vh]">
        Empty files
    </div>
</template>