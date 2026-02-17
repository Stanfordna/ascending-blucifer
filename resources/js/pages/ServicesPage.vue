<template>
    <div class="min-h-screen bg-cream">
        <Navigation />

        <main class="py-20 px-8">
            <div class="max-w-6xl mx-auto">
                <!-- Header -->
                <div class="text-center mb-12">
                    <span class="font-script text-2xl text-terracotta">How I Help</span>
                    <h1 class="text-4xl md:text-5xl font-serif text-charcoal mt-2">Services Offered</h1>
                    <p class="text-charcoal-light mt-4 max-w-2xl mx-auto">
                        Comprehensive nutrition and diabetes care services tailored to your unique needs and goals.
                    </p>
                    <!-- Admin Edit Button -->
                    <router-link
                        v-if="isAuthenticated"
                        to="/admin/services"
                        class="inline-flex items-center gap-2 mt-6 px-4 py-2 text-sm font-medium text-white bg-mountain-blue rounded hover:bg-mountain-blue-dark transition-colors"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Manage Services
                    </router-link>
                </div>

                <!-- Loading -->
                <div v-if="loading" class="flex justify-center py-12">
                    <div class="animate-spin w-8 h-8 border-2 border-mountain-blue border-t-transparent rounded-full"></div>
                </div>

                <!-- Services Grid -->
                <div v-else-if="services.length" class="grid md:grid-cols-2 gap-8">
                    <article
                        v-for="service in services"
                        :key="service.id"
                        class="bg-white p-8 rounded-lg shadow-sm"
                    >
                        <!-- Icon & Title -->
                        <div class="flex items-start gap-4 mb-4">
                            <div class="w-16 h-16 flex-shrink-0 border-2 border-mountain-blue rounded-full flex items-center justify-center text-3xl text-mountain-blue">
                                {{ service.icon }}
                            </div>
                            <div>
                                <router-link
                                    v-if="service.slug"
                                    :to="`/services/${service.slug}`"
                                    class="text-2xl font-serif text-charcoal hover:text-mountain-blue transition-colors"
                                >
                                    {{ service.title }}
                                </router-link>
                                <h2 v-else class="text-2xl font-serif text-charcoal">{{ service.title }}</h2>
                                <p class="text-charcoal-light font-light mt-1">{{ service.description }}</p>
                            </div>
                        </div>

                        <!-- Extended Description -->
                        <div v-if="service.extended_description" class="mt-4 pt-4 border-t border-gray-100">
                            <p class="text-charcoal-light leading-relaxed whitespace-pre-line">
                                {{ service.extended_description }}
                            </p>
                        </div>

                        <!-- Link -->
                        <router-link
                            v-if="service.slug"
                            :to="`/services/${service.slug}`"
                            class="inline-block mt-4 text-mountain-blue font-semibold text-sm tracking-wide uppercase hover:text-terracotta transition-colors"
                        >
                            Learn More &rarr;
                        </router-link>
                        <a
                            v-else-if="service.link_url"
                            :href="service.link_url"
                            class="inline-block mt-4 text-mountain-blue font-semibold text-sm tracking-wide uppercase hover:text-terracotta transition-colors"
                        >
                            Learn More &rarr;
                        </a>
                    </article>
                </div>

                <!-- Empty State -->
                <div v-else class="text-center py-12">
                    <p class="text-charcoal-light">No services available yet. Check back soon!</p>
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
const services = ref([]);

async function fetchServices() {
    try {
        const response = await api.get('/services/all');
        services.value = response.data;
    } catch (e) {
        console.error('Failed to load services', e);
    } finally {
        loading.value = false;
    }
}

onMounted(() => {
    fetchServices();
});
</script>
