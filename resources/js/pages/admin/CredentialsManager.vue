<template>
    <div>
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <p class="text-charcoal-light">Manage your professional credentials and certifications.</p>
            <button @click="openModal()" class="btn btn-primary">
                Add Credential
            </button>
        </div>

        <!-- Loading -->
        <div v-if="loading" class="flex items-center justify-center py-12">
            <div class="animate-spin w-8 h-8 border-2 border-mountain-blue border-t-transparent rounded-full"></div>
        </div>

        <!-- Credentials List -->
        <div v-else class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-charcoal-light uppercase tracking-wider w-16">Order</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-charcoal-light uppercase tracking-wider">Credential</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-charcoal-light uppercase tracking-wider">Institution</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-charcoal-light uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-charcoal-light uppercase tracking-wider w-32">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr
                        v-for="(credential, index) in credentials"
                        :key="credential.id"
                        class="hover:bg-gray-50 transition-colors"
                    >
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1">
                                <button
                                    v-if="index > 0"
                                    @click="moveCredential(index, -1)"
                                    class="p-1 hover:bg-gray-200 rounded transition-colors"
                                >
                                    <svg class="w-4 h-4 text-charcoal-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                    </svg>
                                </button>
                                <button
                                    v-if="index < credentials.length - 1"
                                    @click="moveCredential(index, 1)"
                                    class="p-1 hover:bg-gray-200 rounded transition-colors"
                                >
                                    <svg class="w-4 h-4 text-charcoal-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-medium text-charcoal">{{ credential.name }}</p>
                                <p v-if="credential.abbreviation" class="text-sm text-charcoal-light">{{ credential.abbreviation }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-charcoal-light">
                            {{ credential.institution || '—' }}
                        </td>
                        <td class="px-6 py-4">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                :class="credential.is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600'"
                            >
                                {{ credential.is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button
                                @click="openModal(credential)"
                                class="text-mountain-blue hover:text-mountain-blue-dark mr-3"
                            >
                                Edit
                            </button>
                            <button
                                @click="confirmDelete(credential)"
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
                        class="px-4 py-2 text-sm font-medium text-charcoal-light hover:text-charcoal border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
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

const toast = useToast();
const loading = ref(true);
const saving = ref(false);
const deleting = ref(false);
const credentials = ref([]);
const modalOpen = ref(false);
const deleteDialogOpen = ref(false);
const editingCredential = ref(null);
const deletingCredential = ref(null);

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

async function moveCredential(index, direction) {
    const newIndex = index + direction;
    const [credential] = credentials.value.splice(index, 1);
    credentials.value.splice(newIndex, 0, credential);

    try {
        await api.post('/admin/credentials/reorder', {
            ids: credentials.value.map(c => c.id),
        });
    } catch (e) {
        toast.error('Failed to update order');
        fetchCredentials();
    }
}

onMounted(() => {
    fetchCredentials();
});
</script>
