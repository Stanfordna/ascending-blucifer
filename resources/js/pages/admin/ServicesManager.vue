<template>
    <div>
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <p class="text-charcoal-light">Manage the services displayed on your website.</p>
                <span v-if="services.length > 1" class="text-xs text-charcoal-light">Drag to reorder</span>
            </div>
            <div class="flex items-center gap-3">
                <a
                    href="/#services"
                    target="_blank"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-charcoal-light border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-charcoal transition-colors"
                >
                    Go to Section
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                </a>
                <button @click="openModal()" class="btn btn-primary">
                    Add Service
                </button>
            </div>
        </div>

        <!-- Loading -->
        <div v-if="loading" class="flex items-center justify-center py-12">
            <div class="animate-spin w-8 h-8 border-2 border-mountain-blue border-t-transparent rounded-full"></div>
        </div>

        <!-- Services List -->
        <div
            v-else-if="services.length"
            class="grid gap-3"
            @dragover.prevent
            @drop="handleDrop"
        >
            <div
                v-for="(service, index) in services"
                :key="service.id"
                :draggable="true"
                @dragstart="handleDragStart($event, index)"
                @dragover.prevent="handleDragOver($event, index)"
                @dragend="handleDragEnd"
                class="flex items-center gap-4 p-4 bg-white rounded-lg border cursor-move hover:shadow-sm transition-all"
                :class="{
                    'ring-2 ring-[var(--brand-primary)] bg-[var(--brand-primary)]/5': dragOverIndex === index,
                    'opacity-50': dragIndex === index
                }"
            >
                <!-- Drag handle -->
                <div class="text-gray-400 flex-shrink-0">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M7 2a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 2zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 8zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 14zm6-8a2 2 0 1 0-.001-4.001A2 2 0 0 0 13 6zm0 2a2 2 0 1 0 .001 4.001A2 2 0 0 0 13 8zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 13 14z"/>
                    </svg>
                </div>

                <!-- Icon -->
                <span class="text-2xl flex-shrink-0">{{ service.icon }}</span>

                <!-- Content -->
                <div class="flex-1 min-w-0">
                    <p class="font-medium text-charcoal">{{ service.title }}</p>
                    <p class="text-sm text-charcoal-light truncate">{{ service.description }}</p>
                </div>

                <!-- Status Badges -->
                <div class="flex items-center gap-2 flex-shrink-0">
                    <span
                        v-if="service.is_featured"
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gold/20 text-gold-dark"
                    >
                        Featured
                    </span>
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                        :class="service.is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600'"
                    >
                        {{ service.is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>

                <!-- Actions -->
                <ActionButtons
                    @edit="openModal(service)"
                    @delete="confirmDelete(service)"
                />
            </div>
        </div>

        <!-- Empty State -->
        <div v-else class="bg-white rounded-lg border border-gray-200 p-12 text-center">
            <p class="text-charcoal-light mb-4">No services yet.</p>
            <button @click="openModal()" class="btn btn-primary">
                Add your first service
            </button>
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
                    <label for="description" class="block text-sm font-medium text-charcoal mb-1">Short Description</label>
                    <textarea
                        id="description"
                        v-model="form.description"
                        rows="2"
                        required
                        placeholder="Brief description for the home page"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-mountain-blue focus:ring-1 focus:ring-mountain-blue outline-none resize-y"
                    ></textarea>
                </div>

                <div>
                    <label for="extended_description" class="block text-sm font-medium text-charcoal mb-1">Extended Description (optional)</label>
                    <textarea
                        id="extended_description"
                        v-model="form.extended_description"
                        rows="4"
                        placeholder="Detailed description for the services page"
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

                <div class="space-y-2">
                    <label class="flex items-center gap-2">
                        <input
                            v-model="form.is_active"
                            type="checkbox"
                            class="w-4 h-4 border-gray-300 text-mountain-blue focus:ring-mountain-blue rounded"
                        />
                        <span class="text-sm text-charcoal">Active (visible on website)</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input
                            v-model="form.is_featured"
                            type="checkbox"
                            class="w-4 h-4 border-gray-300 text-mountain-blue focus:ring-mountain-blue rounded"
                        />
                        <span class="text-sm text-charcoal">Featured (show on home page)</span>
                    </label>
                </div>

                <!-- Color Overrides -->
                <div class="border-t border-gray-200 pt-4 mt-4">
                    <h4 class="text-sm font-medium text-charcoal mb-3">Color Overrides (optional)</h4>
                    <div class="grid grid-cols-3 gap-3">
                        <ColorPicker v-model="form.color_primary" label="Primary" />
                        <ColorPicker v-model="form.color_secondary" label="Secondary" />
                        <ColorPicker v-model="form.color_accent" label="Accent" />
                    </div>
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
import ActionButtons from '@/components/admin/ActionButtons.vue';
import ColorPicker from '@/components/admin/ColorPicker.vue';

const toast = useToast();
const loading = ref(true);
const saving = ref(false);
const deleting = ref(false);
const services = ref([]);
const modalOpen = ref(false);
const deleteDialogOpen = ref(false);
const editingService = ref(null);
const deletingService = ref(null);

// Drag and drop state
const dragIndex = ref(null);
const dragOverIndex = ref(null);

const form = ref({
    title: '',
    description: '',
    extended_description: '',
    icon: '',
    link_url: '',
    is_active: true,
    is_featured: false,
    color_primary: '',
    color_secondary: '',
    color_accent: '',
});

function resetForm() {
    form.value = {
        title: '',
        description: '',
        extended_description: '',
        icon: '',
        link_url: '',
        is_active: true,
        is_featured: false,
        color_primary: '',
        color_secondary: '',
        color_accent: '',
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

// Drag and drop handlers
function handleDragStart(e, index) {
    dragIndex.value = index;
    e.dataTransfer.effectAllowed = 'move';
}

function handleDragOver(e, index) {
    e.preventDefault();
    dragOverIndex.value = index;
}

function handleDragEnd() {
    dragOverIndex.value = null;
}

async function handleDrop() {
    if (dragIndex.value === null || dragOverIndex.value === null) return;
    if (dragIndex.value === dragOverIndex.value) {
        dragIndex.value = null;
        dragOverIndex.value = null;
        return;
    }

    // Reorder the array
    const items = [...services.value];
    const [movedItem] = items.splice(dragIndex.value, 1);
    items.splice(dragOverIndex.value, 0, movedItem);
    services.value = items;

    dragIndex.value = null;
    dragOverIndex.value = null;

    // Save the new order to the server
    try {
        await api.post('/admin/services/reorder', {
            ids: services.value.map(s => s.id),
        });
        toast.success('Order updated');
    } catch (e) {
        toast.error('Failed to update order');
        fetchServices();
    }
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

onMounted(() => {
    fetchServices();
});
</script>
