<template>
    <div>
        <!-- Header with Filters -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <SelectMenu
                    v-model="statusFilter"
                    @change="() => fetchBookings()"
                    :options="[
                        { value: '', label: 'All Bookings' },
                        { value: 'pending', label: 'Pending' },
                        { value: 'confirmed', label: 'Confirmed' },
                        { value: 'completed', label: 'Completed' },
                        { value: 'cancelled', label: 'Cancelled' },
                    ]"
                    placeholder="All Bookings"
                />
            </div>

            <div class="flex items-center gap-3">
                <label class="flex items-center gap-2 cursor-pointer">
                    <span class="text-sm text-charcoal-light">Booking Requests</span>
                    <button
                        type="button"
                        @click="toggleBooking"
                        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors"
                        :class="bookingEnabled ? 'bg-mountain-blue' : 'bg-gray-300'"
                    >
                        <span
                            class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"
                            :class="bookingEnabled ? 'translate-x-6' : 'translate-x-1'"
                        />
                    </button>
                </label>
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
                        <th class="px-6 py-3 text-right text-xs font-medium text-charcoal-light uppercase tracking-wider w-36"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr
                        v-for="booking in bookings"
                        :key="booking.id"
                        class="hover:bg-gray-50 transition-colors cursor-pointer"
                        :class="{ 'bg-gold/5': booking.status === 'pending' }"
                        @click="viewBooking(booking)"
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
                        <td class="px-6 py-4">
                            <ActionChips
                                :actions="bookingStatuses"
                                :exclude="booking.status"
                                @action="quickStatus(booking, $event)"
                            />
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
                        <SelectMenu
                            v-model="selectedBooking.status"
                            :options="[
                                { value: 'pending', label: 'Pending' },
                                { value: 'confirmed', label: 'Confirmed' },
                                { value: 'completed', label: 'Completed' },
                                { value: 'cancelled', label: 'Cancelled' },
                            ]"
                        />
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
import SelectMenu from '@/components/ui/SelectMenu.vue';
import ActionChips from '@/components/admin/ActionChips.vue';

const toast = useToast();
const loading = ref(true);
const saving = ref(false);
const bookings = ref([]);
const bookingEnabled = ref(false);
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

const bookingStatuses = [
    { key: 'pending', tooltip: 'Set to Pending', color: '#D4A84B', gradient: 'linear-gradient(135deg, #D4A84B, #E5C070)', paths: ['M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'] },
    { key: 'confirmed', tooltip: 'Mark Confirmed', color: '#166534', gradient: 'linear-gradient(135deg, #16a34a, #22c55e)', paths: ['M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'] },
    { key: 'completed', tooltip: 'Mark Completed', color: '#5B7FA4', gradient: 'linear-gradient(135deg, #5B7FA4, #7A9BB8)', paths: ['M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'] },
    { key: 'cancelled', tooltip: 'Mark Cancelled', color: '#6b7280', gradient: 'linear-gradient(135deg, #6b7280, #9ca3af)', paths: ['M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'] },
];

async function quickStatus(booking, status) {
    const prev = booking.status;
    booking.status = status;
    try {
        await api.patch(`/admin/booking-requests/${booking.id}`, { status });
        toast.success(`Marked as ${status}`);
    } catch (e) {
        booking.status = prev;
        toast.error('Failed to update status');
    }
}

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

async function fetchBookingToggle() {
    try {
        const response = await api.get('/admin/contact-form-config');
        const setting = response.data.find(s => s.key === 'booking_requests_enabled');
        bookingEnabled.value = setting?.value === '1';
    } catch (e) {
        // Defaults
    }
}

async function toggleBooking() {
    bookingEnabled.value = !bookingEnabled.value;
    try {
        await api.put('/admin/contact-form-config', {
            settings: [{ key: 'booking_requests_enabled', value: bookingEnabled.value ? '1' : '0' }],
        });
        toast.success(bookingEnabled.value ? 'Booking requests enabled' : 'Booking requests disabled');
    } catch (e) {
        bookingEnabled.value = !bookingEnabled.value;
        toast.error('Failed to update');
    }
}

onMounted(() => {
    fetchBookings();
    fetchBookingToggle();
});
</script>
