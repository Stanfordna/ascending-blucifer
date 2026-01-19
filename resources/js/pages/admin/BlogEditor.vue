<template>
    <div class="max-w-4xl">
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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
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
                    <div class="bg-white rounded-lg border border-gray-200 p-6">
                        <label class="block text-sm font-medium text-charcoal mb-2">Content</label>
                        <textarea
                            v-model="form.content"
                            rows="20"
                            placeholder="Write your post content here..."
                            class="w-full px-0 py-0 border-0 focus:ring-0 outline-none resize-y"
                        ></textarea>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
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
                        </div>
                    </div>

                    <!-- SEO -->
                    <div class="bg-white rounded-lg border border-gray-200 p-6">
                        <h3 class="font-medium text-charcoal mb-4">SEO</h3>

                        <div class="space-y-4">
                            <div>
                                <label for="meta_title" class="block text-sm font-medium text-charcoal mb-1">Meta Title</label>
                                <input
                                    id="meta_title"
                                    v-model="form.meta_title"
                                    type="text"
                                    placeholder="Page title for search engines"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-mountain-blue focus:ring-1 focus:ring-mountain-blue outline-none text-sm"
                                />
                            </div>

                            <div>
                                <label for="meta_description" class="block text-sm font-medium text-charcoal mb-1">Meta Description</label>
                                <textarea
                                    id="meta_description"
                                    v-model="form.meta_description"
                                    rows="3"
                                    placeholder="Brief description for search results"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-mountain-blue focus:ring-1 focus:ring-mountain-blue outline-none text-sm resize-none"
                                ></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between bg-white rounded-lg border border-gray-200 p-4">
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
        </form>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import api from '@/services/api';
import { useToast } from '@/stores/toast';

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
    if (asDraft) {
        data.published_at = null;
    } else if (!data.published_at) {
        data.published_at = new Date().toISOString();
    }

    try {
        if (isEditing.value) {
            await api.put(`/admin/blog-posts/${route.params.id}`, data);
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
