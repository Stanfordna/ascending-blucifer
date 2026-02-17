<template>
    <div class="min-h-screen bg-cream">
        <Navigation />

        <main class="py-20 px-8">
            <!-- Loading -->
            <div v-if="loading" class="flex justify-center py-12">
                <div class="animate-spin w-8 h-8 border-2 border-mountain-blue border-t-transparent rounded-full"></div>
            </div>

            <!-- Error -->
            <div v-else-if="error" class="max-w-2xl mx-auto text-center py-12">
                <h1 class="text-3xl font-serif text-charcoal mb-4">Service Not Found</h1>
                <p class="text-charcoal-light mb-8">The service you're looking for doesn't exist or is no longer available.</p>
                <router-link
                    to="/services"
                    class="inline-block px-6 py-3 bg-mountain-blue text-white rounded hover:bg-mountain-blue-dark transition-colors"
                >
                    View All Services
                </router-link>
            </div>

            <!-- Service Content -->
            <article v-else class="max-w-3xl mx-auto">
                <!-- Navigation -->
                <div class="flex items-center justify-between mb-8">
                    <router-link
                        to="/services"
                        class="inline-flex items-center gap-2 text-mountain-blue hover:text-terracotta transition-colors"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        All Services
                    </router-link>

                    <!-- Admin Edit Button -->
                    <router-link
                        v-if="isAuthenticated"
                        to="/admin/services"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-mountain-blue rounded hover:bg-mountain-blue-dark transition-colors"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Manage Services
                    </router-link>
                </div>

                <!-- Header -->
                <header class="mb-8">
                    <div class="flex items-start gap-6 mb-6">
                        <div v-if="service.icon" class="w-20 h-20 flex-shrink-0 border-2 border-mountain-blue rounded-full flex items-center justify-center text-4xl text-mountain-blue">
                            {{ service.icon }}
                        </div>
                        <div>
                            <h1 class="text-4xl md:text-5xl font-serif text-charcoal leading-tight">{{ service.title }}</h1>
                            <p class="text-xl text-charcoal-light mt-3 font-light">{{ service.description }}</p>
                        </div>
                    </div>
                </header>

                <!-- Extended Description -->
                <div v-if="service.extended_description" class="prose prose-lg max-w-none">
                    <p class="text-charcoal-light leading-relaxed whitespace-pre-line">{{ service.extended_description }}</p>
                </div>

                <!-- Link -->
                <div v-if="service.link_url" class="mt-8">
                    <a
                        :href="service.link_url"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-mountain-blue text-white rounded hover:bg-mountain-blue-dark transition-colors"
                    >
                        Learn More
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </a>
                </div>

                <!-- CTA -->
                <div class="mt-12 pt-8 border-t border-gray-200">
                    <div class="bg-mountain-blue/5 rounded-lg p-8 text-center relative">
                        <button
                            v-if="isAuthenticated"
                            @click="promptsModalOpen = true; promptsModalTab = 'service_cta'"
                            class="absolute top-3 right-3 bg-white/80 hover:bg-white text-charcoal p-2 rounded-full shadow transition-all hover:scale-110"
                            title="Edit Service CTA"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                        </button>
                        <h3 class="text-xl font-serif text-charcoal mb-2">
                            {{ contentStore.getBlock('service_cta_title', 'Interested in This Service?') }}
                        </h3>
                        <p class="text-charcoal-light mb-4">
                            {{ contentStore.getBlock('service_cta_description', 'Schedule a consultation to learn how this service can support your health goals.') }}
                        </p>
                        <a
                            href="/#contact"
                            class="inline-block px-6 py-3 bg-terracotta text-white rounded hover:bg-terracotta/90 transition-colors"
                        >
                            {{ contentStore.getBlock('service_cta_button', 'Book a Consultation') }}
                        </a>
                    </div>
                </div>

                <ContactPromptsModal
                    v-model="promptsModalOpen"
                    :initial-tab="promptsModalTab"
                    @saved="contentStore.refresh()"
                />
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
const promptsModalTab = ref('service_cta');
const originalColors = ref(null);

const props = defineProps({
    slug: String,
});

const route = useRoute();
const loading = ref(true);
const error = ref(false);
const service = ref({});

async function fetchService() {
    loading.value = true;
    error.value = false;

    try {
        const response = await api.get(`/services/${route.params.slug}`);
        service.value = response.data;

        document.title = `${service.value.title} | Maggie Chamberlain`;

        // Apply color overrides if set
        if (service.value.color_primary || service.value.color_secondary || service.value.color_accent) {
            if (!originalColors.value) {
                originalColors.value = { ...settingsStore.colors };
            }
            settingsStore.setColors({
                primary: service.value.color_primary || settingsStore.colors.primary,
                secondary: service.value.color_secondary || settingsStore.colors.secondary,
                accent: service.value.color_accent || settingsStore.colors.accent,
            });
        }
    } catch (e) {
        console.error('Failed to load service', e);
        error.value = true;
    } finally {
        loading.value = false;
    }
}

watch(() => route.params.slug, () => {
    if (route.params.slug) {
        fetchService();
    }
});

onMounted(() => {
    fetchService();
    contentStore.fetchBlocks();
});

onUnmounted(() => {
    if (originalColors.value) {
        settingsStore.setColors(originalColors.value);
    }
});
</script>
