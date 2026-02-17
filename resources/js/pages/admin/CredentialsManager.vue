<template>
    <div>
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <p class="text-charcoal-light">Manage your professional credentials and certifications.</p>
                <span v-if="credentials.length > 1" class="text-xs text-charcoal-light">Drag to reorder</span>
            </div>
            <div class="flex items-center gap-3">
                <a
                    href="/#about"
                    target="_blank"
                    class="btn btn-outline"
                >
                    Go to Section
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                </a>
                <button @click="openModal()" class="btn btn-primary">
                    Add Credential
                </button>
            </div>
        </div>

        <!-- Loading -->
        <div v-if="loading" class="flex items-center justify-center py-12">
            <div class="animate-spin w-8 h-8 border-2 border-mountain-blue border-t-transparent rounded-full"></div>
        </div>

        <!-- Credentials List -->
        <div
            v-else-if="credentials.length"
            class="grid gap-3"
            @dragover.prevent
            @drop="handleDrop"
        >
            <div
                v-for="(credential, index) in credentials"
                :key="credential.id"
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

                <!-- Content -->
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <p class="font-medium text-charcoal">{{ credential.name }}</p>
                        <span v-if="credential.abbreviation" class="text-sm text-charcoal-light">({{ credential.abbreviation }})</span>
                    </div>
                    <p v-if="credential.institution" class="text-sm text-charcoal-light">{{ credential.institution }}</p>
                </div>

                <!-- Status -->
                <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium flex-shrink-0"
                    :class="credential.is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600'"
                >
                    {{ credential.is_active ? 'Active' : 'Inactive' }}
                </span>

                <!-- Actions -->
                <ActionChips
                    :actions="crudActions"
                    @action="$event === 'edit' ? openModal(credential) : confirmDelete(credential)"
                />
            </div>
        </div>

        <!-- Empty State -->
        <div v-else class="bg-white rounded-lg border border-gray-200 p-12 text-center">
            <p class="text-charcoal-light mb-4">No credentials yet.</p>
            <button @click="openModal()" class="btn btn-primary">
                Add your first credential
            </button>
        </div>

        <!-- Edit Modal -->
        <Modal v-model="modalOpen" :title="editingCredential ? 'Edit Credential' : 'Add Credential'">
            <form @submit.prevent="saveCredential" class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-charcoal mb-1">Name</label>
                    <input
                        id="name"
                        v-model="form.name"
                        type="text"
                        required
                        placeholder="e.g., Registered Dietitian"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-mountain-blue focus:ring-1 focus:ring-mountain-blue outline-none"
                    />
                </div>

                <div>
                    <label for="abbreviation" class="block text-sm font-medium text-charcoal mb-1">Abbreviation (optional)</label>
                    <input
                        id="abbreviation"
                        v-model="form.abbreviation"
                        type="text"
                        placeholder="e.g., RD"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-mountain-blue focus:ring-1 focus:ring-mountain-blue outline-none"
                    />
                </div>

                <div>
                    <label for="institution" class="block text-sm font-medium text-charcoal mb-1">Institution (optional)</label>
                    <input
                        id="institution"
                        v-model="form.institution"
                        type="text"
                        placeholder="e.g., Louisiana Tech University"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-mountain-blue focus:ring-1 focus:ring-mountain-blue outline-none"
                    />
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
                        class="btn btn-ghost"
                    >
                        Cancel
                    </button>
                    <button
                        @click="saveCredential"
                        class="btn btn-primary"
                        :disabled="saving"
                    >
                        {{ saving ? 'Saving...' : 'Save Credential' }}
                    </button>
                </div>
            </template>
        </Modal>

        <!-- Delete Confirmation -->
        <ConfirmDialog
            v-model="deleteDialogOpen"
            title="Delete Credential"
            :message="`Are you sure you want to delete '${deletingCredential?.name}'?`"
            confirm-text="Delete"
            :loading="deleting"
            @confirm="deleteCredential"
        />
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '@/services/api';
import { useToast } from '@/stores/toast';
import Modal from '@/components/ui/Modal.vue';
import ConfirmDialog from '@/components/ui/ConfirmDialog.vue';
import ActionChips, { CRUD_ACTIONS } from '@/components/admin/ActionChips.vue';

const toast = useToast();
const loading = ref(true);
const saving = ref(false);
const deleting = ref(false);
const credentials = ref([]);
const modalOpen = ref(false);
const deleteDialogOpen = ref(false);
const editingCredential = ref(null);
const deletingCredential = ref(null);

// Drag and drop state
const dragIndex = ref(null);
const dragOverIndex = ref(null);

const crudActions = [CRUD_ACTIONS.edit, CRUD_ACTIONS.delete];

const form = ref({
    name: '',
    abbreviation: '',
    institution: '',
    is_active: true,
});

function resetForm() {
    form.value = {
        name: '',
        abbreviation: '',
        institution: '',
        is_active: true,
    };
}

function openModal(credential = null) {
    editingCredential.value = credential;
    if (credential) {
        form.value = { ...credential };
    } else {
        resetForm();
    }
    modalOpen.value = true;
}

function confirmDelete(credential) {
    deletingCredential.value = credential;
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
    const items = [...credentials.value];
    const [movedItem] = items.splice(dragIndex.value, 1);
    items.splice(dragOverIndex.value, 0, movedItem);
    credentials.value = items;

    dragIndex.value = null;
    dragOverIndex.value = null;

    // Save the new order to the server
    try {
        await api.post('/admin/credentials/reorder', {
            ids: credentials.value.map(c => c.id),
        });
        toast.success('Order updated');
    } catch (e) {
        toast.error('Failed to update order');
        fetchCredentials();
    }
}

async function fetchCredentials() {
    try {
        const response = await api.get('/admin/credentials');
        credentials.value = response.data;
    } catch (e) {
        toast.error('Failed to load credentials');
    } finally {
        loading.value = false;
    }
}

async function saveCredential() {
    saving.value = true;
    try {
        if (editingCredential.value) {
            const response = await api.put(`/admin/credentials/${editingCredential.value.id}`, form.value);
            const index = credentials.value.findIndex(c => c.id === editingCredential.value.id);
            if (index > -1) credentials.value[index] = response.data;
            toast.success('Credential updated');
        } else {
            const response = await api.post('/admin/credentials', form.value);
            credentials.value.push(response.data);
            toast.success('Credential created');
        }
        modalOpen.value = false;
    } catch (e) {
        toast.error('Failed to save credential');
    } finally {
        saving.value = false;
    }
}

async function deleteCredential() {
    deleting.value = true;
    try {
        await api.delete(`/admin/credentials/${deletingCredential.value.id}`);
        credentials.value = credentials.value.filter(c => c.id !== deletingCredential.value.id);
        deleteDialogOpen.value = false;
        toast.success('Credential deleted');
    } catch (e) {
        toast.error('Failed to delete credential');
    } finally {
        deleting.value = false;
    }
}

onMounted(() => {
    fetchCredentials();
});
</script>
