<template>
    <section v-if="services.length" id="services" class="py-20 px-8 bg-cream-dark">
        <div class="max-w-7xl mx-auto">
            <div class="section-header">
                <span class="script">How I Help</span>
                <h2>Services Offered</h2>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <component
                    :is="service.slug ? 'router-link' : 'div'"
                    v-for="service in services"
                    :key="service.id"
                    v-bind="service.slug ? { to: `/services/${service.slug}` } : {}"
                    class="bg-cream p-10 text-center relative transition-all duration-300 border border-transparent hover:-translate-y-2 hover:border-gold hover:shadow-xl group block"
                >
                    <!-- Top accent line -->
                    <div class="absolute -top-px left-1/2 -translate-x-1/2 w-12 h-1 bg-gold"></div>

                    <!-- Icon -->
                    <div class="w-20 h-20 mx-auto mb-6 border-2 border-mountain-blue rounded-full flex items-center justify-center text-3xl text-mountain-blue transition-all duration-300 group-hover:bg-mountain-blue group-hover:text-white">
                        {{ service.icon }}
                    </div>

                    <h3 class="text-2xl mb-4 text-charcoal">{{ service.title }}</h3>
                    <p class="text-charcoal-light font-light">{{ service.description }}</p>
                </component>
            </div>

            <!-- View All Link -->
            <div class="text-center mt-12">
                <router-link
                    to="/services"
                    class="inline-block text-mountain-blue font-semibold text-sm tracking-wide uppercase hover:text-terracotta transition-colors"
                >
                    View All Services &rarr;
                </router-link>
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '@/services/api';

const services = ref([]);

async function fetchServices() {
    try {
        const response = await api.get('/services');
        services.value = response.data;
    } catch (e) {
        console.error('Failed to load services', e);
    }
}

onMounted(() => {
    fetchServices();
});
</script>
