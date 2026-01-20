<template>
    <aside
        class="fixed left-0 top-0 h-screen bg-charcoal text-white transition-all duration-300 z-20"
        :class="collapsed ? 'w-16' : 'w-64'"
    >
        <!-- Logo -->
        <div class="h-16 flex items-center px-4 border-b border-white/10">
            <router-link to="/admin/dashboard" class="flex items-center gap-3 overflow-hidden">
                <div class="w-8 h-8 bg-gold rounded flex items-center justify-center flex-shrink-0">
                    <span class="font-script text-charcoal text-lg">M</span>
                </div>
                <span v-if="!collapsed" class="font-serif text-lg whitespace-nowrap">Admin</span>
            </router-link>
        </div>

        <!-- Navigation -->
        <nav class="p-3 space-y-1">
            <router-link
                v-for="item in navItems"
                :key="item.to"
                :to="item.to"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors"
                :class="[
                    isActive(item.to)
                        ? 'bg-mountain-blue text-white'
                        : 'text-white/70 hover:bg-white/10 hover:text-white'
                ]"
            >
                <component :is="item.icon" class="w-5 h-5 flex-shrink-0" />
                <span v-if="!collapsed" class="text-sm whitespace-nowrap">{{ item.label }}</span>
            </router-link>
        </nav>

        <!-- Hover zone for toggle (right edge) -->
        <div
            class="absolute right-0 top-0 h-full w-3 cursor-pointer group"
            @click="$emit('toggle')"
            @mouseenter="hoverActive = true"
            @mouseleave="hoverActive = false"
        >
            <!-- Visual indicator - subtle gradient showing interactive edge -->
            <div class="absolute inset-0 bg-gradient-to-r from-transparent to-white/5 group-hover:to-white/10 transition-colors"></div>

            <!-- Chevron overlay (appears on hover) -->
            <Transition name="chevron">
                <div
                    v-if="hoverActive"
                    class="absolute top-1/2 -translate-y-1/2 -right-3 w-6 h-12 bg-charcoal-light rounded-r-lg flex items-center justify-center shadow-lg border-r border-y border-white/10"
                >
                    <svg
                        class="w-4 h-4 text-white/80 transition-transform"
                        :class="collapsed ? 'rotate-180' : ''"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </div>
            </Transition>
        </div>
    </aside>
</template>

<script setup>
import { h, ref } from 'vue';
import { useRoute } from 'vue-router';

defineProps({
    collapsed: Boolean,
});

defineEmits(['toggle']);

const route = useRoute();
const hoverActive = ref(false);

// Icon components
const DashboardIcon = {
    render: () => h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
        h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z' })
    ])
};

const SettingsIcon = {
    render: () => h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
        h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z' }),
        h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M15 12a3 3 0 11-6 0 3 3 0 016 0z' })
    ])
};

const ContentIcon = {
    render: () => h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
        h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z' })
    ])
};

const ServicesIcon = {
    render: () => h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
        h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10' })
    ])
};

const CredentialsIcon = {
    render: () => h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
        h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z' })
    ])
};

const BlogIcon = {
    render: () => h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
        h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z' })
    ])
};

const TestimonialsIcon = {
    render: () => h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
        h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z' })
    ])
};

const MessagesIcon = {
    render: () => h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
        h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z' })
    ])
};

const BookingsIcon = {
    render: () => h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
        h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z' })
    ])
};

const navItems = [
    { to: '/admin/dashboard', label: 'Dashboard', icon: DashboardIcon },
    { to: '/admin/settings', label: 'Site Settings', icon: SettingsIcon },
    { to: '/admin/section-text', label: 'Section Text', icon: ContentIcon },
    { to: '/admin/services', label: 'Services', icon: ServicesIcon },
    { to: '/admin/credentials', label: 'Credentials', icon: CredentialsIcon },
    { to: '/admin/blog', label: 'Blog Posts', icon: BlogIcon },
    { to: '/admin/testimonials', label: 'Testimonials', icon: TestimonialsIcon },
    { to: '/admin/messages', label: 'Messages', icon: MessagesIcon },
    { to: '/admin/bookings', label: 'Bookings', icon: BookingsIcon },
];

function isActive(path) {
    return route.path.startsWith(path);
}
</script>

<style scoped>
.chevron-enter-active,
.chevron-leave-active {
    transition: opacity 0.15s ease, transform 0.15s ease;
}

.chevron-enter-from,
.chevron-leave-to {
    opacity: 0;
    transform: translate(4px, -50%);
}

.chevron-enter-to,
.chevron-leave-from {
    opacity: 1;
    transform: translate(0, -50%);
}
</style>
