import { ref, watch } from 'vue';

const isDark = ref(localStorage.getItem('admin-dark-mode') === 'true');

watch(isDark, (val) => {
    localStorage.setItem('admin-dark-mode', val ? 'true' : 'false');
    document.body.classList.toggle('admin-dark', val);
});

// Apply on load if already dark
if (isDark.value) {
    document.body.classList.add('admin-dark');
}

export function useAdminTheme() {
    function toggle() {
        isDark.value = !isDark.value;
    }
    return { isDark, toggle };
}
