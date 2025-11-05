<script setup>

defineProps({
    headerClass: String,
    bodyClass: String
})

</script>

<template>
    <div class="relative">
        <section :class="headerClass" class="bg-white p-3 border-t border-x shadow rounded-t-lg dark:bg-zinc-800 dark:border-zinc-700 sticky top-0 z-10">
            <slot name="header" />
        </section>
        <section :class="bodyClass" class="bg-white dark:bg-zinc-800 dark:border-zinc-700 p-3 border shadow-lg rounded-b-lg overflow-x-auto max-h-[70vh]">
            <slot name="body" />
        </section>
    </div>
</template>
