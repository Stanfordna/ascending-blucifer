<template>
    <div class="max-w-6xl relative pb-20">
        <!-- Loading -->
        <div v-if="loading" class="flex items-center justify-center py-12">
            <div class="animate-spin w-8 h-8 border-2 border-mountain-blue border-t-transparent rounded-full"></div>
        </div>

        <form v-else @submit.prevent="savePost" class="space-y-6">
            <!-- Title -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <input
                    v-model="form.title"
                    type="text"
                    placeholder="Post title"
                    required
                    class="w-full text-2xl font-serif text-charcoal border-0 focus:ring-0 outline-none p-0"
                />
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-3 space-y-6">
                    <!-- Excerpt -->
                    <div class="bg-white rounded-lg border border-gray-200 p-6">
                        <label class="block text-sm font-medium text-charcoal mb-2">Excerpt</label>
                        <textarea
                            v-model="form.excerpt"
                            rows="2"
                            placeholder="Brief summary of the post..."
                            class="w-full px-0 py-0 border-0 focus:ring-0 outline-none resize-none text-charcoal-light"
                        ></textarea>
                    </div>

                    <!-- Content -->
                    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                        <div class="flex items-center justify-between px-4 py-2 border-b border-gray-200">
                            <label class="text-sm font-medium text-charcoal">Content</label>
                            <button
                                type="button"
                                @click="openExpandedEditor"
                                class="btn btn-secondary"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                                </svg>
                                Full Screen Editor
                            </button>
                        </div>
                        <MdEditor
                            v-model="form.content"
                            language="en-US"
                            :toolbars="editorToolbars"
                            :preview="false"
                            :footers="[]"
                            placeholder="Write your post content here..."
                            :style="{ height: '500px' }"
                            no-upload-img
                        />
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Publish Settings -->
                    <div class="bg-white rounded-lg border border-gray-200 p-6">
                        <h3 class="font-medium text-charcoal mb-4">Publish Settings</h3>

                        <div class="space-y-4">
                            <div>
                                <label for="slug" class="block text-sm font-medium text-charcoal mb-1">Slug</label>
                                <input
                                    id="slug"
                                    v-model="form.slug"
                                    type="text"
                                    placeholder="post-url-slug"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-mountain-blue focus:ring-1 focus:ring-mountain-blue outline-none text-sm"
                                />
                            </div>

                            <div>
                                <label for="published_at" class="block text-sm font-medium text-charcoal mb-1">Publish Date</label>
                                <input
                                    id="published_at"
                                    v-model="form.published_at"
                                    type="datetime-local"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-mountain-blue focus:ring-1 focus:ring-mountain-blue outline-none text-sm"
                                />
                                <p class="text-xs text-charcoal-light mt-1">Leave empty to save as draft</p>
                            </div>

                            <div>
                                <label class="flex items-center gap-2">
                                    <input
                                        v-model="form.is_featured"
                                        type="checkbox"
                                        class="w-4 h-4 border-gray-300 text-mountain-blue focus:ring-mountain-blue rounded"
                                    />
                                    <span class="text-sm text-charcoal">Featured post</span>
                                </label>
                            </div>

                            <!-- View published post link -->
                            <div v-if="isEditing && form.slug && form.published_at">
                                <a
                                    :href="`/blog/${form.slug}`"
                                    target="_blank"
                                    class="inline-flex items-center gap-1 text-sm text-mountain-blue hover:text-mountain-blue-dark"
                                >
                                    View post
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- SEO -->
                    <div class="bg-white rounded-lg border border-gray-200 p-6 flex-1">
                        <h3 class="font-medium text-charcoal mb-4">SEO</h3>

                        <div class="space-y-4">
                            <div>
                                <div class="flex items-center gap-1 mb-1">
                                    <label for="meta_title" class="block text-sm font-medium text-charcoal">Meta Title</label>
                                    <InfoTip title="Writing Great Meta Titles">
                                        <p>Keep it under 60 characters so it doesn't get cut off in search results.</p>
                                        <p class="mt-2"><strong>Good example:</strong><br>"Mindful Eating Tips for Diabetes | Maggie Chamberlain"</p>
                                        <p class="mt-2"><strong>Include:</strong> Primary keyword + your name/brand</p>
                                    </InfoTip>
                                </div>
                                <input
                                    id="meta_title"
                                    v-model="form.meta_title"
                                    type="text"
                                    placeholder="Page title for search engines"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-mountain-blue focus:ring-1 focus:ring-mountain-blue outline-none text-sm"
                                />
                                <p class="text-xs mt-1" :class="(form.meta_title?.length || 0) > 60 ? 'text-terracotta' : 'text-charcoal-light'">
                                    {{ form.meta_title?.length || 0 }}/60 characters
                                </p>
                            </div>

                            <div>
                                <div class="flex items-center gap-1 mb-1">
                                    <label for="meta_description" class="block text-sm font-medium text-charcoal">Meta Description</label>
                                    <InfoTip title="Writing Great Meta Descriptions">
                                        <p>This appears below the title in search results. Make it compelling!</p>
                                        <p class="mt-2"><strong>Good example:</strong><br>"Learn practical mindful eating strategies to manage blood sugar and enjoy food again. Expert tips from a Denver diabetes educator."</p>
                                        <p class="mt-2"><strong>Include:</strong> What the reader will learn + why it matters + a call to action</p>
                                    </InfoTip>
                                </div>
                                <textarea
                                    id="meta_description"
                                    v-model="form.meta_description"
                                    rows="4"
                                    placeholder="Brief description for search results"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-mountain-blue focus:ring-1 focus:ring-mountain-blue outline-none text-sm resize-none"
                                ></textarea>
                                <p class="text-xs mt-1" :class="(form.meta_description?.length || 0) > 160 ? 'text-terracotta' : 'text-charcoal-light'">
                                    {{ form.meta_description?.length || 0 }}/160 characters
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Color Overrides -->
                    <div class="bg-white rounded-lg border border-gray-200 p-6">
                        <h3 class="font-medium text-charcoal mb-2">Color Overrides</h3>
                        <p class="text-xs text-charcoal-light mb-4">Override site brand colors for this post. Leave blank to use defaults.</p>
                        <div class="space-y-3">
                            <ColorPicker v-model="form.color_primary" label="Primary" />
                            <ColorPicker v-model="form.color_secondary" label="Secondary" />
                            <ColorPicker v-model="form.color_accent" label="Accent" />
                        </div>
                        <button
                            v-if="form.color_primary || form.color_secondary || form.color_accent"
                            type="button"
                            @click="form.color_primary = ''; form.color_secondary = ''; form.color_accent = '';"
                            class="text-sm text-charcoal-light hover:text-charcoal mt-3"
                        >
                            Clear all overrides
                        </button>
                    </div>
                </div>
            </div>

            <!-- Actions - Fixed at bottom -->
            <div class="fixed bottom-0 left-0 right-0 lg:left-64 bg-white border-t border-gray-200 p-4 z-10">
                <div class="max-w-6xl mx-auto flex items-center justify-between">
                    <router-link to="/admin/blog" class="text-charcoal-light hover:text-charcoal transition-colors">
                        &larr; Back to posts
                    </router-link>
                    <div class="flex gap-3">
                        <button
                            type="button"
                            @click="savePost(true)"
                            class="btn btn-outline"
                            :disabled="saving"
                        >
                            Save as Draft
                        </button>
                        <button
                            type="submit"
                            class="btn btn-primary"
                            :disabled="saving"
                        >
                            {{ saving ? 'Saving...' : (isEditing ? 'Update Post' : 'Publish Post') }}
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <!-- Fullscreen Editor Modal -->
        <Teleport to="body">
            <Transition name="modal-fade">
                <div
                    v-if="editorExpanded"
                    class="fixed inset-0 z-50 flex items-center justify-center"
                >
                    <div class="absolute inset-0 bg-black/60" @click="editorExpanded = false"></div>

                    <div
                        class="relative bg-white rounded-lg shadow-2xl flex flex-col overflow-hidden"
                        :style="{ width: editorWidth + 'px', height: editorHeight + 'px' }"
                    >
                        <!-- Header -->
                        <div class="flex items-center justify-between px-5 py-3 border-b border-gray-200 bg-gray-50 shrink-0">
                            <h3 class="font-medium text-charcoal truncate">{{ form.title || 'Post Content' }}</h3>
                            <button
                                type="button"
                                @click="editorExpanded = false"
                                class="p-1.5 hover:bg-gray-200 rounded transition-colors cursor-pointer"
                            >
                                <svg class="w-5 h-5 text-charcoal-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- Editor -->
                        <div class="flex-1 min-h-0">
                            <MdEditor
                                v-model="form.content"
                                language="en-US"
                                :toolbars="editorToolbars"
                                :preview="true"
                                :footers="[]"
                                placeholder="Write your post content here..."
                                :style="{ height: modalEditorHeight }"
                                no-upload-img
                            />
                        </div>

                        <!-- Resize handles — edges (invisible) -->
                        <div @mousedown="startResize($event, 'n')" class="absolute top-0 left-3 right-3 h-1.5 cursor-ns-resize z-10"></div>
                        <div @mousedown="startResize($event, 's')" class="absolute bottom-0 left-3 right-3 h-1.5 cursor-ns-resize z-10"></div>
                        <div @mousedown="startResize($event, 'w')" class="absolute left-0 top-3 bottom-3 w-1.5 cursor-ew-resize z-10"></div>
                        <div @mousedown="startResize($event, 'e')" class="absolute right-0 top-3 bottom-3 w-1.5 cursor-ew-resize z-10"></div>

                        <!-- Resize handles — corners (invisible) -->
                        <div @mousedown="startResize($event, 'nw')" class="absolute top-0 left-0 w-3 h-3 cursor-nwse-resize z-20"></div>
                        <div @mousedown="startResize($event, 'ne')" class="absolute top-0 right-0 w-3 h-3 cursor-nesw-resize z-20"></div>
                        <div @mousedown="startResize($event, 'sw')" class="absolute bottom-0 left-0 w-3 h-3 cursor-nesw-resize z-20"></div>

                        <!-- Bottom-right corner — visible handle -->
                        <div @mousedown="startResize($event, 'se')" class="absolute bottom-0 right-0 w-5 h-5 cursor-nwse-resize z-20 flex items-end justify-end pr-1 pb-1">
                            <svg class="w-3 h-3 text-gray-400" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.5">
                                <line x1="11" y1="3" x2="3" y2="11" />
                                <line x1="11" y1="7" x2="7" y2="11" />
                                <line x1="11" y1="11" x2="10" y2="11" />
                            </svg>
                        </div>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </div>
</template>

<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { MdEditor } from 'md-editor-v3';
import 'md-editor-v3/lib/style.css';
import api from '@/services/api';
import { useToast } from '@/stores/toast';
import InfoTip from '@/components/ui/InfoTip.vue';
import ColorPicker from '@/components/admin/ColorPicker.vue';

const editorToolbars = [
    'bold', 'italic', 'strikeThrough', '-',
    'title', 'quote', 'unorderedList', 'orderedList', '-',
    'link', 'image', 'table', '-',
    'revoke', 'next', '=',
    'preview',
];

const route = useRoute();
const router = useRouter();
const toast = useToast();

const loading = ref(false);
const saving = ref(false);

// Fullscreen editor
const editorExpanded = ref(false);
const editorWidth = ref(0);
const editorHeight = ref(0);
const modalEditorHeight = computed(() => `${editorHeight.value - 52}px`);

let resizeState = null;

function openExpandedEditor() {
    editorWidth.value = Math.min(window.innerWidth * 0.85, 1400);
    editorHeight.value = Math.min(window.innerHeight * 0.85, 900);
    editorExpanded.value = true;
}

watch(editorExpanded, (open) => {
    document.body.style.overflow = open ? 'hidden' : '';
});

const RESIZE_CURSORS = { n: 'ns-resize', s: 'ns-resize', e: 'ew-resize', w: 'ew-resize', nw: 'nwse-resize', ne: 'nesw-resize', sw: 'nesw-resize', se: 'nwse-resize' };

function startResize(e, direction) {
    e.preventDefault();
    resizeState = {
        startX: e.clientX,
        startY: e.clientY,
        startWidth: editorWidth.value,
        startHeight: editorHeight.value,
        direction,
    };
    document.addEventListener('mousemove', onResize);
    document.addEventListener('mouseup', stopResize);
    document.body.style.userSelect = 'none';
    document.body.style.cursor = RESIZE_CURSORS[direction];
}

function onResize(e) {
    if (!resizeState) return;
    const dx = e.clientX - resizeState.startX;
    const dy = e.clientY - resizeState.startY;
    const dir = resizeState.direction;
    const minW = 600, minH = 400;
    const maxW = window.innerWidth - 40;
    const maxH = window.innerHeight - 40;

    let newW = resizeState.startWidth;
    let newH = resizeState.startHeight;

    // Symmetric: each pixel of edge drag = 2px of size change (center stays fixed)
    if (dir.includes('e')) newW = resizeState.startWidth + dx * 2;
    if (dir.includes('w')) newW = resizeState.startWidth - dx * 2;
    if (dir.includes('s')) newH = resizeState.startHeight + dy * 2;
    if (dir.includes('n')) newH = resizeState.startHeight - dy * 2;

    editorWidth.value = Math.max(minW, Math.min(maxW, newW));
    editorHeight.value = Math.max(minH, Math.min(maxH, newH));
}

function stopResize() {
    resizeState = null;
    document.removeEventListener('mousemove', onResize);
    document.removeEventListener('mouseup', stopResize);
    document.body.style.userSelect = '';
    document.body.style.cursor = '';
}

const form = ref({
    title: '',
    slug: '',
    excerpt: '',
    content: '',
    published_at: '',
    is_featured: false,
    meta_title: '',
    meta_description: '',
    color_primary: '',
    color_secondary: '',
    color_accent: '',
});

const isEditing = computed(() => !!route.params.id);

async function fetchPost() {
    if (!route.params.id) return;

    loading.value = true;
    try {
        const response = await api.get(`/admin/blog-posts/${route.params.id}`);
        const post = response.data;
        form.value = {
            title: post.title,
            slug: post.slug,
            excerpt: post.excerpt || '',
            content: post.content || '',
            published_at: post.published_at ? formatDateForInput(post.published_at) : '',
            is_featured: post.is_featured,
            meta_title: post.meta_title || '',
            meta_description: post.meta_description || '',
            color_primary: post.color_primary || '',
            color_secondary: post.color_secondary || '',
            color_accent: post.color_accent || '',
        };
    } catch (e) {
        toast.error('Failed to load post');
        router.push('/admin/blog');
    } finally {
        loading.value = false;
    }
}

function formatDateForInput(date) {
    const d = new Date(date);
    return d.toISOString().slice(0, 16);
}

async function savePost(asDraft = false) {
    saving.value = true;

    const data = { ...form.value };

    // Handle publish date
    if (asDraft) {
        data.published_at = null;
    } else if (!data.published_at) {
        // Set to now if publishing without a date
        data.published_at = new Date().toISOString();
    } else {
        // Convert datetime-local format to ISO string for Laravel
        data.published_at = new Date(data.published_at).toISOString();
    }

    try {
        if (isEditing.value) {
            const response = await api.put(`/admin/blog-posts/${route.params.id}`, data);
            // Update form with server response to ensure consistency
            const post = response.data;
            form.value = {
                title: post.title,
                slug: post.slug,
                excerpt: post.excerpt || '',
                content: post.content || '',
                published_at: post.published_at ? formatDateForInput(post.published_at) : '',
                is_featured: post.is_featured,
                meta_title: post.meta_title || '',
                meta_description: post.meta_description || '',
                color_primary: post.color_primary || '',
                color_secondary: post.color_secondary || '',
                color_accent: post.color_accent || '',
            };
            toast.success('Post updated');
        } else {
            const response = await api.post('/admin/blog-posts', data);
            toast.success('Post created');
            router.push(`/admin/blog/${response.data.id}`);
        }
    } catch (e) {
        const message = e.response?.data?.message || 'Failed to save post';
        toast.error(message);
    } finally {
        saving.value = false;
    }
}

onMounted(() => {
    fetchPost();
});

onUnmounted(() => {
    stopResize();
    document.body.style.overflow = '';
});
</script>

<style scoped>
.modal-fade-enter-active,
.modal-fade-leave-active {
    transition: opacity 0.2s ease;
}
.modal-fade-enter-from,
.modal-fade-leave-to {
    opacity: 0;
}
</style>
