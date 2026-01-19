<template>
    <section id="services" class="py-20 px-8 bg-cream-dark">
        <div class="max-w-7xl mx-auto">
            <div class="section-header">
                <span class="script">How I Help</span>
                <h2>Services Offered</h2>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div
                    v-for="service in services"
                    :key="service.title"
                    class="bg-cream p-10 text-center relative transition-all duration-300 border border-transparent hover:-translate-y-2 hover:border-gold hover:shadow-xl group"
                >
                    <!-- Top accent line -->
                    <div class="absolute -top-px left-1/2 -translate-x-1/2 w-12 h-1 bg-gold"></div>

                    <!-- Icon -->
                    <div class="w-20 h-20 mx-auto mb-6 border-2 border-mountain-blue rounded-full flex items-center justify-center text-3xl text-mountain-blue transition-all duration-300 group-hover:bg-mountain-blue group-hover:text-white">
                        {{ service.icon }}
                    </div>

                    <h3 class="text-2xl mb-4 text-charcoal">{{ service.title }}</h3>
                    <p class="text-charcoal-light font-light">{{ service.description }}</p>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
const services = [
    {
        icon: '💉',
        title: 'Diabetes Education',
        description: 'Personalized strategies for blood sugar management and living well with Type 1 or Type 2 diabetes.',
    },
    {
        icon: '🌿',
        title: 'Nutrition Guidance',
        description: 'Evidence-based meal planning that honors your lifestyle and supports your health goals.',
    },
    {
        icon: '⚙️',
        title: 'Pump Training',
        description: 'Expert instruction on insulin pumps and automated delivery systems including bionic pancreas.',
    },
    {
        icon: '🎤',
        title: 'Speaking',
        description: 'Engaging presentations for healthcare groups, conferences, and community organizations.',
    },
];
</script>
