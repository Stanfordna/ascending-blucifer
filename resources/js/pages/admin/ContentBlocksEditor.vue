<template>
    <div class="max-w-4xl">
        <div v-if="loading" class="flex items-center justify-center py-12">
            <div class="animate-spin w-8 h-8 border-2 border-mountain-blue border-t-transparent rounded-full"></div>
        </div>

        <form v-else @submit.prevent="saveBlocks" class="space-y-8">
            <!-- Section Tabs -->
            <div class="flex gap-2 border-b border-gray-200">
                <button
                    v-for="section in sections"
                    :key="section"
                    type="button"
                    @click="activeSection = section"
                    class="px-4 py-2 text-sm font-medium transition-colors border-b-2 -mb-px"
                    :class="activeSection === section
                        ? 'border-mountain-blue text-mountain-blue'
                        : 'border-transparent text-charcoal-light hover:text-charcoal'"
                >
                    {{ formatSectionName(section) }}
                </button>
            </div>

            <!-- Content Blocks for Active Section -->
            <div class="bg-white rounded-lg border border-gray-200">
                <div class="p-6 space-y-6">
                    <div v-for="block in getSection(activeSection)" :key="block.key">
                        <label :for="block.key" class="block text-sm font-medium text-charcoal mb-1">
                            {{ block.label }}
                        </label>
                        <textarea
                            v-if="block.type === 'textarea'"
                            :id="block.key"
                            v-model="block.content"
                            rows="4"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-mountain-blue focus:ring-1 focus:ring-mountain-blue outline-none resize-y"
                        ></textarea>
                        <input
                            v-else
                            :id="block.key"
                            v-model="block.content"
                            type="text"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-mountain-blue focus:ring-1 focus:ring-mountain-blue outline-none"
                        />
                        <p class="mt-1 text-xs text-charcoal-light">Key: {{ block.key }}</p>
                    </div>
                </div>
            </div>

            <!-- Save Button -->
            <div class="flex justify-end">
                <button
                    type="submit"
                    class="btn btn-primary"
                    :disabled="saving"
                >
                    <span v-if="saving">Saving...</span>
                    <span v-else>Save Content</span>
                </button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import api from '@/services/api';
import { useToast } from '@/stores/toast';

const toast = useToast();
const loading = ref(true);
const saving = ref(false);
const blocks = ref([]);
const activeSection = ref('hero');

const sections = computed(() => {
    return [...new Set(blocks.value.map(b => b.section))];
});

function getSection(section) {
    return blocks.value.filter(b => b.section === section);
}

function formatSectionName(section) {
    return section.charAt(0).toUpperCase() + section.slice(1);
}

async function fetchBlocks() {
    try {
        const response = await api.get('/admin/content-blocks');
        // Flatten grouped response into array
        const grouped = response.data;
        blocks.value = Object.values(grouped).flat();
        if (sections.value.length > 0 && !sections.value.includes(activeSection.value)) {
            activeSection.value = sections.value[0];
        }
    } catch (e) {
        toast.error('Failed to load content blocks');
    } finally {
        loading.value = false;
    }
}

async function saveBlocks() {
    saving.value = true;
    try {
        await api.put('/admin/content-blocks', {
            blocks: blocks.value.map(b => ({
                key: b.key,
                content: b.content,
            })),
        });
        toast.success('Content saved successfully');
    } catch (e) {
        toast.error('Failed to save content');
    } finally {
        saving.value = false;
    }
}

onMounted(() => {
    fetchBlocks();
});
</script>
