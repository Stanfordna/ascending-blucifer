<template>
    <div>
        <div v-if="loading" class="flex items-center justify-center py-12">
            <div class="animate-spin w-8 h-8 border-2 border-mountain-blue border-t-transparent rounded-full"></div>
        </div>

        <div v-else class="flex gap-8">
            <!-- Left: Page Layout Thumbnails -->
            <div class="w-64 flex-shrink-0">
                <h3 class="text-xs font-medium text-charcoal-light uppercase tracking-wide mb-3">Page Layout</h3>
                <SectionThumbnails
                    :sections="allSections"
                    :active-section="activeSection"
                    @select="activeSection = $event"
                />
            </div>

            <!-- Right: Edit Form -->
            <div class="flex-1 min-w-0">
                <form @submit.prevent="saveBlocks" class="space-y-6">
                    <!-- Section Header -->
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-charcoal">
                            {{ sectionDisplayNames[activeSection] || activeSection }}
                        </h2>
                        <button
                            type="submit"
                            class="btn btn-primary"
                            :disabled="saving"
                        >
                            <span v-if="saving">Saving...</span>
                            <span v-else>Save Changes</span>
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

                            <div v-if="getSection(activeSection).length === 0" class="text-center py-8 text-charcoal-light">
                                No editable text fields for this section.
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import api from '@/services/api';
import { useToast } from '@/stores/toast';
import SectionThumbnails from '@/components/admin/SectionThumbnails.vue';

const toast = useToast();
const loading = ref(true);
const saving = ref(false);
const blocks = ref([]);
const activeSection = ref('welcome');

const sectionDisplayNames = {
    welcome: 'Welcome Banner',
    services: 'Services',
    about: 'About',
    blog: 'Blog',
    contact: 'Contact Prompt',
    footer: 'Footer',
};

const sectionOrder = ['welcome', 'services', 'about', 'blog', 'contact', 'footer'];

const editableSections = computed(() => {
    const uniqueSections = [...new Set(blocks.value.map(b => b.section))];
    return sectionOrder.filter(s => uniqueSections.includes(s));
});

const allSections = computed(() => {
    const sections = [];

    for (const key of sectionOrder) {
        const isEditable = editableSections.value.includes(key);

        if (key === 'services') {
            sections.push({
                key: 'services',
                label: 'Services',
                editable: false,
                link: '/admin/services',
            });
        } else if (key === 'blog') {
            sections.push({
                key: 'blog',
                label: 'Blog',
                editable: false,
                link: '/admin/blog',
            });
        } else if (isEditable) {
            sections.push({
                key,
                label: sectionDisplayNames[key] || key,
                editable: true,
            });
        }
    }

    return sections;
});

function getSection(section) {
    return blocks.value.filter(b => b.section === section);
}

async function fetchBlocks() {
    try {
        const response = await api.get('/admin/content-blocks');
        const grouped = response.data;
        blocks.value = Object.values(grouped).flat();
        if (editableSections.value.length > 0 && !editableSections.value.includes(activeSection.value)) {
            activeSection.value = editableSections.value[0];
        }
    } catch (e) {
        toast.error('Failed to load section text');
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
        toast.success('Section text saved successfully');
    } catch (e) {
        toast.error('Failed to save section text');
    } finally {
        saving.value = false;
    }
}

onMounted(() => {
    fetchBlocks();
});
</script>
