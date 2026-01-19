import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import api, { initCsrf } from '@/services/api';

export const useAuthStore = defineStore('auth', () => {
    const user = ref(null);
    const loading = ref(false);
    const error = ref(null);

    const isAuthenticated = computed(() => !!user.value);

    async function fetchUser() {
        try {
            const response = await api.get('/user');
            user.value = response.data;
            return true;
        } catch (e) {
            user.value = null;
            return false;
        }
    }

    async function login(email, password, remember = false) {
        loading.value = true;
        error.value = null;

        try {
            await initCsrf();
            const response = await api.post('/login', { email, password, remember });
            user.value = response.data.user;
            return true;
        } catch (e) {
            error.value = e.response?.data?.message || 'Login failed';
            if (e.response?.data?.errors?.email) {
                error.value = e.response.data.errors.email[0];
            }
            return false;
        } finally {
            loading.value = false;
        }
    }

    async function logout() {
        try {
            await api.post('/logout');
        } finally {
            user.value = null;
        }
    }

    async function checkAuth() {
        if (user.value) return true;
        return await fetchUser();
    }

    return {
        user,
        loading,
        error,
        isAuthenticated,
        login,
        logout,
        fetchUser,
        checkAuth,
    };
});
