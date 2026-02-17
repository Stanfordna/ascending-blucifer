<template>
    <section v-if="testimonials.length" id="testimonials" class="py-20 bg-cream-dark">
        <div class="max-w-6xl mx-auto px-8">
            <!-- Header -->
            <div class="text-center mb-12">
                <span class="font-script text-3xl text-terracotta">Kind Words</span>
                <h2 class="text-4xl md:text-5xl text-charcoal mt-2">What Clients Say</h2>
            </div>

            <!-- Testimonials Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div
                    v-for="testimonial in testimonials"
                    :key="testimonial.id"
                    class="bg-white rounded-lg p-8 shadow-sm"
                >
                    <!-- Quote -->
                    <div class="mb-6">
                        <svg class="w-8 h-8 text-gold/50 mb-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
                        </svg>
                        <p class="text-charcoal-light leading-relaxed italic">
                            "{{ testimonial.quote }}"
                        </p>
                    </div>

                    <!-- Client Info -->
                    <div class="flex items-center gap-4">
                        <div
                            v-if="testimonial.photo"
                            class="w-12 h-12 rounded-full overflow-hidden"
                        >
                            <img
                                :src="testimonial.photo.url"
                                :alt="testimonial.client_name"
                                class="w-full h-full object-cover"
                            />
                        </div>
                        <div
                            v-else
                            class="w-12 h-12 rounded-full bg-mountain-blue/10 flex items-center justify-center"
                        >
                            <span class="text-mountain-blue font-medium text-lg">
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
                </div>
            </div>

            <!-- View All Link -->
            <div class="text-center mt-12">
                <router-link
                    to="/testimonials"
                    class="inline-block text-mountain-blue font-semibold text-sm tracking-wide uppercase hover:text-terracotta transition-colors"
                >
                    Read More Testimonials &rarr;
                </router-link>
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '@/services/api';

const testimonials = ref([]);

async function fetchTestimonials() {
    try {
        const response = await api.get('/testimonials');
        testimonials.value = response.data;
    } catch (e) {
        console.error('Failed to load testimonials', e);
    }
}

onMounted(() => {
    fetchTestimonials();
});
</script>
