<script setup>

import { Link } from '@inertiajs/vue3';

defineProps({ links: Array, params: Object });

/**
 * prompt below
 *
 * const objectToParams = (obj) => {
    return Object.entries(obj)
        .filter(([key, value]) => value !== null && value !== undefined) // Exclude null or undefined values
        .map(([key, value]) => ${encodeURIComponent(key)}=${encodeURIComponent(value)})
        .join('&');
}

<- this function transform an object into a query param but only if the shape of object is this ->

{
        keyword: null,
        year: null,
        council_no: null,
        resolution_classification: null
}

but what if, the shape of the object is this ->

{
        keyword: null,
        year: null,
        council_no: null,
        resolution_classification: null,
        author: {
            first_name: null,
            middle_name: null,
            last_name: null
        },
        co_author: {
            first_name: null,
            middle_name: null,
            last_name: null
        },
        proposal_author: {
            first_name: null,
            middle_name: null,
            last_name: null
        },
        proposal_co_author: {
            first_name: null,
            middle_name: null,
            last_name: null
        },
        status: null,
        type: null
    }

<- how do I transform it to query param string?
 *
 */

// chatgpt base code
const objectToParams = (obj, parentKey = '') => {
    return Object.entries(obj)
        .filter(([key, value]) => value !== null && value !== undefined) // Exclude null or undefined values
        .map(([key, value]) => {
            const fullKey = parentKey ? `${parentKey}[${key}]` : key;
            if (typeof value === 'object' && !Array.isArray(value)) {
                // Recursively handle nested objects
                return objectToParams(value, fullKey);
            } else {
                return `${encodeURIComponent(fullKey)}=${encodeURIComponent(value)}`;
            }
        })
        .filter(Boolean) // Filter out empty values
        .join('&');
};

</script>


<template>
    <nav aria-label="Page navigation example">
        <ul class="flex items-center -space-x-px h-8 text-sm">
            <li v-for="(link, index) in links" :key="index">
                <Link
                    :href="`${link.url ?? ''}${link?.url ? '&' : ''}${objectToParams(params)} `"
                    :class="[
                        index === 0
                            ? 'rounded-s-lg'
                            : index === links.length - 1
                            ? 'rounded-e-lg'
                            : '',
                        link.active === true
                            ? 'flex items-center justify-center px-3 h-8 text-blue-600 border border-zinc-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 dark:border-zinc-700 dark:bg-zinc-700 dark:text-white'
                            : 'flex items-center justify-center px-3 h-8 leading-tight text-zinc-500 bg-white border border-zinc-300 hover:bg-zinc-100 hover:text-zinc-700 dark:bg-zinc-800 dark:border-zinc-700 dark:text-zinc-400 dark:hover:bg-zinc-700 dark:hover:text-white'
                    ]"
                >
                    <span v-html="link.label" class="truncate"></span>
                </Link>
            </li>
        </ul>
    </nav>
</template>
