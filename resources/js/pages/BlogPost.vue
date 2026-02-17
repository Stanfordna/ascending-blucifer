<template>
    <div class="min-h-screen bg-cream">
        <!-- Preview Banner -->
        <div v-if="isPreview" class="bg-gold text-charcoal text-center py-2 px-4 text-sm font-medium">
            Preview Mode — This post is not yet published.
            <router-link :to="`/admin/blog/${props.id}`" class="underline ml-2 hover:text-charcoal/70">
                Back to Editor
            </router-link>
        </div>

        <Navigation />

        <main class="py-20 px-8">
            <!-- Loading -->
            <div v-if="loading" class="flex justify-center py-12">
                <div class="animate-spin w-8 h-8 border-2 border-mountain-blue border-t-transparent rounded-full"></div>
            </div>

            <!-- Error -->
            <div v-else-if="error" class="max-w-2xl mx-auto text-center py-12">
                <h1 class="text-3xl font-serif text-charcoal mb-4">Post Not Found</h1>
                <p class="text-charcoal-light mb-8">The article you're looking for doesn't exist or has been removed.</p>
                <router-link
                    to="/blog"
                    class="inline-block px-6 py-3 bg-mountain-blue text-white rounded hover:bg-mountain-blue-dark transition-colors"
                >
                    View All Posts
                </router-link>
            </div>

            <!-- Post Content -->
            <article v-else class="max-w-3xl mx-auto">
                <!-- Navigation -->
                <div class="flex items-center justify-between mb-8">
                    <router-link
                        to="/blog"
                        class="inline-flex items-center gap-2 text-mountain-blue hover:text-terracotta transition-colors"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Back to All Posts
                    </router-link>

                    <!-- Admin Edit Button -->
                    <router-link
                        v-if="isAuthenticated && post.id"
                        :to="`/admin/blog/${post.id}`"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-mountain-blue rounded hover:bg-mountain-blue-dark transition-colors"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit Post
                    </router-link>
                </div>

                <!-- Header -->
                <header class="mb-8">
                    <div class="flex items-center gap-4 text-sm text-charcoal-light mb-4">
                        <time :datetime="post.published_at">{{ formatDate(post.published_at) }}</time>
                        <span v-if="post.updated_at && post.updated_at !== post.published_at" class="text-xs">
                            (Updated {{ formatDate(post.updated_at) }})
                        </span>
                    </div>
                    <h1 class="text-4xl md:text-5xl font-serif text-charcoal leading-tight">{{ post.title }}</h1>
                    <p v-if="post.excerpt" class="text-xl text-charcoal-light mt-4 font-light">{{ post.excerpt }}</p>
                </header>

                <!-- Featured Image -->
                <div v-if="post.featured_image" class="mb-10">
                    <img
                        :src="post.featured_image.url"
                        :alt="post.title"
                        class="w-full rounded-lg shadow-lg"
                    />
                </div>

                <!-- Content -->
                <div class="prose prose-lg max-w-none blog-content" v-html="renderedContent"></div>

                <!-- Author/CTA -->
                <div class="mt-12 pt-8 border-t border-gray-200">
                    <div class="bg-mountain-blue/5 rounded-lg p-8 text-center relative">
                        <button
                            v-if="isAuthenticated"
                            @click="promptsModalOpen = true; promptsModalTab = 'blog_cta'"
                            class="absolute top-3 right-3 bg-white/80 hover:bg-white text-charcoal p-2 rounded-full shadow transition-all hover:scale-110"
                            title="Edit Blog CTA"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                        </button>
                        <h3 class="text-xl font-serif text-charcoal mb-2">
                            {{ contentStore.getBlock('blog_cta_title', 'Ready to Take the Next Step?') }}
                        </h3>
                        <p class="text-charcoal-light mb-4">
                            {{ contentStore.getBlock('blog_cta_description', 'Schedule a consultation to discuss how personalized nutrition can support your health goals.') }}
                        </p>
                        <a
                            href="/#contact"
                            class="inline-block px-6 py-3 bg-terracotta text-white rounded hover:bg-terracotta/90 transition-colors"
                        >
                            {{ contentStore.getBlock('blog_cta_button', 'Book a Consultation') }}
                        </a>
                    </div>
                </div>

                <ContactPromptsModal
                    v-model="promptsModalOpen"
                    :initial-tab="promptsModalTab"
                    @saved="contentStore.refresh()"
                />

                <!-- Share -->
                <div class="mt-8 flex items-center justify-center gap-4">
                    <span class="text-sm text-charcoal-light">Share this article:</span>
                    <a
                        :href="`https://twitter.com/intent/tweet?text=${encodeURIComponent(post.title)}&url=${encodeURIComponent(currentUrl)}`"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="text-charcoal-light hover:text-mountain-blue transition-colors"
                        aria-label="Share on Twitter"
                    >
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                        </svg>
                    </a>
                    <a
                        :href="`https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(currentUrl)}`"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="text-charcoal-light hover:text-mountain-blue transition-colors"
                        aria-label="Share on LinkedIn"
                    >
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                        </svg>
                    </a>
                    <a
                        :href="`mailto:?subject=${encodeURIComponent(post.title)}&body=${encodeURIComponent(currentUrl)}`"
                        class="text-charcoal-light hover:text-mountain-blue transition-colors"
                        aria-label="Share via Email"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </a>
                </div>
            </article>
        </main>

        <FooterSection />
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, watch } from 'vue';
import { useRoute } from 'vue-router';
import api from '@/services/api';
import { useAuthStore } from '@/stores/auth';
import { useSettingsStore } from '@/stores/settings';
import { useContentStore } from '@/stores/content';
import Navigation from '@/components/Navigation.vue';
import FooterSection from '@/components/FooterSection.vue';
import ContactPromptsModal from '@/components/ContactPromptsModal.vue';

const auth = useAuthStore();
const settingsStore = useSettingsStore();
const contentStore = useContentStore();
const isAuthenticated = computed(() => auth.isAuthenticated);
const promptsModalOpen = ref(false);
const promptsModalTab = ref('blog_cta');
const originalColors = ref(null);

const props = defineProps({
    slug: String,
    id: String,
    preview: Boolean,
});

const route = useRoute();
const loading = ref(true);
const error = ref(false);
const post = ref({});
const isPreview = computed(() => props.preview || false);

const currentUrl = computed(() => window.location.href);

// Simple markdown-to-HTML conversion for blog content
const renderedContent = computed(() => {
    if (!post.value.content) return '';

    let html = post.value.content;

    // Convert markdown to HTML (basic conversion)
    // Headers
    html = html.replace(/^### (.*$)/gim, '<h3>$1</h3>');
    html = html.replace(/^## (.*$)/gim, '<h2>$1</h2>');
    html = html.replace(/^# (.*$)/gim, '<h1>$1</h1>');

    // Bold and italic
    html = html.replace(/\*\*\*(.*?)\*\*\*/g, '<strong><em>$1</em></strong>');
    html = html.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
    html = html.replace(/\*(.*?)\*/g, '<em>$1</em>');

    // Links
    html = html.replace(/\[([^\]]+)\]\(([^)]+)\)/g, '<a href="$2" class="text-mountain-blue hover:text-terracotta">$1</a>');

    // Lists
    html = html.replace(/^\d+\. (.*$)/gim, '<li>$1</li>');
    html = html.replace(/^- (.*$)/gim, '<li>$1</li>');

    // Paragraphs (double newlines)
    html = html.split('\n\n').map(p => {
        if (p.startsWith('<h') || p.startsWith('<li') || p.trim() === '') return p;
        return `<p>${p}</p>`;
    }).join('\n');

    // Line breaks
    html = html.replace(/\n/g, '<br>');

    // Clean up list items
    html = html.replace(/(<li>.*<\/li>)/g, '<ul class="list-disc pl-6 my-4">$1</ul>');
    html = html.replace(/<\/ul>\s*<ul[^>]*>/g, '');

    return html;
});

function formatDate(date) {
    if (!date) return '';
    return new Date(date).toLocaleDateString('en-US', {
        month: 'long',
        day: 'numeric',
        year: 'numeric',
    });
}

async function fetchPost() {
    loading.value = true;
    error.value = false;

    try {
        let response;
        if (isPreview.value && props.id) {
            // Preview mode: fetch from admin endpoint by ID
            response = await api.get(`/admin/blog-posts/${props.id}`);
        } else {
            // Public mode: fetch by slug
            response = await api.get(`/blog-posts/${route.params.slug}`);
        }
        post.value = response.data;

        // Set page meta for SEO
        const titleSuffix = isPreview.value ? ' (Preview)' : '';
        document.title = (post.value.meta_title || `${post.value.title} | Maggie Chamberlain`) + titleSuffix;

        // Update meta description
        const metaDesc = document.querySelector('meta[name="description"]');
        if (metaDesc) {
            metaDesc.setAttribute('content', post.value.meta_description || post.value.excerpt || '');
        }

        // Apply color overrides if set
        if (post.value.color_primary || post.value.color_secondary || post.value.color_accent) {
            if (!originalColors.value) {
                originalColors.value = { ...settingsStore.colors };
            }
            settingsStore.setColors({
                primary: post.value.color_primary || settingsStore.colors.primary,
                secondary: post.value.color_secondary || settingsStore.colors.secondary,
                accent: post.value.color_accent || settingsStore.colors.accent,
            });
        }
    } catch (e) {
        console.error('Failed to load blog post', e);
        error.value = true;
    } finally {
        loading.value = false;
    }
}

// Watch for route changes (navigating between posts)
watch(() => [route.params.slug, props.id], () => {
    if (route.params.slug || props.id) {
        fetchPost();
    }
});

onMounted(() => {
    fetchPost();
    contentStore.fetchBlocks();
});

onUnmounted(() => {
    if (originalColors.value) {
        settingsStore.setColors(originalColors.value);
    }
});
</script>

<style scoped>
.blog-content :deep(h2) {
    font-size: 1.5rem;
    font-family: var(--font-serif, 'Cormorant Garamond', serif);
    color: #2D3748;
    margin-top: 2rem;
    margin-bottom: 1rem;
}

.blog-content :deep(h3) {
    font-size: 1.25rem;
    font-family: var(--font-serif, 'Cormorant Garamond', serif);
    color: #2D3748;
    margin-top: 1.5rem;
    margin-bottom: 0.75rem;
}

.blog-content :deep(p) {
    color: #4A5568;
    line-height: 1.75;
    margin-bottom: 1rem;
}

.blog-content :deep(ul) {
    margin-top: 1rem;
    margin-bottom: 1rem;
}

.blog-content :deep(li) {
    color: #4A5568;
    margin-bottom: 0.5rem;
}

.blog-content :deep(strong) {
    color: #2D3748;
    font-weight: 600;
}

.blog-content :deep(a) {
    color: #4A6FA5;
    text-decoration: underline;
    transition: color 0.2s;
}

.blog-content :deep(a:hover) {
    color: #C4785A;
}
</style>
