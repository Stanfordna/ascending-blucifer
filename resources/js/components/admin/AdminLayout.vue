<template>
    <div class="min-h-screen bg-gray-50 flex">
        <!-- Sidebar -->
        <AdminSidebar :collapsed="sidebarCollapsed" @toggle="sidebarCollapsed = !sidebarCollapsed" />

        <!-- Main Content -->
        <div class="flex-1 flex flex-col" :class="sidebarCollapsed ? 'ml-16' : 'ml-64'">
            <!-- Header -->
            <header class="bg-white border-b border-gray-200 h-16 flex items-center justify-between px-6 sticky top-0 z-10">
                <div class="flex items-center gap-4">
                    <button
                        @click="sidebarCollapsed = !sidebarCollapsed"
                        class="p-2 hover:bg-gray-100 rounded-lg transition-colors lg:hidden"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <h1 class="text-lg font-semibold text-charcoal">{{ pageTitle }}</h1>
                </div>

                <div class="flex items-center gap-4">
                    <!-- View Site Link -->
                    <a
                        href="/"
                        target="_blank"
                        class="text-sm text-charcoal-light hover:text-mountain-blue transition-colors flex items-center gap-1"
                    >
                        View Site
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </a>

                    <!-- User Menu -->
                    <div class="relative" ref="userMenuRef">
                        <button
                            @click="userMenuOpen = !userMenuOpen"
                            class="flex items-center gap-2 p-2 hover:bg-gray-100 rounded-lg transition-colors"
                        >
                            <div class="w-8 h-8 bg-mountain-blue text-white rounded-full flex items-center justify-center text-sm font-medium">
                                {{ userInitials }}
                            </div>
                            <svg class="w-4 h-4 text-charcoal-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <Transition name="fade">
                            <div
                                v-if="userMenuOpen"
                                class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg py-1 z-20"
                            >
                                <div class="px-4 py-2 border-b border-gray-100">
                                    <p class="text-sm font-medium text-charcoal">{{ auth.user?.name }}</p>
                                    <p class="text-xs text-charcoal-light">{{ auth.user?.email }}</p>
                                </div>
                                <button
                                    @click="handleLogout"
                                    class="w-full text-left px-4 py-2 text-sm text-charcoal-light hover:bg-gray-50 hover:text-terracotta transition-colors"
                                >
                                    Sign Out
                                </button>
                            </div>
                        </Transition>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-6">
                <router-view />
            </main>
        </div>

        <!-- Toast Container -->
        <ToastContainer />
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import AdminSidebar from './AdminSidebar.vue';
import ToastContainer from '@/components/ui/ToastContainer.vue';

const route = useRoute();
const router = useRouter();
const auth = useAuthStore();

const sidebarCollapsed = ref(false);
const userMenuOpen = ref(false);
const userMenuRef = ref(null);

const pageTitle = computed(() => {
    const titles = {
        'admin.dashboard': 'Dashboard',
        'admin.settings': 'Site Settings',
        'admin.content': 'Content Blocks',
        'admin.services': 'Services',
        'admin.credentials': 'Credentials',
        'admin.blog': 'Blog Posts',
        'admin.blog.new': 'New Blog Post',
        'admin.blog.edit': 'Edit Blog Post',
        'admin.testimonials': 'Testimonials',
        'admin.messages': 'Contact Messages',
        'admin.bookings': 'Booking Requests',
    };
    return titles[route.name] || 'Admin';
});

const userInitials = computed(() => {
    const name = auth.user?.name || '';
    return name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2);
});

async function handleLogout() {
    await auth.logout();
    router.push('/login');
}

function handleClickOutside(event) {
    if (userMenuRef.value && !userMenuRef.value.contains(event.target)) {
        userMenuOpen.value = false;
    }
}

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
});
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.15s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
