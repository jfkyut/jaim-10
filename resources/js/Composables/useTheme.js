import { ref, onMounted, watch } from 'vue'

export function useTheme() {
    const currentTheme = ref(false)

    const toggleTheme = () => {
        if (currentTheme.value) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
        localStorage.setItem('theme', currentTheme.value ? 'dark' : 'light')
    }

    onMounted(() => {
        const savedTheme = localStorage.getItem('theme')
        currentTheme.value = savedTheme === 'dark'
        toggleTheme()
    })

    watch(currentTheme, toggleTheme)

    return {
        currentTheme,
        toggleTheme
    }
}