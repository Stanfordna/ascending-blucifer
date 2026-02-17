import axios from 'axios';

const api = axios.create({
    baseURL: '/api',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
    },
    withCredentials: true,
});

// Get CSRF cookie before making requests
export async function initCsrf() {
    await axios.get('/sanctum/csrf-cookie');
}

// Response interceptor for handling auth errors
api.interceptors.response.use(
    (response) => response,
    (error) => {
        // Only redirect to login for admin routes, not for auth checks or public API calls
        if (error.response?.status === 401) {
            const url = error.config?.url || '';
            const isAdminRoute = url.includes('/admin/');
            if (isAdminRoute) {
                window.location.href = '/login';
            }
        }
        return Promise.reject(error);
    }
);

export default api;
