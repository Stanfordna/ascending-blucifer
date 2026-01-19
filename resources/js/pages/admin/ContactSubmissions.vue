<template>
    <div>
        <!-- Header with Filters -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <select
                    v-model="statusFilter"
                    @change="fetchSubmissions"
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:border-mountain-blue focus:ring-1 focus:ring-mountain-blue outline-none"
                >
                    <option value="">All Messages</option>
                    <option value="new">New</option>
                    <option value="read">Read</option>
                    <option value="responded">Responded</option>
                    <option value="archived">Archived</option>
                </select>
            </div>
        </div>

        <!-- Loading -->
        <div v-if="loading" class="flex items-center justify-center py-12">
            <div class="animate-spin w-8 h-8 border-2 border-mountain-blue border-t-transparent rounded-full"></div>
        </div>

        <!-- Submissions List -->
        <div v-else-if="submissions.length" class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-charcoal-light uppercase tracking-wider">From</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-charcoal-light uppercase tracking-wider">Message</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-charcoal-light uppercase tracking-wider w-28">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-charcoal-light uppercase tracking-wider w-36">Date</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-charcoal-light uppercase tracking-wider w-24">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr
                        v-for="submission in submissions"
                        :key="submission.id"
                        class="hover:bg-gray-50 transition-colors cursor-pointer"
                        :class="{ 'bg-terracotta/5': submission.status === 'new' }"
                        @click="viewSubmission(submission)"
                    >
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-medium text-charcoal">{{ submission.name }}</p>
                                <p class="text-sm text-charcoal-light">{{ submission.email }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-charcoal-light truncate max-w-md">
                                {{ submission.subject ? `${submission.subject}: ` : '' }}{{ submission.message }}
                            </p>
                        </td>
                        <td class="px-6 py-4">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                :class="statusClass(submission.status)"
                            >
                                {{ submission.status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-charcoal-light">
                            {{ formatDate(submission.created_at) }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button
                                @click.stop="viewSubmission(submission)"
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
            <p class="text-charcoal-light">No messages found.</p>
        </div>

        <!-- View Modal -->
        <Modal v-model="modalOpen" :title="selectedSubmission?.subject || 'Message'" size="lg">
            <div v-if="selectedSubmission" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-charcoal-light mb-1">From</label>
                        <p class="text-charcoal">{{ selectedSubmission.name }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-charcoal-light mb-1">Email</label>
                        <a :href="`mailto:${selectedSubmission.email}`" class="text-mountain-blue hover:text-mountain-blue-dark">
                            {{ selectedSubmission.email }}
                        </a>
                    </div>
                    <div v-if="selectedSubmission.phone">
                        <label class="block text-xs font-medium text-charcoal-light mb-1">Phone</label>
                        <a :href="`tel:${selectedSubmission.phone}`" class="text-mountain-blue hover:text-mountain-blue-dark">
                            {{ selectedSubmission.phone }}
                        </a>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-charcoal-light mb-1">Received</label>
                        <p class="text-charcoal">{{ formatDate(selectedSubmission.created_at) }}</p>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-charcoal-light mb-1">Message</label>
                    <p class="text-charcoal whitespace-pre-wrap bg-gray-50 p-4 rounded-lg">{{ selectedSubmission.message }}</p>
                </div>

                <div>
                    <label class="block text-xs font-medium text-charcoal-light mb-1">Status</label>
                    <select
                        v-model="selectedSubmission.status"
                        @change="updateStatus"
                        class="px-3 py-2 border border-gray-300 rounded-lg focus:border-mountain-blue focus:ring-1 focus:ring-mountain-blue outline-none"
                    >
                        <option value="new">New</option>
                        <option value="read">Read</option>
                        <option value="responded">Responded</option>
                        <option value="archived">Archived</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-charcoal-light mb-1">Admin Notes</label>
                    <textarea
                        v-model="selectedSubmission.admin_notes"
                        rows="2"
                        placeholder="Add notes..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-mountain-blue focus:ring-1 focus:ring-mountain-blue outline-none resize-none"
                        @blur="updateStatus"
                    ></textarea>
                </div>
            </div>

            <template #footer>
                <div class="flex justify-end">
                    <button @click="modalOpen = false" class="btn btn-primary">
                        Close
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
const submissions = ref([]);
const statusFilter = ref('');
const modalOpen = ref(false);
const selectedSubmission = ref(null);

const pagination = reactive({
    currentPage: 1,
    lastPage: 1,
    from: 0,
    to: 0,
    total: 0,
});

function statusClass(status) {
    const classes = {
        new: 'bg-terracotta/10 text-terracotta',
        read: 'bg-gray-100 text-gray-600',
        responded: 'bg-green-100 text-green-800',
        archived: 'bg-charcoal/10 text-charcoal-light',
    };
    return classes[status] || 'bg-gray-100 text-gray-600';
}

function formatDate(date) {
    return new Date(date).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
        hour: 'numeric',
        minute: '2-digit',
    });
}

async function fetchSubmissions(page = 1) {
    loading.value = true;
    try {
        const params = { page };
        if (statusFilter.value) params.status = statusFilter.value;

        const response = await api.get('/admin/contact-submissions', { params });
        submissions.value = response.data.data;
        pagination.currentPage = response.data.current_page;
        pagination.lastPage = response.data.last_page;
        pagination.from = response.data.from || 0;
        pagination.to = response.data.to || 0;
        pagination.total = response.data.total;
    } catch (e) {
        toast.error('Failed to load messages');
    } finally {
        loading.value = false;
    }
}

function goToPage(page) {
    fetchSubmissions(page);
}

async function viewSubmission(submission) {
    selectedSubmission.value = { ...submission };
    modalOpen.value = true;

    if (submission.status === 'new') {
        try {
            await api.patch(`/admin/contact-submissions/${submission.id}`, { status: 'read' });
            const index = submissions.value.findIndex(s => s.id === submission.id);
            if (index > -1) submissions.value[index].status = 'read';
            selectedSubmission.value.status = 'read';
        } catch (e) {
            // Silent fail
        }
    }
}

async function updateStatus() {
    try {
        await api.patch(`/admin/contact-submissions/${selectedSubmission.value.id}`, {
            status: selectedSubmission.value.status,
            admin_notes: selectedSubmission.value.admin_notes,
        });
        const index = submissions.value.findIndex(s => s.id === selectedSubmission.value.id);
        if (index > -1) {
            submissions.value[index].status = selectedSubmission.value.status;
            submissions.value[index].admin_notes = selectedSubmission.value.admin_notes;
        }
        toast.success('Updated');
    } catch (e) {
        toast.error('Failed to update');
    }
}

onMounted(() => {
    fetchSubmissions();
});
</script>
