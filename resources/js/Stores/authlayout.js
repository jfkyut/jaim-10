import { ref } from 'vue';
import { defineStore } from 'pinia';

export const useAuthLayoutStore = defineStore('authlayout-store', () => {
    const sidebarOpen = ref(true);

    const toggleSidebar = () => {
        sidebarOpen.value = !sidebarOpen.value;
    };

    return { sidebarOpen, toggleSidebar };
})