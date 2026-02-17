<template>
    <div class="min-h-screen bg-cream">
        <Navigation />

        <main class="py-20 px-8">
            <div class="max-w-6xl mx-auto">
                <!-- Header -->
                <div class="text-center mb-12">
                    <span class="font-script text-2xl text-terracotta">Kind Words</span>
                    <h1 class="text-4xl md:text-5xl font-serif text-charcoal mt-2">Client Testimonials</h1>
                    <p class="text-charcoal-light mt-4 max-w-2xl mx-auto">
                        Hear from clients who have transformed their health and relationship with food and diabetes management.
                    </p>
                    <!-- Admin Edit Button -->
                    <router-link
                        v-if="isAuthenticated"
                        to="/admin/testimonials"
                        class="inline-flex items-center gap-2 mt-6 px-4 py-2 text-sm font-medium text-white bg-mountain-blue rounded hover:bg-mountain-blue-dark transition-colors"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Manage Testimonials
                    </router-link>
                </div>

                <!-- Loading -->
                <div v-if="loading" class="flex justify-center py-12">
                    <div class="animate-spin w-8 h-8 border-2 border-mountain-blue border-t-transparent rounded-full"></div>
                </div>

                <!-- Testimonials Grid -->
                <div v-else-if="testimonials.length" class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <article
                        v-for="testimonial in testimonials"
                        :key="testimonial.id"
                        class="bg-white rounded-lg p-8 shadow-sm"
                    >
                        <!-- Quote Icon -->
                        <svg
                            class="w-10 h-10 mb-4"
                            :class="testimonial.color_accent ? '' : 'text-gold/40'"
                            :style="testimonial.color_accent ? { color: testimonial.color_accent } : {}"
                            fill="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
                        </svg>

                        <!-- Quote -->
                        <blockquote class="text-charcoal-light leading-relaxed italic mb-6">
                            "{{ testimonial.quote }}"
                        </blockquote>

                        <!-- Client Info -->
                        <div class="flex items-center gap-4 pt-4 border-t border-gray-100">
                            <div
                                v-if="testimonial.photo"
                                class="w-14 h-14 rounded-full overflow-hidden flex-shrink-0"
                            >
                                <img
                                    :src="testimonial.photo.url"
                                    :alt="testimonial.client_name"
                                    class="w-full h-full object-cover"
                                />
                            </div>
                            <div
                                v-else
                                class="w-14 h-14 rounded-full flex items-center justify-center flex-shrink-0"
                                :class="testimonial.color_primary ? '' : 'bg-mountain-blue/10'"
                                :style="testimonial.color_primary ? { backgroundColor: testimonial.color_primary + '1a' } : {}"
                            >
                                <span
                                    class="font-medium text-xl"
                                    :class="testimonial.color_primary ? '' : 'text-mountain-blue'"
                                    :style="testimonial.color_primary ? { color: testimonial.color_primary } : {}"
                                >
                                    {{ testimonial.client_name.charAt(0) }}
                                </span>
                            </div>
                            <div>
                                <p class="font-medium text-charcoal">{{ testimonial.client_name }}</p>
                                <p v-if="testimonial.client_title" class="text-sm text-charcoal-light">
                                    {{ testimonial.client_title }}
                                </p>
                            </div>
                        </div>
                    </article>
                </div>

                <!-- Empty State -->
                <div v-else class="text-center py-12">
                    <p class="text-charcoal-light">No testimonials available yet. Check back soon!</p>
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
const testimonials = ref([]);

async function fetchTestimonials() {
    try {
        const response = await api.get('/testimonials/all');
        testimonials.value = response.data;
    } catch (e) {
        console.error('Failed to load testimonials', e);
    } finally {
        loading.value = false;
    }
}

onMounted(() => {
    fetchTestimonials();
});
</script>
