<template>
    <div>
        <!-- Header with Filters -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <select
                    v-model="statusFilter"
                    @change="fetchBookings"
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:border-mountain-blue focus:ring-1 focus:ring-mountain-blue outline-none"
                >
                    <option value="">All Bookings</option>
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
        </div>

        <!-- Loading -->
        <div v-if="loading" class="flex items-center justify-center py-12">
            <div class="animate-spin w-8 h-8 border-2 border-mountain-blue border-t-transparent rounded-full"></div>
        </div>

        <!-- Bookings List -->
        <div v-else-if="bookings.length" class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-charcoal-light uppercase tracking-wider">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-charcoal-light uppercase tracking-wider">Service</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-charcoal-light uppercase tracking-wider">Preferred Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-charcoal-light uppercase tracking-wider w-28">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-charcoal-light uppercase tracking-wider w-32">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr
                        v-for="booking in bookings"
                        :key="booking.id"
                        class="hover:bg-gray-50 transition-colors"
                        :class="{ 'bg-gold/5': booking.status === 'pending' }"
                    >
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-medium text-charcoal">{{ booking.name }}</p>
                                <p class="text-sm text-charcoal-light">{{ booking.email }}</p>
                                <span
                                    v-if="booking.is_new_client"
                                    class="text-xs text-mountain-blue"
                                >
                                    New Client
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-charcoal">
                            {{ booking.service?.title || booking.service_other || 'Not specified' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-charcoal-light">
                            <div v-if="booking.preferred_date_1">
                                {{ formatDate(booking.preferred_date_1) }}
                                <span v-if="booking.preferred_time_1"> at {{ booking.preferred_time_1 }}</span>
                            </div>
                            <div v-if="booking.preferred_date_2" class="text-xs">
                                Alt: {{ formatDate(booking.preferred_date_2) }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                :class="statusClass(booking.status)"
                            >
                                {{ booking.status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button
                                @click="viewBooking(booking)"
                                class="text-mountain-blue hover:text-mountain-blue-dark"
                            >
                                View
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Pagination -->
            <div v-if="pagination.lastPage > 1" class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                <p class="text-sm text-charcoal-light">
                    Showing {{ pagination.from }} to {{ pagination.to }} of {{ pagination.total }}
                </p>
                <div class="flex gap-2">
                    <button
                        @click="goToPage(pagination.currentPage - 1)"
                        :disabled="pagination.currentPage === 1"
                        class="px-3 py-1 text-sm border border-gray-300 rounded hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        Previous
                    </button>
                    <button
                        @click="goToPage(pagination.currentPage + 1)"
                        :disabled="pagination.currentPage === pagination.lastPage"
                        class="px-3 py-1 text-sm border border-gray-300 rounded hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        Next
                    </button>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div v-else class="bg-white rounded-lg border border-gray-200 p-12 text-center">
            <p class="text-charcoal-light">No booking requests found.</p>
        </div>

        <!-- View/Edit Modal -->
        <Modal v-model="modalOpen" title="Booking Request" size="lg">
            <div v-if="selectedBooking" class="space-y-4">
                <!-- Client Info -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-charcoal-light mb-1">Client Name</label>
                        <p class="text-charcoal">{{ selectedBooking.name }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-charcoal-light mb-1">Email</label>
                        <a :href="`mailto:${selectedBooking.email}`" class="text-mountain-blue hover:text-mountain-blue-dark">
                            {{ selectedBooking.email }}
                        </a>
                    </div>
                    <div v-if="selectedBooking.phone">
                        <label class="block text-xs font-medium text-charcoal-light mb-1">Phone</label>
                        <a :href="`tel:${selectedBooking.phone}`" class="text-mountain-blue hover:text-mountain-blue-dark">
                            {{ selectedBooking.phone }}
                        </a>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-charcoal-light mb-1">Client Type</label>
                        <p class="text-charcoal">{{ selectedBooking.is_new_client ? 'New Client' : 'Returning Client' }}</p>
                    </div>
                </div>

                <!-- Service -->
                <div>
                    <label class="block text-xs font-medium text-charcoal-light mb-1">Service Requested</label>
                    <p class="text-charcoal">{{ selectedBooking.service?.title || selectedBooking.service_other || 'Not specified' }}</p>
                </div>

                <!-- Preferred Times -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-charcoal-light mb-1">Preferred Date/Time 1</label>
                        <p class="text-charcoal">
                            {{ selectedBooking.preferred_date_1 ? formatDate(selectedBooking.preferred_date_1) : 'Not specified' }}
                            <span v-if="selectedBooking.preferred_time_1"> at {{ selectedBooking.preferred_time_1 }}</span>
                        </p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-charcoal-light mb-1">Preferred Date/Time 2</label>
                        <p class="text-charcoal">
                            {{ selectedBooking.preferred_date_2 ? formatDate(selectedBooking.preferred_date_2) : 'Not specified' }}
                            <span v-if="selectedBooking.preferred_time_2"> at {{ selectedBooking.preferred_time_2 }}</span>
                        </p>
                    </div>
                </div>

                <!-- Message -->
                <div v-if="selectedBooking.message">
                    <label class="block text-xs font-medium text-charcoal-light mb-1">Message</label>
                    <p class="text-charcoal whitespace-pre-wrap bg-gray-50 p-4 rounded-lg">{{ selectedBooking.message }}</p>
                </div>

                <!-- Status & Scheduling -->
                <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-200">
                    <div>
                        <label class="block text-xs font-medium text-charcoal-light mb-1">Status</label>
                        <select
                            v-model="selectedBooking.status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-mountain-blue focus:ring-1 focus:ring-mountain-blue outline-none"
                        >
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-charcoal-light mb-1">Scheduled For</label>
                        <input
                            v-model="selectedBooking.scheduled_datetime"
                            type="datetime-local"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-mountain-blue focus:ring-1 focus:ring-mountain-blue outline-none"
                        />
                    </div>
                </div>

                <!-- Admin Notes -->
                <div>
                    <label class="block text-xs font-medium text-charcoal-light mb-1">Admin Notes</label>
                    <textarea
                        v-model="selectedBooking.admin_notes"
                        rows="2"
                        placeholder="Add notes..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-mountain-blue focus:ring-1 focus:ring-mountain-blue outline-none resize-none"
                    ></textarea>
                </div>
            </div>

            <template #footer>
                <div class="flex justify-end gap-3">
                    <button
                        @click="modalOpen = false"
                        class="px-4 py-2 text-sm font-medium text-charcoal-light hover:text-charcoal border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
                    >
                        Cancel
                    </button>
                    <button
                        @click="updateBooking"
                        class="btn btn-primary"
                        :disabled="saving"
                    >
                        {{ saving ? 'Saving...' : 'Save Changes' }}
                    </button>
                </div>
            </template>
        </Modal>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import api from '@/services/api';
import { useToast } from '@/stores/toast';
import Modal from '@/components/ui/Modal.vue';

const toast = useToast();
const loading = ref(true);
const saving = ref(false);
const bookings = ref([]);
const statusFilter = ref('');
const modalOpen = ref(false);
const selectedBooking = ref(null);

const pagination = reactive({
    currentPage: 1,
    lastPage: 1,
    from: 0,
    to: 0,
    total: 0,
});

function statusClass(status) {
    const classes = {
        pending: 'bg-gold/10 text-gold',
        confirmed: 'bg-green-100 text-green-800',
        completed: 'bg-mountain-blue/10 text-mountain-blue',
        cancelled: 'bg-gray-100 text-gray-600',
    };
    return classes[status] || 'bg-gray-100 text-gray-600';
}

function formatDate(date) {
    if (!date) return '';
    return new Date(date).toLocaleDateString('en-US', {
        weekday: 'short',
        month: 'short',
        day: 'numeric',
        year: 'numeric',
    });
}

async function fetchBookings(page = 1) {
    loading.value = true;
    try {
        const params = { page };
        if (statusFilter.value) params.status = statusFilter.value;

        const response = await api.get('/admin/booking-requests', { params });
        bookings.value = response.data.data;
        pagination.currentPage = response.data.current_page;
        pagination.lastPage = response.data.last_page;
        pagination.from = response.data.from || 0;
        pagination.to = response.data.to || 0;
        pagination.total = response.data.total;
    } catch (e) {
        toast.error('Failed to load bookings');
    } finally {
        loading.value = false;
    }
}

function goToPage(page) {
    fetchBookings(page);
}

function viewBooking(booking) {
    selectedBooking.value = {
        ...booking,
        scheduled_datetime: booking.scheduled_datetime
            ? new Date(booking.scheduled_datetime).toISOString().slice(0, 16)
            : '',
    };
    modalOpen.value = true;
}

async function updateBooking() {
    saving.value = true;
    try {
        const response = await api.patch(`/admin/booking-requests/${selectedBooking.value.id}`, {
            status: selectedBooking.value.status,
            scheduled_datetime: selectedBooking.value.scheduled_datetime || null,
            admin_notes: selectedBooking.value.admin_notes,
        });

        const index = bookings.value.findIndex(b => b.id === selectedBooking.value.id);
        if (index > -1) {
            bookings.value[index] = response.data;
        }

        modalOpen.value = false;
        toast.success('Booking updated');
    } catch (e) {
        toast.error('Failed to update booking');
    } finally {
        saving.value = false;
    }
}

onMounted(() => {
    fetchBookings();
});
</script>
