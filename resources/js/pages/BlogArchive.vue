<template>
    <div class="min-h-screen bg-cream">
        <Navigation />

        <main class="py-20 px-8">
            <div class="max-w-6xl mx-auto">
                <!-- Header -->
                <div class="text-center mb-12">
                    <span class="font-script text-2xl text-terracotta">The Journal</span>
                    <h1 class="text-4xl md:text-5xl font-serif text-charcoal mt-2">Insights & Inspiration</h1>
                    <p class="text-charcoal-light mt-4 max-w-2xl mx-auto">
                        Explore articles on nutrition, diabetes management, and living well with chronic conditions.
                    </p>
                    <!-- Admin Edit Button -->
                    <router-link
                        v-if="isAuthenticated"
                        to="/admin/blog"
                        class="inline-flex items-center gap-2 mt-6 px-4 py-2 text-sm font-medium text-white bg-mountain-blue rounded hover:bg-mountain-blue-dark transition-colors"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Manage Posts
                    </router-link>
                </div>

                <!-- Loading -->
                <div v-if="loading" class="flex justify-center py-12">
                    <div class="animate-spin w-8 h-8 border-2 border-mountain-blue border-t-transparent rounded-full"></div>
                </div>

                <!-- Posts Grid -->
                <div v-else-if="posts.length" class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <article
                        v-for="post in posts"
                        :key="post.id"
                        class="bg-white transition-all duration-300 hover:-translate-y-2 hover:shadow-xl"
                    >
                        <!-- Image -->
                        <div
                            class="h-56 relative"
                            :style="{ background: getPostGradient(post) }"
                        >
                            <img
                                v-if="post.featured_image"
                                :src="post.featured_image.url"
                                :alt="post.title"
                                class="w-full h-full object-cover"
                            />
                            <div class="absolute bottom-0 left-8 right-8 h-1 bg-gold"></div>
                        </div>

                        <!-- Content -->
                        <div class="p-8">
                            <span class="font-script text-xl text-terracotta">{{ formatDate(post.published_at) }}</span>
                            <h2 class="text-2xl mt-2 mb-3 text-charcoal font-serif">{{ post.title }}</h2>
                            <p class="text-charcoal-light font-light line-clamp-3">{{ post.excerpt }}</p>
                            <router-link
                                :to="`/blog/${post.slug}`"
                                class="inline-block mt-4 text-mountain-blue font-semibold text-sm tracking-wide uppercase hover:text-terracotta transition-colors"
                            >
                                Read More &rarr;
                            </router-link>
                        </div>
                    </article>
                </div>

                <!-- Empty State -->
                <div v-else class="text-center py-12">
                    <p class="text-charcoal-light">No blog posts yet. Check back soon!</p>
                </div>

                <!-- Back to home -->
                <div class="text-center mt-12">
                    <router-link
                        to="/"
                        class="inline-block text-mountain-blue font-semibold text-sm tracking-wide uppercase hover:text-terracotta transition-colors"
                    >
                        &larr; Back to Home
                    </router-link>
                </div>
            </div>
        </main>

        <FooterSection />
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import api from '@/services/api';
import { useAuthStore } from '@/stores/auth';
import Navigation from '@/components/Navigation.vue';
import FooterSection from '@/components/FooterSection.vue';

const auth = useAuthStore();
const isAuthenticated = computed(() => auth.isAuthenticated);

const loading = ref(true);
const posts = ref([]);

const gradients = [
    { from: '#D99A7D', to: '#C4785A' },
    { from: '#7A9BB8', to: '#5B7FA4' },
    { from: '#E5C070', to: '#D4A84B' },
];

function getPostGradient(post) {
    if (post.featured_image) return 'transparent';
    const index = posts.value.indexOf(post) % gradients.length;
    const g = gradients[index];
    return `linear-gradient(135deg, ${g.from}, ${g.to})`;
}

function formatDate(date) {
    if (!date) return '';
    return new Date(date).toLocaleDateString('en-US', {
        month: 'long',
        day: 'numeric',
        year: 'numeric',
    });
}

async function fetchPosts() {
    try {
        const response = await api.get('/blog-posts', { params: { limit: 50 } });
        posts.value = response.data;
    } catch (e) {
        console.error('Failed to load blog posts', e);
    } finally {
        loading.value = false;
    }
}

onMounted(() => {
    fetchPosts();
});
</script>
