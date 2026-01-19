<template>
    <div>
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <p class="text-charcoal-light">Manage your blog posts and articles.</p>
            <router-link to="/admin/blog/new" class="btn btn-primary">
                New Post
            </router-link>
        </div>

        <!-- Loading -->
        <div v-if="loading" class="flex items-center justify-center py-12">
            <div class="animate-spin w-8 h-8 border-2 border-mountain-blue border-t-transparent rounded-full"></div>
        </div>

        <!-- Posts List -->
        <div v-else-if="posts.length" class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-charcoal-light uppercase tracking-wider">Post</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-charcoal-light uppercase tracking-wider w-32">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-charcoal-light uppercase tracking-wider w-40">Date</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-charcoal-light uppercase tracking-wider w-32">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr
                        v-for="post in posts"
                        :key="post.id"
                        class="hover:bg-gray-50 transition-colors"
                    >
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div
                                    v-if="post.featured_image"
                                    class="w-16 h-12 bg-gray-100 rounded overflow-hidden flex-shrink-0"
                                >
                                    <img :src="post.featured_image.url" :alt="post.title" class="w-full h-full object-cover" />
                                </div>
                                <div>
                                    <router-link
                                        :to="`/admin/blog/${post.id}`"
                                        class="font-medium text-charcoal hover:text-mountain-blue"
                                    >
                                        {{ post.title }}
                                    </router-link>
                                    <p class="text-sm text-charcoal-light truncate max-w-md">{{ post.excerpt || 'No excerpt' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                :class="statusClass(post)"
                            >
                                {{ statusLabel(post) }}
                            </span>
                            <span
                                v-if="post.is_featured"
                                class="ml-1 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gold/10 text-gold"
                            >
                                Featured
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-charcoal-light">
                            {{ formatDate(post.published_at || post.created_at) }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <router-link
                                :to="`/admin/blog/${post.id}`"
                                class="text-mountain-blue hover:text-mountain-blue-dark mr-3"
                            >
                                Edit
                            </router-link>
                            <button
                                @click="confirmDelete(post)"
                                class="text-terracotta hover:text-terracotta/80"
                            >
                                Delete
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Empty State -->
        <div v-else class="bg-white rounded-lg border border-gray-200 p-12 text-center">
            <p class="text-charcoal-light mb-4">No blog posts yet.</p>
            <router-link to="/admin/blog/new" class="btn btn-primary">
                Create your first post
            </router-link>
        </div>

        <!-- Delete Confirmation -->
        <ConfirmDialog
            v-model="deleteDialogOpen"
            title="Delete Post"
            :message="`Are you sure you want to delete '${deletingPost?.title}'? This action cannot be undone.`"
            confirm-text="Delete"
            :loading="deleting"
            @confirm="deletePost"
        />
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '@/services/api';
import { useToast } from '@/stores/toast';
import ConfirmDialog from '@/components/ui/ConfirmDialog.vue';

const toast = useToast();
const loading = ref(true);
const deleting = ref(false);
const posts = ref([]);
const deleteDialogOpen = ref(false);
const deletingPost = ref(null);

function statusClass(post) {
    if (!post.published_at) return 'bg-gray-100 text-gray-600';
    const isPublished = new Date(post.published_at) <= new Date();
    return isPublished ? 'bg-green-100 text-green-800' : 'bg-gold/10 text-gold';
}

function statusLabel(post) {
    if (!post.published_at) return 'Draft';
    const isPublished = new Date(post.published_at) <= new Date();
    return isPublished ? 'Published' : 'Scheduled';
}

function formatDate(date) {
    if (!date) return '—';
    return new Date(date).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
    });
}

function confirmDelete(post) {
    deletingPost.value = post;
    deleteDialogOpen.value = true;
}

async function fetchPosts() {
    try {
        const response = await api.get('/admin/blog-posts');
        posts.value = response.data;
    } catch (e) {
        toast.error('Failed to load posts');
    } finally {
        loading.value = false;
    }
}

async function deletePost() {
    deleting.value = true;
    try {
        await api.delete(`/admin/blog-posts/${deletingPost.value.id}`);
        posts.value = posts.value.filter(p => p.id !== deletingPost.value.id);
        deleteDialogOpen.value = false;
        toast.success('Post deleted');
    } catch (e) {
        toast.error('Failed to delete post');
    } finally {
        deleting.value = false;
    }
}

onMounted(() => {
    fetchPosts();
});
</script>
