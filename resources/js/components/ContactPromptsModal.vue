<template>
    <Teleport to="body">
        <Transition name="modal">
            <div
                v-if="modelValue"
                class="fixed inset-0 z-[100] flex items-center justify-center p-4"
            >
                <!-- Backdrop -->
                <div
                    class="absolute inset-0 bg-charcoal/50 backdrop-blur-sm"
                    @click="close"
                ></div>

                <!-- Modal -->
                <div class="relative bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-hidden">
                    <!-- Header -->
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-charcoal">Edit Contact Prompts</h2>
                        <button
                            @click="close"
                            class="text-charcoal-light hover:text-charcoal transition-colors"
                        >
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Tabs -->
                    <div class="flex border-b border-gray-200">
                        <button
                            v-for="tab in tabs"
                            :key="tab.key"
                            type="button"
                            @click="activeTab = tab.key"
                            class="px-6 py-3 text-sm font-medium transition-colors border-b-2 -mb-px"
                            :class="activeTab === tab.key
                                ? 'border-mountain-blue text-mountain-blue'
                                : 'border-transparent text-charcoal-light hover:text-charcoal hover:border-gray-300'"
                        >
                            {{ tab.label }}
                        </button>
                    </div>

                    <!-- Content -->
                    <div class="px-6 py-4 overflow-y-auto max-h-[60vh]">
                        <div v-if="loading" class="flex items-center justify-center py-8">
                            <div class="animate-spin w-8 h-8 border-2 border-mountain-blue border-t-transparent rounded-full"></div>
                        </div>

                        <form v-else @submit.prevent="save" class="space-y-4">
                            <div v-for="block in activeBlocks" :key="block.key">
                                <label :for="block.key" class="block text-sm font-medium text-charcoal mb-1">
                                    {{ block.label }}
                                </label>
                                <textarea
                                    v-if="block.type === 'textarea'"
                                    :id="block.key"
                                    v-model="block.content"
                                    rows="3"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-mountain-blue focus:ring-1 focus:ring-mountain-blue outline-none resize-y"
                                ></textarea>
                                <input
                                    v-else
                                    :id="block.key"
                                    v-model="block.content"
                                    type="text"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-mountain-blue focus:ring-1 focus:ring-mountain-blue outline-none"
                                />
                            </div>
                        </form>
                    </div>

                    <!-- Footer -->
                    <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-200 bg-gray-50">
                        <button
                            type="button"
                            @click="close"
                            class="px-4 py-2 text-sm font-medium text-charcoal-light hover:text-charcoal transition-colors"
                        >
                            Cancel
                        </button>
                        <button
                            type="button"
                            @click="save"
                            class="btn btn-primary"
                            :disabled="saving"
                        >
                            <span v-if="saving">Saving...</span>
                            <span v-else>Save Changes</span>
                        </button>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import api from '@/services/api';
import { useToast } from '@/stores/toast';
import { useContentStore } from '@/stores/content';

const props = defineProps({
    modelValue: {
        type: Boolean,
        default: false,
    },
    initialTab: {
        type: String,
        default: 'contact',
    },
});

const emit = defineEmits(['update:modelValue', 'saved']);

const toast = useToast();
const contentStore = useContentStore();
const loading = ref(false);
const saving = ref(false);
const allBlocks = ref({});
const activeTab = ref(props.initialTab);

const tabs = [
    { key: 'contact', label: 'Home Page' },
    { key: 'blog_cta', label: 'Blog Posts' },
    { key: 'service_cta', label: 'Services' },
];

const activeBlocks = computed(() => {
    return allBlocks.value[activeTab.value] || [];
});

watch(() => props.modelValue, async (isOpen) => {
    if (isOpen) {
        activeTab.value = props.initialTab;
        await fetchBlocks();
    }
});

watch(() => props.initialTab, (val) => {
    activeTab.value = val;
});

async function fetchBlocks() {
    loading.value = true;
    try {
        const response = await api.get('/admin/content-blocks');
        allBlocks.value = response.data;
    } catch (e) {
        toast.error('Failed to load content');
        close();
    } finally {
        loading.value = false;
    }
}

async function save() {
    saving.value = true;
    try {
        const blocks = activeBlocks.value;
        await api.put('/admin/content-blocks', {
            blocks: blocks.map(b => ({
                key: b.key,
                content: b.content,
            })),
        });
        toast.success('Content saved successfully');
        contentStore.refresh();
        emit('saved');
    } catch (e) {
        toast.error('Failed to save content');
    } finally {
        saving.value = false;
    }
}

function close() {
    emit('update:modelValue', false);
}
</script>

<style scoped>
.modal-enter-active,
.modal-leave-active {
    transition: opacity 0.2s ease;
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
}

.modal-enter-active .relative,
.modal-leave-active .relative {
    transition: transform 0.2s ease;
}

.modal-enter-from .relative,
.modal-leave-to .relative {
    transform: scale(0.95);
}
</style>
