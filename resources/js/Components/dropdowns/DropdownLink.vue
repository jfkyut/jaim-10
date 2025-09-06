<script setup>

import { Link } from '@inertiajs/vue3';

defineProps({ to: String })

</script>

<template>
  <li>
    <Link
      :href="to"
      active-class="bg-zinc-100 dark:bg-zinc-600"
      class="block px-4 py-2 hover:bg-zinc-100 dark:hover:bg-zinc-600 dark:hover:text-white w-full text-start">
      <slot/>
    </Link>
  </li>
</template>
