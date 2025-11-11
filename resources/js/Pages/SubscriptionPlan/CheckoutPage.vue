<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { onMounted, ref } from 'vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    redirectUrl: String
})

const linkRef = ref(null);

onMounted(() => {
    setTimeout(() => {
        if (linkRef.value) {
            linkRef.value.click();
        }
    }, 1000);
})

</script>

<template>
    <Head title="Checkout Page" />
    <AuthenticatedLayout>
        <template #header>
            Checkout
        </template>

        <div class="p-4 max-w-7xl mx-auto">
            <h2 class="text-2xl font-semibold mb-8 text-center">Checkout Page</h2>
            <!-- Checkout form and details go here -->

            <div class="text-center mt-6">
                <a :href="redirectUrl" ref="linkRef" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                    Proceed to Payment
                </a>
            </div>
        </div>
    </AuthenticatedLayout>

</template>