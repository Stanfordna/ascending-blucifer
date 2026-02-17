<template>
    <div>
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <p class="text-charcoal-light">Manage your blog posts and articles.</p>
            <div class="flex items-center gap-3">
                <a
                    href="/blog"
                    target="_blank"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-charcoal-light border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-charcoal transition-colors"
                >
                    View Blog
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                </a>
                <button
                    @click="showSeoTips = !showSeoTips"
                    class="flex items-center gap-2 px-3 py-2 text-sm font-medium rounded-lg border transition-colors"
                    :class="showSeoTips ? 'bg-mountain-blue/10 border-mountain-blue text-mountain-blue' : 'border-gray-300 text-charcoal-light hover:text-charcoal hover:bg-gray-50'"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                    SEO Tips
                </button>
                <router-link to="/admin/blog/new" class="btn btn-primary">
                    New Post
                </router-link>
            </div>
        </div>

        <!-- SEO Tips Panel -->
        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0 -translate-y-2"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100 translate-y-0"
            leave-to-class="opacity-0 -translate-y-2"
        >
            <div v-if="showSeoTips" class="mb-6 bg-mountain-blue/5 border border-mountain-blue/20 rounded-lg p-6">
                <h3 class="font-semibold text-charcoal mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-mountain-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                    SEO Best Practices for Blog Posts
                </h3>
                <div class="grid md:grid-cols-2 gap-6 text-sm">
                    <div>
                        <h4 class="font-medium text-charcoal mb-2">Content Tips</h4>
                        <ul class="space-y-2 text-charcoal-light">
                            <li class="flex items-start gap-2">
                                <span class="text-mountain-blue mt-1">•</span>
                                <span><strong>Publish regularly</strong> – Even 1-2 posts per month signals an active site to Google</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-mountain-blue mt-1">•</span>
                                <span><strong>Update old posts</strong> – Refresh outdated content with new info to boost rankings</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-mountain-blue mt-1">•</span>
                                <span><strong>Use keywords naturally</strong> – Include terms people search for in titles and content</span>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-medium text-charcoal mb-2">Technical Tips</h4>
                        <ul class="space-y-2 text-charcoal-light">
                            <li class="flex items-start gap-2">
                                <span class="text-mountain-blue mt-1">•</span>
                                <span><strong>Fill out SEO fields</strong> – Meta title and description appear in search results</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-mountain-blue mt-1">•</span>
                                <span><strong>Use descriptive slugs</strong> – "mindful-eating-diabetes" beats "post-123"</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-mountain-blue mt-1">•</span>
                                <span><strong>Feature your best posts</strong> – Featured posts appear prominently on the homepage</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </Transition>

        <!-- Loading -->
        <div v-if="loading" class="flex items-center justify-center py-12">
            <div class="animate-spin w-8 h-8 border-2 border-mountain-blue border-t-transparent rounded-full"></div>
        </div>

        <template v-else>
            <!-- Featured Posts Section -->
            <div v-if="featuredPosts.length" class="mb-8">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-charcoal flex items-center gap-2">
                        <svg class="w-5 h-5 text-gold" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        Featured Posts
                        <span class="text-sm font-normal text-charcoal-light">({{ featuredPosts.length }} in carousel)</span>
                    </h2>
                    <span class="text-xs text-charcoal-light">Drag to reorder</span>
                </div>

                <div
                    class="bg-white rounded-lg border border-gray-200 divide-y divide-gray-100"
                    @dragover.prevent
                    @drop="handleDrop"
                >
                    <div
                        v-for="(post, index) in featuredPosts"
                        :key="post.id"
                        :draggable="true"
                        @dragstart="handleDragStart($event, index)"
                        @dragover.prevent="handleDragOver($event, index)"
                        @dragend="handleDragEnd"
                        class="flex items-center gap-4 px-4 py-3 cursor-move hover:bg-gray-50 transition-colors"
                        :class="{ 'bg-mountain-blue/5': dragOverIndex === index }"
                    >
                        <!-- Drag handle -->
                        <div class="text-gray-400">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M7 2a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 2zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 8zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 14zm6-8a2 2 0 1 0-.001-4.001A2 2 0 0 0 13 6zm0 2a2 2 0 1 0 .001 4.001A2 2 0 0 0 13 8zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 13 14z"/>
                            </svg>
                        </div>

                        <!-- Order number -->
                        <span class="w-6 h-6 rounded-full bg-gold/20 text-gold text-xs font-semibold flex items-center justify-center">
                            {{ index + 1 }}
                        </span>

                        <!-- Post info -->
                        <div class="flex-1 min-w-0">
                            <router-link
                                :to="`/admin/blog/${post.id}`"
                                class="font-medium text-charcoal hover:text-mountain-blue truncate block"
                            >
                                {{ post.title }}
                            </router-link>
                        </div>

                        <!-- Status -->
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                            :class="statusClass(post)"
                        >
                            {{ statusLabel(post) }}
                        </span>

                        <!-- Actions -->
                        <div class="flex items-center gap-1">
                            <!-- Unfeature -->
                            <button
                                @click="toggleFeatured(post)"
                                class="p-2 rounded hover:bg-gray-100 transition-colors"
                                title="Remove from featured"
                            >
                                <svg class="w-5 h-5 text-gold" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                </svg>
                            </button>

                            <!-- View -->
                            <a
                                v-if="post.slug && post.published_at"
                                :href="`/blog/${post.slug}`"
                                target="_blank"
                                class="p-2 rounded hover:bg-gray-100 text-charcoal-light hover:text-mountain-blue transition-colors"
                                title="View post"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- All Posts Section -->
            <div>
                <h2 class="text-lg font-semibold text-charcoal mb-4">All Posts</h2>

                <div v-if="posts.length" class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-charcoal-light uppercase tracking-wider">Post</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-charcoal-light uppercase tracking-wider w-32">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-charcoal-light uppercase tracking-wider w-40">Date</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-charcoal-light uppercase tracking-wider w-40">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr
                                v-for="post in nonFeaturedPosts"
                                :key="post.id"
                                class="hover:bg-gray-50 transition-colors"
                            >
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <!-- Feature star toggle -->
                                        <button
                                            @click="toggleFeatured(post)"
                                            class="flex-shrink-0 p-1 rounded hover:bg-gray-100 transition-colors"
                                            :title="post.is_featured ? 'Remove from featured' : 'Add to featured'"
                                        >
                                            <svg
                                                class="w-5 h-5 transition-colors"
                                                :class="post.is_featured ? 'text-gold fill-gold' : 'text-gray-300 hover:text-gold'"
                                                :fill="post.is_featured ? 'currentColor' : 'none'"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                            </svg>
                                        </button>

                                        <div
                                            v-if="post.featured_image"
                                            class="w-16 h-12 bg-gray-100 rounded overflow-hidden flex-shrink-0"
                                        >
                                            <img :src="post.featured_image.url" :alt="post.title" class="w-full h-full object-cover" />
                                        </div>
                                        <div class="min-w-0">
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
                                </td>
                                <td class="px-6 py-4 text-sm text-charcoal-light">
                                    {{ formatDate(post.published_at || post.created_at) }}
                                </td>
                                <td class="px-6 py-4">
                                    <ActionChips
                                        :actions="blogActions(post)"
                                        @action="$event === 'delete' && confirmDelete(post)"
                                    />
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
            </div>
        </template>

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
import { ref, computed, onMounted } from 'vue';
import api from '@/services/api';
import { useToast } from '@/stores/toast';
import ConfirmDialog from '@/components/ui/ConfirmDialog.vue';
import ActionChips, { CRUD_ACTIONS } from '@/components/admin/ActionChips.vue';

const toast = useToast();
const loading = ref(true);
const deleting = ref(false);
const posts = ref([]);
const showSeoTips = ref(false);
const deleteDialogOpen = ref(false);
const deletingPost = ref(null);

// Drag and drop state
const dragIndex = ref(null);
const dragOverIndex = ref(null);

// Computed properties for featured/non-featured posts
const featuredPosts = computed(() => {
    return posts.value
        .filter(p => p.is_featured)
        .sort((a, b) => (a.featured_order || 0) - (b.featured_order || 0));
});

const nonFeaturedPosts = computed(() => {
    return posts.value.filter(p => !p.is_featured);
});

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

function blogActions(post) {
    return [
        { ...CRUD_ACTIONS.view, href: post.slug && post.published_at ? `/blog/${post.slug}` : `/blog/preview/${post.id}` },
        { ...CRUD_ACTIONS.edit, to: `/admin/blog/${post.id}` },
        CRUD_ACTIONS.delete,
    ];
}

function confirmDelete(post) {
    deletingPost.value = post;
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

    // Reorder the featured posts array
    const items = [...featuredPosts.value];
    const [movedItem] = items.splice(dragIndex.value, 1);
    items.splice(dragOverIndex.value, 0, movedItem);

    // Update the order in the main posts array
    items.forEach((item, index) => {
        const post = posts.value.find(p => p.id === item.id);
        if (post) post.featured_order = index;
    });

    dragIndex.value = null;
    dragOverIndex.value = null;

    // Save the new order to the server
    try {
        await api.post('/admin/blog-posts/reorder-featured', {
            order: items.map(p => p.id),
        });
        toast.success('Carousel order updated');
    } catch (e) {
        toast.error('Failed to update order');
        // Refetch to restore original order
        fetchPosts();
    }
}

async function toggleFeatured(post) {
    const newValue = !post.is_featured;
    const originalValue = post.is_featured;

    // Optimistic update
    post.is_featured = newValue;

    try {
        await api.put(`/admin/blog-posts/${post.id}`, {
            is_featured: newValue,
        });
        toast.success(newValue ? 'Added to featured' : 'Removed from featured');
    } catch (e) {
        // Revert on error
        post.is_featured = originalValue;
        toast.error('Failed to update featured status');
    }
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
