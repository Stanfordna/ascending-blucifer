import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '@/stores/auth';

// Layouts
import PublicLayout from '@/components/PublicLayout.vue';

// Lazy-loaded pages
const LoginPage = () => import('@/pages/LoginPage.vue');
const AdminLayout = () => import('@/components/admin/AdminLayout.vue');
const Dashboard = () => import('@/pages/admin/Dashboard.vue');
const SettingsEditor = () => import('@/pages/admin/SettingsEditor.vue');
const ContentBlocksEditor = () => import('@/pages/admin/ContentBlocksEditor.vue');
const ServicesManager = () => import('@/pages/admin/ServicesManager.vue');
const CredentialsManager = () => import('@/pages/admin/CredentialsManager.vue');
const BlogManager = () => import('@/pages/admin/BlogManager.vue');
const BlogEditor = () => import('@/pages/admin/BlogEditor.vue');
const TestimonialsManager = () => import('@/pages/admin/TestimonialsManager.vue');
const ContactSubmissions = () => import('@/pages/admin/ContactSubmissions.vue');
const BookingRequests = () => import('@/pages/admin/BookingRequests.vue');

const routes = [
    {
        path: '/',
        component: PublicLayout,
    },
    {
        path: '/login',
        name: 'login',
        component: LoginPage,
        meta: { guest: true },
    },
    {
        path: '/admin',
        component: AdminLayout,
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                redirect: '/admin/dashboard',
            },
            {
                path: 'dashboard',
                name: 'admin.dashboard',
                component: Dashboard,
            },
            {
                path: 'settings',
                name: 'admin.settings',
                component: SettingsEditor,
            },
            {
                path: 'section-text',
                name: 'admin.section-text',
                component: ContentBlocksEditor,
            },
            {
                path: 'services',
                name: 'admin.services',
                component: ServicesManager,
            },
            {
                path: 'credentials',
                name: 'admin.credentials',
                component: CredentialsManager,
            },
            {
                path: 'blog',
                name: 'admin.blog',
                component: BlogManager,
            },
            {
                path: 'blog/new',
                name: 'admin.blog.new',
                component: BlogEditor,
            },
            {
                path: 'blog/:id',
                name: 'admin.blog.edit',
                component: BlogEditor,
                props: true,
            },
            {
                path: 'testimonials',
                name: 'admin.testimonials',
                component: TestimonialsManager,
            },
            {
                path: 'messages',
                name: 'admin.messages',
                component: ContactSubmissions,
            },
            {
                path: 'bookings',
                name: 'admin.bookings',
                component: BookingRequests,
            },
        ],
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition;
        }
        if (to.hash) {
            return { el: to.hash, behavior: 'smooth' };
        }
        return { top: 0 };
    },
});

// Navigation guards
router.beforeEach(async (to, from, next) => {
    const auth = useAuthStore();

    // Check auth for protected routes
    if (to.meta.requiresAuth) {
        const isAuth = await auth.checkAuth();
        if (!isAuth) {
            return next({ name: 'login', query: { redirect: to.fullPath } });
        }
    }

    // Redirect authenticated users away from guest pages
    if (to.meta.guest && auth.isAuthenticated) {
        return next({ name: 'admin.dashboard' });
    }

    next();
});

export default router;
