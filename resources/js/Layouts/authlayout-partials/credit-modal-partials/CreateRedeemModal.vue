<script setup>

import { Button, InputText, Select } from 'primevue';
import Modal from '@/Components/Modal.vue';
import Container from '@/Components/containers/Container.vue';
import { ref } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { useToast } from 'vue-toastification';

const isShowModal = ref(false);
const toast = useToast();

const submitButtonRef = ref(null);

const page = usePage();

const form = ref(
    useForm({
        credits: 0,
        amount: 0,
        claim_through: null, // Optional field for claim method
        claim_number: null, // Optional field for claim method
    })
)

const claimMethods = [
    { label: 'GCash', value: 'gcash' },
    { label: 'Bank Transfer', value: 'bank_transfer' },
    { label: 'PayPal', value: 'paypal' },
];

// Watch credits and calculate amount
const calculateAmount = (credits) => {
    // Convert to number and divide by 1000
    form.value.amount = Number(credits) / 1000;
}

// Validate and update credits
const handleCreditsInput = (event) => {
    let value = parseInt(event.target.value);
    const maxCredits = page.props.auth.user.credits;

    // Ensure credits are not negative
    if (value < 0) value = 0;
    
    // Ensure credits don't exceed user's balance
    if (value > maxCredits) value = maxCredits;
    
    // Ensure credits are multiples of 1000
    value = Math.floor(value / 1000) * 1000;
    
    form.value.credits = value;
    calculateAmount(value);
}

const submit = () => {
    // Validate form
    if (form.value.credits < 1000) {
        toast.error('You must redeem at least 1,000 credits.');
        return;
    }

    // Submit form
    form.value.post(route('redeem.store'), {
        onSuccess: () => {
            isShowModal.value = false;
            toast.success('Redeem request submitted successfully.');
        },
        onError: (errors) => {
            toast.error('Failed to submit redeem request: ' + errors.message);
        }
    });
}

</script>

<template>
    <Button @click="isShowModal = true" :disabled="$page.props.auth.user.credits < 1000">
        Redeem Credits
    </Button>

    <Modal
        :show="isShowModal"
        @close="isShowModal = false"
    >
        <Container>
            <template #header>
                <div class="flex justify-between items-start">
                    <header>
                        <h3 class="text-2xl font-bold text-zinc-900 dark:text-white">
                            Convert Credits to Cash
                        </h3>
                        <p class="text-sm text-zinc-600 dark:text-zinc-300">
                            Convert your earned credits to Philippine Peso (₱)
                        </p>
                        <div class="mt-2 py-2 bg-zinc-100 dark:bg-zinc-800 rounded-lg">
                            <p class="text-sm font-medium">
                                Conversion Rate: <span class="text-emerald-600 dark:text-emerald-400">1,000 credits = ₱1.00</span>
                            </p>
                        </div>
                    </header>
                    <!-- buttons -->
                    <div class="flex gap-2 items-center">
                        <Button @click="submitButtonRef.click()">
                            Request
                        </Button>
                    </div>
                </div>
            </template>
            <template #body>
                <form @submit.prevent="submit" class="space-y-4 mt-4">
                    <div class="space-y-2">
                        <label class="font-medium text-zinc-800 dark:text-zinc-200" for="credits">
                            Credits to Convert
                        </label>
                        <InputText 
                            id="credits"
                            class="w-full"
                            type="number"
                            :step="1000"
                            :min="0"
                            :max="$page.props.auth.user.credits"
                            placeholder="Enter amount of credits (min: 1,000)"
                            v-model="form.credits"
                            @input="handleCreditsInput"
                        />
                        <small class="block text-sm text-zinc-500">
                            Available credits: {{ $page.props.auth.user.credits.toLocaleString() }}
                        </small>
                    </div>
                    <div class="space-y-2">
                        <label class="font-medium text-zinc-800 dark:text-zinc-200" for="amount">
                            Cash Value (PHP)
                        </label>
                        <InputText 
                            id="amount"
                            class="w-full"
                            type="number"
                            placeholder="Converted amount in PHP"
                            :model-value="form.amount"
                            disabled
                        />
                        <small class="block text-sm text-zinc-500">
                            This is the amount you'll receive in Philippine Peso
                        </small>
                    </div>
                    <div class="space-y-2 w-full">
                        <label for="">Claim Through</label>
                        <Select 
                            :options="claimMethods"
                            v-model="form.claim_through"
                            option-label="label"
                            option-value="value"
                            class="w-full"
                            placeholder="e.g. GCash, Bank Transfer, etc."
                            required
                        />
                        <small class="block text-sm text-zinc-500">
                            Specify how you want to receive your cash (e.g., GCash, Bank Transfer)
                        </small>
                    </div>
                    <div class="space-y-2 w-full">
                        <label for="claim_number">Claim Number</label>
                        <InputText 
                            id="claim_number"
                            class="w-full"
                            type="text"
                            placeholder="e.g. GCash number, Bank account number"
                            v-model="form.claim_number"
                            required
                        />
                        <small class="block text-sm text-zinc-500">
                            Provide your account number or ID for the claim method selected
                        </small>
                    </div>
                    <button type="submit" class="sr-only" ref="submitButtonRef"></button>
                </form>
            </template>
        </Container>
    </Modal>
</template>