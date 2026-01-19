<template>
    <div>
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <p class="text-charcoal-light">Manage client testimonials displayed on your website.</p>
            <button @click="openModal()" class="btn btn-primary">
                Add Testimonial
            </button>
        </div>

        <!-- Loading -->
        <div v-if="loading" class="flex items-center justify-center py-12">
            <div class="animate-spin w-8 h-8 border-2 border-mountain-blue border-t-transparent rounded-full"></div>
        </div>

        <!-- Testimonials Grid -->
        <div v-else-if="testimonials.length" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div
                v-for="testimonial in testimonials"
                :key="testimonial.id"
                class="bg-white rounded-lg border border-gray-200 p-6"
            >
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-mountain-blue/10 rounded-full flex items-center justify-center">
                            <span class="text-mountain-blue font-medium">
                                {{ testimonial.client_name.charAt(0) }}
                            </span>
                        </div>
                        <div>
                            <p class="font-medium text-charcoal">{{ testimonial.client_name }}</p>
                            <p v-if="testimonial.client_title" class="text-sm text-charcoal-light">
                                {{ testimonial.client_title }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span
                            v-if="testimonial.is_featured"
                            class="text-xs px-2 py-1 bg-gold/10 text-gold rounded"
                        >
                            Featured
                        </span>
                        <span
                            class="text-xs px-2 py-1 rounded"
                            :class="testimonial.is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600'"
                        >
                            {{ testimonial.is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>

                <blockquote class="text-charcoal-light italic mb-4 line-clamp-4">
                    "{{ testimonial.quote }}"
                </blockquote>

                <div class="flex justify-end gap-2">
                    <button
                        @click="openModal(testimonial)"
                        class="text-sm text-mountain-blue hover:text-mountain-blue-dark"
                    >
                        Edit
                    </button>
                    <button
                        @click="confirmDelete(testimonial)"
                        class="text-sm text-terracotta hover:text-terracotta/80"
                    >
                        Delete
                    </button>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div v-else class="bg-white rounded-lg border border-gray-200 p-12 text-center">
            <p class="text-charcoal-light mb-4">No testimonials yet.</p>
            <button @click="openModal()" class="btn btn-primary">
                Add your first testimonial
            </button>
        </div>

        <!-- Edit Modal -->
        <Modal v-model="modalOpen" :title="editingTestimonial ? 'Edit Testimonial' : 'Add Testimonial'" size="lg">
            <form @submit.prevent="saveTestimonial" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="client_name" class="block text-sm font-medium text-charcoal mb-1">Client Name</label>
                        <input
                            id="client_name"
                            v-model="form.client_name"
                            type="text"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-mountain-blue focus:ring-1 focus:ring-mountain-blue outline-none"
                        />
                    </div>

                    <div>
                        <label for="client_title" class="block text-sm font-medium text-charcoal mb-1">Title (optional)</label>
                        <input
                            id="client_title"
                            v-model="form.client_title"
                            type="text"
                            placeholder="e.g., Type 1 Diabetic, Denver"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-mountain-blue focus:ring-1 focus:ring-mountain-blue outline-none"
                        />
                    </div>
                </div>

                <div>
                    <label for="quote" class="block text-sm font-medium text-charcoal mb-1">Quote</label>
                    <textarea
                        id="quote"
                        v-model="form.quote"
                        rows="4"
                        required
                        placeholder="What did they say about working with you?"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-mountain-blue focus:ring-1 focus:ring-mountain-blue outline-none resize-y"
                    ></textarea>
                </div>

                <div class="flex gap-6">
                    <label class="flex items-center gap-2">
                        <input
                            v-model="form.is_active"
                            type="checkbox"
                            class="w-4 h-4 border-gray-300 text-mountain-blue focus:ring-mountain-blue rounded"
                        />
                        <span class="text-sm text-charcoal">Active</span>
                    </label>

                    <label class="flex items-center gap-2">
                        <input
                            v-model="form.is_featured"
                            type="checkbox"
                            class="w-4 h-4 border-gray-300 text-mountain-blue focus:ring-mountain-blue rounded"
                        />
                        <span class="text-sm text-charcoal">Featured</span>
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
                        @click="saveTestimonial"
                        class="btn btn-primary"
                        :disabled="saving"
                    >
                        {{ saving ? 'Saving...' : 'Save Testimonial' }}
                    </button>
                </div>
            </template>
        </Modal>

        <!-- Delete Confirmation -->
        <ConfirmDialog
            v-model="deleteDialogOpen"
            title="Delete Testimonial"
            :message="`Are you sure you want to delete the testimonial from '${deletingTestimonial?.client_name}'?`"
            confirm-text="Delete"
            :loading="deleting"
            @confirm="deleteTestimonial"
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
const testimonials = ref([]);
const modalOpen = ref(false);
const deleteDialogOpen = ref(false);
const editingTestimonial = ref(null);
const deletingTestimonial = ref(null);

const form = ref({
    client_name: '',
    client_title: '',
    quote: '',
    is_active: true,
    is_featured: false,
});

function resetForm() {
    form.value = {
        client_name: '',
        client_title: '',
        quote: '',
        is_active: true,
        is_featured: false,
    };
}

function openModal(testimonial = null) {
    editingTestimonial.value = testimonial;
    if (testimonial) {
        form.value = { ...testimonial };
    } else {
        resetForm();
    }
    modalOpen.value = true;
}

function confirmDelete(testimonial) {
    deletingTestimonial.value = testimonial;
    deleteDialogOpen.value = true;
}

async function fetchTestimonials() {
    try {
        const response = await api.get('/admin/testimonials');
        testimonials.value = response.data;
    } catch (e) {
        toast.error('Failed to load testimonials');
    } finally {
        loading.value = false;
    }
}

async function saveTestimonial() {
    saving.value = true;
    try {
        if (editingTestimonial.value) {
            const response = await api.put(`/admin/testimonials/${editingTestimonial.value.id}`, form.value);
            const index = testimonials.value.findIndex(t => t.id === editingTestimonial.value.id);
            if (index > -1) testimonials.value[index] = response.data;
            toast.success('Testimonial updated');
        } else {
            const response = await api.post('/admin/testimonials', form.value);
            testimonials.value.push(response.data);
            toast.success('Testimonial created');
        }
        modalOpen.value = false;
    } catch (e) {
        toast.error('Failed to save testimonial');
    } finally {
        saving.value = false;
    }
}

async function deleteTestimonial() {
    deleting.value = true;
    try {
        await api.delete(`/admin/testimonials/${deletingTestimonial.value.id}`);
        testimonials.value = testimonials.value.filter(t => t.id !== deletingTestimonial.value.id);
        deleteDialogOpen.value = false;
        toast.success('Testimonial deleted');
    } catch (e) {
        toast.error('Failed to delete testimonial');
    } finally {
        deleting.value = false;
    }
}

onMounted(() => {
    fetchTestimonials();
});
</script>
