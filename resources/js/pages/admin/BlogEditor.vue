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
                            class="px-4 py-2 text-sm font-medium text-charcoal-light hover:text-charcoal border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
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
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
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
</script>
