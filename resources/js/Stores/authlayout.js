import { ref } from 'vue';
import { defineStore } from 'pinia';

export const useAuthLayoutStore = defineStore('authlayout-store', () => {
    // Initialize from sessionStorage or default to true
    const sidebarOpen = ref(sessionStorage.getItem('sidebarOpen') !== null 
        ? JSON.parse(sessionStorage.getItem('sidebarOpen')) 
        : true
    );

    const toggleSidebar = () => {
        sidebarOpen.value = !sidebarOpen.value;
        // Save to sessionStorage whenever the value changes
        sessionStorage.setItem('sidebarOpen', JSON.stringify(sidebarOpen.value));
    };

    return { sidebarOpen, toggleSidebar };
})