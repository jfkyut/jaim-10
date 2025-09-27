import { defineStore } from 'pinia';
import { ref, watch } from 'vue';

export const useMyAlbumStore = defineStore('my-album-store', () => {
    const params = ref(JSON.parse(sessionStorage.getItem('my-albums-params')) || {
        keyword: '',
    })

    watch(
        () => params.value, 
        (newVal) => {

            sessionStorage.setItem('my-albums-params', JSON.stringify(newVal));
        }, 
        { deep: true }
    );

    return { params };
})