import { ref } from 'vue';
import { defineStore } from 'pinia';

export const useAuthLayoutStore = defineStore('authlayout-store', () => {
    // Initialize from sessionStorage or default to true
    const sidebarOpen = ref(sessionStorage.getItem('sidebarOpen') !== null 
        ? JSON.parse(sessionStorage.getItem('sidebarOpen')) 
        : true
    );

    const openSidebar = () => {
        sidebarOpen.value = true;
        sessionStorage.setItem('sidebarOpen', JSON.stringify(sidebarOpen.value));
    };

    const closeSidebar = () => {
        sidebarOpen.value = false;
        sessionStorage.setItem('sidebarOpen', JSON.stringify(sidebarOpen.value));
    };

    const toggleSidebar = () => {
        sidebarOpen.value = !sidebarOpen.value;
        // Save to sessionStorage whenever the value changes
        sessionStorage.setItem('sidebarOpen', JSON.stringify(sidebarOpen.value));
    };

    return { sidebarOpen, toggleSidebar, openSidebar, closeSidebar };
})