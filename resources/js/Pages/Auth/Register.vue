<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Button, InputText, Select } from 'primevue';

const form = useForm({
    first_name: '',
    last_name: '',
    email: '',
    gender: '',
    birthday: '',
    password: '',
    password_confirmation: '',
});

const genderoptions = [
    'male',
    'female',
    'prefer not to tell'
]

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <template #header>
            <h5>Create Account</h5>
        </template>

        <form @submit.prevent="submit">
            
            <div class="flex gap-2">
                <div>
                    <InputLabel for="first_name" value="First name" />

                    <InputText
                        id="first_name"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.first_name"
                        placeholder="Input first name"
                        required
                        autofocus
                        autocomplete="first_name"
                    />

                    <InputError class="mt-2" :message="form.errors.first_name" />
                </div>
                <div>
                    <InputLabel for="last_name" value="Last name" />

                    <InputText
                        id="last_name"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.last_name"
                        placeholder="Input last name"
                        required
                        autofocus
                        autocomplete="last_name"
                    />

                    <InputError class="mt-2" :message="form.errors.last_name" />
                </div>
            </div>

            <div class="flex gap-2 mt-4">
                <div class="w-full">
                    <InputLabel for="birthday" value="Date of birth" />

                    <InputText
                        id="birthday"
                        type="date"
                        class="mt-1 block w-full"
                        v-model="form.birthday"
                        required
                        autofocus
                        autocomplete="birthday"
                    />

                    <InputError class="mt-2" :message="form.errors.birthday" />
                </div>
                <div class="w-full space-y-1">
                    <InputLabel for="gender" value="Gender" />

                    <Select :options="genderoptions" class="w-full" v-model="form.gender" placeholder="Select gender" />

                    <InputError class="mt-2" :message="form.errors.gender" />
                </div>
            </div>

            <div class="mt-4">
                <InputLabel for="email" value="Email" />

                <InputText
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                    placeholder="sample@email.com"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />

                <InputText
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                    placeholder="Input password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4">
                <InputLabel for="password_confirmation" value="Confirm Password" />

                <InputText
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="Confirm password"
                />

                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link
                    :href="route('login')"
                    class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                >
                    Already registered?
                </Link>

                <Button type="submit" class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Register
                </Button>
            </div>
        </form>
    </GuestLayout>
</template>
