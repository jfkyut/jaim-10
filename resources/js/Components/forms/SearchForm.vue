<script setup>
import { useForm } from '@inertiajs/vue3';
import { toRefs } from 'vue';

const props = defineProps({ 
    url: {
        type: String,
        required: true,
    },
    params :{
        type: Object,
        required: true
    }
});

const { url, params } = toRefs(props);

const form = useForm(params.value);

</script>

<template>
    <form @submit.prevent="form.get(url)" class="max-w-md">   
        <label for="default-search" class="mb-2 text-sm font-medium text-zinc-900 sr-only dark:text-white">Search</label>
        <div class="relative">
            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                </svg>
            </div>
            <input v-model="form.keyword" type="search" id="default-search" class="block w-full p-4 ps-10 text-sm text-zinc-900 border border-zinc-300 rounded-lg bg-zinc-50 focus:ring-green-500 focus:border-green-500 dark:bg-zinc-700 dark:border-zinc-600 dark:placeholder-zinc-400 dark:text-white dark:focus:ring-green-500 dark:focus:border-green-500" placeholder="Search Mockups, Logos..." required />
            <button type="submit" class="text-white absolute end-2.5 bottom-2.5 bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Search</button>
        </div>
    </form>

</template>