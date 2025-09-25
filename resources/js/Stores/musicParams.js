import { defineStore } from "pinia";
import { ref } from "vue";

export const useMusicParamStore = defineStore('music-param-store', () => {
    const params = ref({
        keyword: 'test',
    })

    return { params };
})