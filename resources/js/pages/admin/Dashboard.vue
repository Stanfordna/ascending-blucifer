<template>
    <div class="space-y-6">
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div
                v-for="stat in stats"
                :key="stat.label"
                class="bg-white p-6 rounded-lg border border-gray-200"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-charcoal-light">{{ stat.label }}</p>
                        <p class="text-2xl font-semibold text-charcoal mt-1">{{ stat.value }}</p>
                    </div>
                    <div
                        class="w-12 h-12 rounded-lg flex items-center justify-center"
                        :class="stat.bgColor"
                    >
                        <component :is="stat.icon" class="w-6 h-6" :class="stat.iconColor" />
                    </div>
                </div>
                <router-link
                    v-if="stat.link"
                    :to="stat.link"
                    class="text-sm text-mountain-blue hover:text-mountain-blue-dark mt-3 inline-block"
                >
                    View all &rarr;
                </router-link>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Messages -->
            <div class="bg-white rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="font-semibold text-charcoal">Recent Messages</h2>
                    <router-link to="/admin/messages" class="text-sm text-mountain-blue hover:text-mountain-blue-dark">
                        View all
                    </router-link>
                </div>
                <div v-if="recentMessages.length" class="divide-y divide-gray-100">
                    <div
                        v-for="message in recentMessages"
                        :key="message.id"
                        class="px-6 py-4 hover:bg-gray-50 transition-colors"
                    >
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="font-medium text-charcoal">{{ message.name }}</p>
                                <p class="text-sm text-charcoal-light truncate max-w-xs">{{ message.subject || message.message }}</p>
                            </div>
                            <span
                                class="text-xs px-2 py-1 rounded"
                                :class="message.status === 'new' ? 'bg-terracotta/10 text-terracotta' : 'bg-gray-100 text-charcoal-light'"
                            >
                                {{ message.status }}
                            </span>
                        </div>
                    </div>
                </div>
                <div v-else class="px-6 py-8 text-center text-charcoal-light">
                    No recent messages
                </div>
            </div>

            <!-- Recent Bookings -->
            <div class="bg-white rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="font-semibold text-charcoal">Recent Bookings</h2>
                    <router-link to="/admin/bookings" class="text-sm text-mountain-blue hover:text-mountain-blue-dark">
                        View all
                    </router-link>
                </div>
                <div v-if="recentBookings.length" class="divide-y divide-gray-100">
                    <div
                        v-for="booking in recentBookings"
                        :key="booking.id"
                        class="px-6 py-4 hover:bg-gray-50 transition-colors"
                    >
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="font-medium text-charcoal">{{ booking.name }}</p>
                                <p class="text-sm text-charcoal-light">{{ booking.service?.title || 'General Inquiry' }}</p>
                            </div>
                            <span
                                class="text-xs px-2 py-1 rounded"
                                :class="statusClass(booking.status)"
                            >
                                {{ booking.status }}
                            </span>
                        </div>
                    </div>
                </div>
                <div v-else class="px-6 py-8 text-center text-charcoal-light">
                    No recent bookings
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, h } from 'vue';
import api from '@/services/api';

const stats = ref([
    { label: 'New Messages', value: 0, bgColor: 'bg-terracotta/10', iconColor: 'text-terracotta', link: '/admin/messages', icon: MessagesIcon },
    { label: 'Pending Bookings', value: 0, bgColor: 'bg-gold/10', iconColor: 'text-gold', link: '/admin/bookings', icon: BookingsIcon },
    { label: 'Blog Posts', value: 0, bgColor: 'bg-mountain-blue/10', iconColor: 'text-mountain-blue', link: '/admin/blog', icon: BlogIcon },
    { label: 'Services', value: 0, bgColor: 'bg-green-100', iconColor: 'text-green-600', link: '/admin/services', icon: ServicesIcon },
]);

const recentMessages = ref([]);
const recentBookings = ref([]);

// Icons
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

const BlogIcon = {
    render: () => h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
        h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z' })
    ])
};

const ServicesIcon = {
    render: () => h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
        h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10' })
    ])
};

function statusClass(status) {
    const classes = {
        pending: 'bg-gold/10 text-gold',
        confirmed: 'bg-green-100 text-green-600',
        cancelled: 'bg-gray-100 text-charcoal-light',
        completed: 'bg-mountain-blue/10 text-mountain-blue',
    };
    return classes[status] || 'bg-gray-100 text-charcoal-light';
}

async function fetchDashboardData() {
    try {
        const [messagesRes, bookingsRes, postsRes, servicesRes] = await Promise.all([
            api.get('/admin/contact-submissions?status=new'),
            api.get('/admin/booking-requests?status=pending'),
            api.get('/admin/blog-posts'),
            api.get('/admin/services'),
        ]);

        stats.value[0].value = messagesRes.data.total || messagesRes.data.length || 0;
        stats.value[1].value = bookingsRes.data.total || bookingsRes.data.length || 0;
        stats.value[2].value = postsRes.data.length || 0;
        stats.value[3].value = servicesRes.data.length || 0;

        recentMessages.value = (messagesRes.data.data || messagesRes.data).slice(0, 5);
        recentBookings.value = (bookingsRes.data.data || bookingsRes.data).slice(0, 5);
    } catch (e) {
        console.error('Failed to fetch dashboard data', e);
    }
}

onMounted(() => {
    fetchDashboardData();
});
</script>
