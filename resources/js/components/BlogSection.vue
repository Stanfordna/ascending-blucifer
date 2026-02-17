<template>
    <section id="blog" class="py-20 px-8 bg-cream">
        <div class="max-w-7xl mx-auto">
            <div class="section-header">
                <span class="script">The Journal</span>
                <h2>Insights & Inspiration</h2>
            </div>

            <!-- Loading state -->
            <div v-if="loading" class="flex justify-center py-12">
                <div class="animate-spin w-8 h-8 border-2 border-mountain-blue border-t-transparent rounded-full"></div>
            </div>

            <!-- Featured posts carousel -->
            <div v-else-if="posts.length" class="relative">
                <!-- Carousel navigation -->
                <div v-if="posts.length > 3" class="absolute -left-4 top-1/2 -translate-y-1/2 z-10">
                    <button
                        @click="prevSlide"
                        class="w-10 h-10 bg-white rounded-full shadow-lg flex items-center justify-center hover:bg-gray-50 transition-colors"
                        :disabled="currentSlide === 0"
                        :class="currentSlide === 0 ? 'opacity-50 cursor-not-allowed' : ''"
                    >
                        <svg class="w-5 h-5 text-charcoal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                </div>

                <div class="overflow-hidden">
                    <div
                        class="flex transition-transform duration-500 ease-in-out"
                        :style="{ transform: `translateX(-${currentSlide * (100 / visibleCount)}%)` }"
                    >
                        <article
                            v-for="post in posts"
                            :key="post.id"
                            class="flex-shrink-0 px-4"
                            :style="{ width: `${100 / visibleCount}%` }"
                        >
                            <div class="bg-white transition-all duration-300 hover:-translate-y-2 hover:shadow-xl h-full">
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
                                    <h3 class="text-2xl mt-2 mb-3 text-charcoal">{{ post.title }}</h3>
                                    <p class="text-charcoal-light font-light line-clamp-2">{{ post.excerpt }}</p>
                                    <router-link
                                        :to="`/blog/${post.slug}`"
                                        class="inline-block mt-4 text-mountain-blue font-semibold text-sm tracking-wide uppercase hover:text-terracotta transition-colors"
                                    >
                                        Read More &rarr;
                                    </router-link>
                                </div>
                            </div>
                        </article>
                    </div>
                </div>

                <div v-if="posts.length > 3" class="absolute -right-4 top-1/2 -translate-y-1/2 z-10">
                    <button
                        @click="nextSlide"
                        class="w-10 h-10 bg-white rounded-full shadow-lg flex items-center justify-center hover:bg-gray-50 transition-colors"
                        :disabled="currentSlide >= maxSlide"
                        :class="currentSlide >= maxSlide ? 'opacity-50 cursor-not-allowed' : ''"
                    >
                        <svg class="w-5 h-5 text-charcoal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>

                <!-- Carousel dots -->
                <div v-if="posts.length > 3" class="flex justify-center gap-2 mt-6">
                    <button
                        v-for="i in maxSlide + 1"
                        :key="i"
                        @click="currentSlide = i - 1"
                        class="w-2 h-2 rounded-full transition-colors"
                        :class="currentSlide === i - 1 ? 'bg-mountain-blue' : 'bg-gray-300 hover:bg-gray-400'"
                    ></button>
                </div>
            </div>

            <!-- Empty state -->
            <div v-else class="text-center py-12">
                <p class="text-charcoal-light">No blog posts yet. Check back soon!</p>
            </div>

            <!-- View all link -->
            <div v-if="posts.length" class="text-center mt-8">
                <router-link
                    to="/blog"
                    class="inline-block text-mountain-blue font-semibold text-sm tracking-wide uppercase hover:text-terracotta transition-colors"
                >
                    View All Posts &rarr;
                </router-link>
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import api from '@/services/api';

const loading = ref(true);
const posts = ref([]);
const currentSlide = ref(0);
const visibleCount = 3;

const maxSlide = computed(() => Math.max(0, posts.value.length - visibleCount));

// Color gradients for posts without images
const gradients = [
    { from: '#D99A7D', to: '#C4785A' },  // Terracotta
    { from: '#7A9BB8', to: '#5B7FA4' },  // Mountain blue
    { from: '#E5C070', to: '#D4A84B' },  // Gold
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
        year: 'numeric',
    });
}

function prevSlide() {
    if (currentSlide.value > 0) currentSlide.value--;
}

function nextSlide() {
    if (currentSlide.value < maxSlide.value) currentSlide.value++;
}

async function fetchPosts() {
    try {
        // Fetch featured posts first, fall back to recent posts
        let response = await api.get('/blog-posts', { params: { featured: true, limit: 10 } });
        if (response.data.length === 0) {
            // No featured posts, get recent ones instead
            response = await api.get('/blog-posts', { params: { limit: 6 } });
        }
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
