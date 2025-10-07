import { defineStore } from 'pinia';
import { ref, watch } from 'vue';

export const useCreatorFilterStore = defineStore('creator-filter-store', () => {
    const params = ref(JSON.parse(sessionStorage.getItem('creator-filter-params')) || {
        keyword: '',
    })

    watch(
        () => params.value, 
        (newVal) => {

            sessionStorage.setItem('creator-filter-params', JSON.stringify(newVal));
        }, 
        { deep: true }
    );

    return { params };
})