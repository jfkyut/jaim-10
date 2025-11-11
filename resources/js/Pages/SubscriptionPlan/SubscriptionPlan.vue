<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { Button } from 'primevue';
import { useToast } from 'vue-toastification';

const props = defineProps({
    plans: Array,
    currentPlan: Object
});

const toast = useToast();
const form = useForm({});

function subscribe(plan) {
    form.post(route('subscription.subscribe', plan.id), {
        onSuccess: () => {
            toast.info('Checking out plan!');
        },
        onError: () => {
            toast.error('Failed to subscribe to the plan. Please try again.');
        }
    });
}

function getPlanStatus(plan) {
    if (props.currentPlan?.plan_id === plan.id) {
        return plan.is_free ? 'Your Free Plan' : 'Current Plan';
    }
    return plan.is_free ? 'Default Free Plan' : 'Select Plan';
}

function getPlanBadge(plan) {
    if (plan.is_free) {
        return 'Free';
    }
    return plan.recommended ? 'Recommended' : '';
}
</script>

<template>
    <Head title="Subscription Plans" />

    <AuthenticatedLayout>
        <template #header>
            Subscription Plans
        </template>

        <div class="p-4 max-w-7xl mx-auto">
            <h2 class="text-2xl font-semibold mb-8 text-center">Choose Your Plan</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div v-for="plan in plans" :key="plan.name" 
                     :class="[
                         'border dark:border-zinc-600 bg-white dark:bg-zinc-800 rounded-lg p-6 shadow-sm transition-shadow relative'
                     ]">
                    <div v-if="getPlanBadge(plan)" 
                         :class="[
                             'absolute -top-3 left-1/2 transform -translate-x-1/2 px-4 py-1 rounded-full text-sm text-white',
                             plan.is_free ? 'bg-green-500' : 'bg-blue-500'
                         ]">
                        {{ getPlanBadge(plan) }}
                    </div>
                    <h3 class="text-xl font-bold mb-2">{{ plan.name }}</h3>
                    <p class="text-3xl font-bold mb-4">
                        ₱{{ plan.price }}
                        <span class="text-sm text-gray-600">{{ plan.price === 0 ? '' : '/month' }}</span>
                    </p>
                    
                    <ul class="space-y-3 mb-6">
                        <li v-for="feature in plan.features" :key="feature" 
                            class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ feature }}
                        </li>
                    </ul>
                    
                    <Button 
                        :class="[
                            'w-full',
                            plan.is_free ? 'p-button-success' : 'p-button-primary'
                        ]"
                        @click="subscribe(plan)"
                        :disabled="currentPlan?.plan_id === plan.id || plan.is_free"
                    >
                        {{ getPlanStatus(plan) }}
                    </Button>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>