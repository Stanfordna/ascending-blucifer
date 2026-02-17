import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useSettingsStore = defineStore('settings', () => {
    // Initialize from embedded data or fall back to defaults
    const embeddedColors = window.__SITE_COLORS__ || {};

    const colors = ref({
        primary: embeddedColors.primary || '#4A7C89',
        secondary: embeddedColors.secondary || '#C4785A',
        accent: embeddedColors.accent || '#D4A84B',
    });

    function initColors() {
        applyColors();
    }

    function setColors(newColors) {
        colors.value = {
            primary: newColors.primary || colors.value.primary,
            secondary: newColors.secondary || colors.value.secondary,
            accent: newColors.accent || colors.value.accent,
        };
        applyColors();
    }

    function applyColors() {
        const root = document.documentElement;
        root.style.setProperty('--brand-primary', colors.value.primary);
        root.style.setProperty('--brand-secondary', colors.value.secondary);
        root.style.setProperty('--brand-accent', colors.value.accent);
    }

    return { colors, initColors, setColors, applyColors };
});
