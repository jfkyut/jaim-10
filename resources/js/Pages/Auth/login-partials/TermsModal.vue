<script setup>

import { Button } from 'primevue';
import Modal from '@/Components/Modal.vue';
import Container from '@/Components/containers/Container.vue';
import { ref } from 'vue';

const isShowModal = ref(false);

</script>

<template>
    <span
        class="text-sm text-emerald-600 hover:underline dark:text-emerald-400 cursor-pointer"
        @click="isShowModal = true"
        severity="secondary"
        variant="link"
    >
        Terms and Conditions
    </span>

    <Modal
        :show="isShowModal"
        @close="isShowModal = false"
    >
        <Container>
            <template #header>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    Terms and Conditions
                </h3>
            </template>
            <template #body>
                <div class="space-y-4 text-sm text-gray-700 dark:text-gray-300">
                    <p class="font-bold">About JAM AI</p>
                    <p>JAM AI is a platform that distributes AI-generated music sourced from third parties or uploaded by rights-holding users. JAM AI does not create music itself.</p>

                    <p class="font-bold">Music Plans</p>
                    <ul class="list-disc list-inside ml-4 space-y-1">
                        <li>**Free Plan:**
                            <ul class="list-none ml-4">
                                <li>For personal uses</li>
                                <li>Ads supported (you will hear or see ads while using the platform)</li>
                                <li>No uploading of your own music (uploading available only in Pro)</li>
                                <li>Limited Access</li>
                                <li>Standard Audio quality</li>
                            </ul>
                        </li>
                        <li>**Pro Plan:**
                            <ul class="list-none ml-4">
                                <li>High audio quality</li>
                                <li>Full download access</li>
                                <li>Music upload allowed (if user owns rights)</li>
                            </ul>
                        </li>
                    </ul>

                    <p class="mt-4 p-2 border-l-4 border-yellow-500 bg-yellow-50 dark:bg-gray-800 dark:border-yellow-400">
                        **Note:** Each music track or song provided on JAM AI has a defined duration or length (in 3-5:00 minutes), which is clearly displayed on the platform. Users are licensed to use the music only within the duration specified and under the rights granted by their selected plan.
                    </p>

                    <p class="font-bold">Copyright</p>
                    <p>JAM AI licenses music but does not claim ownership. Users receive limited-use rights based on their plan. Reuploading or reselling tracks is prohibited. Takedown procedures are in place for copyright claims.</p>

                    <p class="font-bold">Acceptable Use</p>
                    <p>No use for illegal, violent, or harmful purposes. No NFT minting, sublicensing, or uploading music you don't own.</p>

                    <p class="font-bold">Intellectual Property</p>
                    <p>JAM AI owns its branding and interface; music remains with original creators. JAM AI operates under licensing deals.</p>

                    <p class="font-bold">Account Termination</p>
                    <p>Violations may result in account suspension or permanent bans.</p>

                    <p class="font-bold">Privacy</p>
                    <p>JAM AI collects user data (e.g., name, email, IP) for personalization and platform improvement. Data is protected, not sold, and shared only when necessary.</p>

                    <hr class="my-4 border-gray-200 dark:border-gray-700">

                    <p class="font-bold">Cookies</p>
                    <p>Used for performance and analytics. Users can manage them via browser settings.</p>

                    <p class="font-bold">User Rights</p>
                    <p>Users can request data access, correction, or deletion by contacting <a href="mailto:privacy@justaimusic.com" class="text-blue-500 hover:text-blue-400">privacy@justaimusic.com</a>.</p>

                    <p class="font-bold">Policy Changes</p>
                    <p>Terms may change. Continued use means acceptance of updates.</p>

                    <p class="font-bold">Contact</p>
                    <ul class="list-disc list-inside ml-4 space-y-1">
                        <li>General: <a href="mailto:support@justaimusic.com" class="text-blue-500 hover:text-blue-400">support@justaimusic.com</a></li>
                        <li>Privacy: <a href="mailto:privacy@justaimusic.com" class="text-blue-500 hover:text-blue-400">privacy@justaimusic.com</a></li>
                        <li>Copyright: <a href="mailto:copyright@justaimusic.com" class="text-blue-500 hover:text-blue-400">copyright@justaimusic.com</a></li>
                        <li>Website: <a href="https://www.justaimusic.com" target="_blank" class="text-blue-500 hover:text-blue-400">www.justaimusic.com</a></li>
                    </ul>

                    <p class="font-bold">Creator Criteria</p>
                    <ul class="list-disc list-inside ml-4 space-y-1">
                        <li>You must own or have permission for the music you upload.</li>
                        <li>No unauthorized copyrighted material (samples, loops, stems).</li>
                        <li>Provide correct metadata (title, artist, genre, copyright).</li>
                        <li>Meet JAM AI's audio quality standards.</li>
                        <li>No uploads that are already exclusively licensed elsewhere.</li>
                        <li>Agree to JAM AI's licensing terms.</li>
                        <li>Follow all community and content guidelines.</li>
                        <li>Not following these may lead to removal of your music or suspension of your Pro account.</li>
                    </ul>
                </div>
            </template>
        </Container>
    </Modal>
</template>