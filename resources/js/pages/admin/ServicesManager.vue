<template>
    <div>
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <p class="text-charcoal-light">Manage the services displayed on your website.</p>
            <button @click="openModal()" class="btn btn-primary">
                Add Service
            </button>
        </div>

        <!-- Loading -->
        <div v-if="loading" class="flex items-center justify-center py-12">
            <div class="animate-spin w-8 h-8 border-2 border-mountain-blue border-t-transparent rounded-full"></div>
        </div>

        <!-- Services List -->
        <div v-else class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-charcoal-light uppercase tracking-wider w-16">Order</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-charcoal-light uppercase tracking-wider">Service</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-charcoal-light uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-charcoal-light uppercase tracking-wider w-32">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr
                        v-for="(service, index) in services"
                        :key="service.id"
                        class="hover:bg-gray-50 transition-colors"
                    >
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1">
                                <button
                                    v-if="index > 0"
                                    @click="moveService(index, -1)"
                                    class="p-1 hover:bg-gray-200 rounded transition-colors"
                                >
                                    <svg class="w-4 h-4 text-charcoal-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                    </svg>
                                </button>
                                <button
                                    v-if="index < services.length - 1"
                                    @click="moveService(index, 1)"
                                    class="p-1 hover:bg-gray-200 rounded transition-colors"
                                >
                                    <svg class="w-4 h-4 text-charcoal-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <span class="text-2xl">{{ service.icon }}</span>
                                <div>
                                    <p class="font-medium text-charcoal">{{ service.title }}</p>
                                    <p class="text-sm text-charcoal-light truncate max-w-md">{{ service.description }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                :class="service.is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600'"
                            >
                                {{ service.is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button
                                @click="openModal(service)"
                                class="text-mountain-blue hover:text-mountain-blue-dark mr-3"
                            >
                                Edit
                            </button>
                            <button
                                @click="confirmDelete(service)"
                                class="text-terracotta hover:text-terracotta/80"
                            >
                                Delete
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Edit Modal -->
        <Modal v-model="modalOpen" :title="editingService ? 'Edit Service' : 'Add Service'" size="lg">
            <form @submit.prevent="saveService" class="space-y-4">
                <div>
                    <label for="title" class="block text-sm font-medium text-charcoal mb-1">Title</label>
                    <input
                        id="title"
                        v-model="form.title"
                        type="text"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-mountain-blue focus:ring-1 focus:ring-mountain-blue outline-none"
                    />
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-charcoal mb-1">Description</label>
                    <textarea
                        id="description"
                        v-model="form.description"
                        rows="3"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-mountain-blue focus:ring-1 focus:ring-mountain-blue outline-none resize-y"
                    ></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="icon" class="block text-sm font-medium text-charcoal mb-1">Icon (emoji)</label>
                        <input
                            id="icon"
                            v-model="form.icon"
                            type="text"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-mountain-blue focus:ring-1 focus:ring-mountain-blue outline-none"
                        />
                    </div>

                    <div>
                        <label for="link_url" class="block text-sm font-medium text-charcoal mb-1">Link URL (optional)</label>
                        <input
                            id="link_url"
                            v-model="form.link_url"
                            type="text"
                            placeholder="/services/diabetes-education"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-mountain-blue focus:ring-1 focus:ring-mountain-blue outline-none"
                        />
                    </div>
                </div>

                <div>
                    <label class="flex items-center gap-2">
                        <input
                            v-model="form.is_active"
                            type="checkbox"
                            class="w-4 h-4 border-gray-300 text-mountain-blue focus:ring-mountain-blue rounded"
                        />
                        <span class="text-sm text-charcoal">Active (visible on website)</span>
                    </label>
                </div>
            </form>

            <template #footer>
                <div class="flex justify-end gap-3">
                    <button
                        @click="modalOpen = false"
                        class="px-4 py-2 text-sm font-medium text-charcoal-light hover:text-charcoal border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
                    >
                        Cancel
                    </button>
                    <button
                        @click="saveService"
                        class="btn btn-primary"
                        :disabled="saving"
                    >
                        {{ saving ? 'Saving...' : 'Save Service' }}
                    </button>
                </div>
            </template>
        </Modal>

        <!-- Delete Confirmation -->
        <ConfirmDialog
            v-model="deleteDialogOpen"
            title="Delete Service"
            :message="`Are you sure you want to delete '${deletingService?.title}'? This action cannot be undone.`"
            confirm-text="Delete"
            :loading="deleting"
            @confirm="deleteService"
        />
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '@/services/api';
import { useToast } from '@/stores/toast';
import Modal from '@/components/ui/Modal.vue';
import ConfirmDialog from '@/components/ui/ConfirmDialog.vue';

const toast = useToast();
const loading = ref(true);
const saving = ref(false);
const deleting = ref(false);
const services = ref([]);
const modalOpen = ref(false);
const deleteDialogOpen = ref(false);
const editingService = ref(null);
const deletingService = ref(null);

const form = ref({
    title: '',
    description: '',
    icon: '',
    link_url: '',
    is_active: true,
});

function resetForm() {
    form.value = {
        title: '',
        description: '',
        icon: '',
        link_url: '',
        is_active: true,
    };
}

function openModal(service = null) {
    editingService.value = service;
    if (service) {
        form.value = { ...service };
    } else {
        resetForm();
    }
    modalOpen.value = true;
}

function confirmDelete(service) {
    deletingService.value = service;
    deleteDialogOpen.value = true;
}

async function fetchServices() {
    try {
        const response = await api.get('/admin/services');
        services.value = response.data;
    } catch (e) {
        toast.error('Failed to load services');
    } finally {
        loading.value = false;
    }
}

async function saveService() {
    saving.value = true;
    try {
        if (editingService.value) {
            const response = await api.put(`/admin/services/${editingService.value.id}`, form.value);
            const index = services.value.findIndex(s => s.id === editingService.value.id);
            if (index > -1) services.value[index] = response.data;
            toast.success('Service updated');
        } else {
            const response = await api.post('/admin/services', form.value);
            services.value.push(response.data);
            toast.success('Service created');
        }
        modalOpen.value = false;
    } catch (e) {
        toast.error('Failed to save service');
    } finally {
        saving.value = false;
    }
}

async function deleteService() {
    deleting.value = true;
    try {
        await api.delete(`/admin/services/${deletingService.value.id}`);
        services.value = services.value.filter(s => s.id !== deletingService.value.id);
        deleteDialogOpen.value = false;
        toast.success('Service deleted');
    } catch (e) {
        toast.error('Failed to delete service');
    } finally {
        deleting.value = false;
    }
}

async function moveService(index, direction) {
    const newIndex = index + direction;
    const [service] = services.value.splice(index, 1);
    services.value.splice(newIndex, 0, service);

    try {
        await api.post('/admin/services/reorder', {
            ids: services.value.map(s => s.id),
        });
    } catch (e) {
        toast.error('Failed to update order');
        fetchServices(); // Revert on error
    }
}

onMounted(() => {
    fetchServices();
});
</script>
