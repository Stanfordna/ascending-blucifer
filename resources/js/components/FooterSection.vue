<template>
    <footer class="py-16 px-8 bg-charcoal text-white">
        <div class="max-w-7xl mx-auto">
            <!-- Footer Grid -->
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
                <!-- Brand -->
                <div>
                    <h3 class="font-serif text-2xl mb-4">
                        Maggie <span class="font-script text-gold">Chamberlain</span>
                    </h3>
                    <p class="text-white/60 font-light text-sm leading-relaxed">
                        Registered Dietitian & Certified Diabetes Care and Education Specialist,
                        helping people thrive from the heart of Colorado.
                    </p>
                </div>

                <!-- Services -->
                <div>
                    <h4 class="text-xs tracking-widest uppercase text-gold mb-5">Services</h4>
                    <ul class="space-y-3">
                        <li v-for="link in servicesLinks" :key="link">
                            <a href="#" class="text-white/60 text-sm font-light hover:text-terracotta-light transition-colors">
                                {{ link }}
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Explore -->
                <div>
                    <h4 class="text-xs tracking-widest uppercase text-gold mb-5">Explore</h4>
                    <ul class="space-y-3">
                        <li v-for="link in exploreLinks" :key="link">
                            <a href="#" class="text-white/60 text-sm font-light hover:text-terracotta-light transition-colors">
                                {{ link }}
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Connect -->
                <div>
                    <h4 class="text-xs tracking-widest uppercase text-gold mb-5">Connect</h4>
                    <ul class="space-y-3">
                        <li v-for="link in connectLinks" :key="link">
                            <a href="#" class="text-white/60 text-sm font-light hover:text-terracotta-light transition-colors">
                                {{ link }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Divider -->
            <div class="h-px bg-white/10 mb-8"></div>

            <!-- Bottom -->
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-white/40 text-sm">
                    &copy; {{ currentYear }} Maggie Chamberlain, MS, RD, CDCES
                </p>
                <div class="flex gap-6">
                    <a href="#" class="text-white/60 text-sm hover:text-gold transition-colors">LinkedIn</a>
                    <a href="#" class="text-white/60 text-sm hover:text-gold transition-colors">Instagram</a>
                </div>
            </div>
        </div>
    </footer>
</template>

<script setup>
import { computed } from 'vue';

const currentYear = computed(() => new Date().getFullYear());

const servicesLinks = [
    'Diabetes Education',
    'Nutrition Guidance',
    'Pump Training',
    'Speaking',
];

const exploreLinks = [
    'About',
    'Journal',
    'Resources',
    'FAQ',
];

const connectLinks = [
    'Denver, Colorado',
    'LinkedIn',
    'Instagram',
    'Email',
];
</script>
