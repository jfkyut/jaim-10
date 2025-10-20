<script setup>
import { useTheme } from '@/Composables/useTheme';
import { ToggleSwitch } from 'primevue';

const { currentTheme, toggleTheme } = useTheme();
</script>

<template>
    <div class="flex items-center gap-2">
        <ToggleSwitch
            v-model="currentTheme"
            @change="toggleTheme"
            class="theme-toggle"
        />
    </div>
</template>