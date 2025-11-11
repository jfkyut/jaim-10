<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});
</script>

<template>
    <Head title="JAM - AI Music Streaming Platform" />

    <div class="relative min-h-screen bg-gradient-to-br from-green-900 to-black">
        <!-- Navigation -->
        <div v-if="canLogin" class="sm:fixed sm:top-0 sm:right-0 p-6 text-end">
            <Link
                v-if="$page.props.auth.user"
                :href="route('dashboard')"
                class="font-semibold text-gray-200 hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-green-500"
            >Home</Link>

            <template v-else>
                <Link
                    :href="route('login')"
                    class="font-semibold text-gray-200 hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-green-500"
                    >Log in</Link>

                <Link
                    v-if="canRegister"
                    :href="route('register')"
                    class="ms-4 font-semibold text-gray-200 hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-green-500"
                    >Register</Link>
            </template>
        </div>

        <!-- Hero Section -->
        <div class="max-w-7xl mx-auto px-6 lg:px-8 pt-32">
            <div class="text-center">
                <h1 class="text-5xl font-bold tracking-tight text-white sm:text-7xl">
                    The Future of AI Music
                </h1>
                <p class="mt-6 text-lg leading-8 text-gray-300">
                    Discover, stream, and share AI-generated music. Join the community of creators and listeners shaping the future of music.
                </p>
                <div class="mt-10 flex items-center justify-center gap-x-6">
                    <a href="/register" class="rounded-md bg-green-600 px-8 py-3 text-lg font-semibold text-white shadow-sm hover:bg-green-500">
                        Start Creating
                    </a>
                    <a href="/music/explore" class="text-lg font-semibold leading-6 text-white">
                        Explore Music <span aria-hidden="true">→</span>
                    </a>
                </div>
            </div>

            <!-- Features Grid -->
            <div class="mt-32 grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6">
                    <div class="h-16 w-16 bg-green-600/20 flex items-center justify-center rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="w-8 h-8 stroke-green-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 9l10.5-3m0 6.553v3.75a2.25 2.25 0 01-1.632 2.163l-1.32.377a1.803 1.803 0 11-.99-3.467l2.31-.66a2.25 2.25 0 001.632-2.163zm0 0V2.25L9 5.25v10.303m0 0v3.75a2.25 2.25 0 01-1.632 2.163l-1.32.377a1.803 1.803 0 01-.99-3.467l2.31-.66A2.25 2.25 0 009 15.553z" />
                        </svg>
                    </div>
                    <h2 class="mt-6 text-xl font-semibold text-white">Upload AI Music</h2>
                    <p class="mt-4 text-gray-400">Share your AI-generated tracks with the world. Simple uploads, powerful reach.</p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6">
                    <div class="h-16 w-16 bg-green-600/20 flex items-center justify-center rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="w-8 h-8 stroke-green-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                        </svg>
                    </div>
                    <h2 class="mt-6 text-xl font-semibold text-white">Creator Community</h2>
                    <p class="mt-4 text-gray-400">Connect with AI music creators, collaborate, and grow your audience.</p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6">
                    <div class="h-16 w-16 bg-green-600/20 flex items-center justify-center rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="w-8 h-8 stroke-green-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h2 class="mt-6 text-xl font-semibold text-white">Monetize Your Music</h2>
                    <p class="mt-4 text-gray-400">Earn from your AI-generated tracks through streams.</p>
                </div>

                <!-- Feature 4 -->
                <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6">
                    <div class="h-16 w-16 bg-green-600/20 flex items-center justify-center rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="w-8 h-8 stroke-green-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
                        </svg>
                    </div>
                    <h2 class="mt-6 text-xl font-semibold text-white">Discover AI Genres</h2>
                    <p class="mt-4 text-gray-400">Explore unique AI-generated genres and styles. Find the next evolution in music.</p>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-32 pb-16 text-center text-gray-400">
                <p>© 2025 JAIM. All rights reserved.</p>
            </div>
        </div>
    </div>
</template>

<style>
/* Remove the existing background styles and add new ones */
body {
    margin: 0;
    padding: 0;
}

.bg-gradient-to-br {
    background-size: cover;
    background-attachment: fixed;
}
</style>