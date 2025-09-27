import { defineStore } from "pinia";
import { ref, watch } from "vue";

export const useMusicParamStore = defineStore('music-param-store', () => {
    const params = ref(JSON.parse(sessionStorage.getItem('music-explore-params')) || {
        keyword: '',
    })

    watch(
        () => params.value, 
        (newVal) => {

            console.log('params changed', newVal);
            

            sessionStorage.setItem('music-explore-params', JSON.stringify(newVal));
        }, 
        { deep: true }
    );

    return { params };
})