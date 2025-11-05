import { ref, onMounted, watch } from 'vue'

export function useTheme() {
    // Default to dark mode
    const currentTheme = ref(true)

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

        // If a theme is saved, use it; otherwise, stay dark by default
        if (savedTheme) {
            currentTheme.value = savedTheme === 'dark'
        } else {
            currentTheme.value = true // dark mode default
            localStorage.setItem('theme', 'dark')
        }

        toggleTheme()
    })

    watch(currentTheme, toggleTheme)

    return {
        currentTheme,
        toggleTheme
    }
}
