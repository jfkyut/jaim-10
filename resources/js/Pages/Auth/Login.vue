<script setup>
// import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { InputText, Checkbox, Button } from 'primevue';
import { useToast } from 'vue-toastification';
import TermsModal from './login-partials/TermsModal.vue';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const toast = useToast();

const form = useForm({
    email: '',
    password: '',
    remember: false,
    agree: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
        onError: () => {
            toast.error('Login Failed. Please check your credentials and try again.');
        }
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <template #header>
            <h5>Sign in to our platform</h5>
        </template>

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        
        <form @submit.prevent="submit" class="space-y-6" action="#">

            <div>
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your email</label>
                <InputText type="email" v-model="form.email" name="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-teal-500 focus:border-teal-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="name@company.com" required />
            </div>
            <div>
                <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your password</label>
                <InputText type="password" v-model="form.password" name="password" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-teal-500 focus:border-teal-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required />
            </div>
            <div class="flex items-start">
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <Checkbox name="remember" v-model="form.remember" binary />
                    </div>
                    <label for="remember" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Remember me</label>
                </div>
                <a :href="route('password.request')" class="ms-auto text-sm text-teal-700 hover:underline dark:text-teal-500">Lost Password?</a>
            </div>
            <Button type="submit" class="w-full">Login to your account</button>
            
            <div class="flex justify-between items-center">
                <div class="text-sm font-medium text-gray-500 dark:text-gray-300">
                    Not registered? <a :href="route('register')" class="text-teal-700 hover:underline dark:text-teal-500">Create account</a>
                </div>
                <div>
                    <Checkbox v-model="form.agree" binary />
                    <TermsModal />
                    <div class="mt-2">
                        <InputError :message="form.errors.agree" class="mt-2" />
                    </div>
                </div>
            </div>
        </form>
    </GuestLayout>
</template>
