<template>
    <section class="min-h-screen flex items-center pt-32 pb-16 px-8 relative overflow-hidden">
        <!-- Background pattern -->
        <div class="absolute inset-0 opacity-5">
            <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-gold rounded-full blur-3xl"></div>
            <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-mountain-blue rounded-full blur-3xl"></div>
        </div>

        <div class="max-w-7xl mx-auto grid lg:grid-cols-2 gap-16 items-center relative">
            <!-- Content -->
            <div class="text-center lg:text-left">
                <span class="font-script text-3xl text-terracotta block mb-2">
                    {{ content.getBlock('welcome_script', 'Diabetes Care & Nutrition') }}
                </span>
                <h1 class="text-5xl lg:text-6xl text-charcoal mb-6 leading-tight">
                    {{ content.getBlock('welcome_title', 'Nourishing Your Journey') }}
                    <span class="block text-mountain-blue">{{ content.getBlock('welcome_title_accent', 'to Wellness') }}</span>
                </h1>
                <p class="text-lg text-charcoal-light font-light mb-8 max-w-lg mx-auto lg:mx-0">
                    {{ content.getBlock('welcome_description', "Expert guidance in diabetes management and nutrition, rooted in the spirit of the Colorado mountains. Together, we'll find your path to thriving.") }}
                </p>
                <div class="flex flex-wrap gap-6 justify-center lg:justify-start items-center">
                    <a href="#contact" class="btn btn-primary">
                        {{ content.getBlock('welcome_cta_primary', 'Begin Your Journey') }}
                    </a>
                    <a
                        href="#services"
                        class="text-mountain-blue-dark font-semibold text-sm tracking-wide uppercase relative group"
                    >
                        {{ content.getBlock('welcome_cta_secondary', 'Explore Services') }}
                        <span class="inline-block ml-2 transition-transform group-hover:translate-x-1">&rarr;</span>
                        <span class="absolute bottom-0 left-0 w-full h-0.5 bg-gold scale-x-0 group-hover:scale-x-100 transition-transform origin-left"></span>
                    </a>
                </div>
            </div>

            <!-- Image -->
            <div class="relative">
                <div class="relative p-6">
                    <!-- Gold frame accent -->
                    <div class="absolute top-0 left-0 right-8 bottom-8 border-3 border-gold/50"></div>
                    <!-- Photo -->
                    <div class="relative z-10 ml-8 mt-8">
                        <div class="aspect-[3/4] bg-gradient-to-br from-mountain-blue to-mountain-blue-dark overflow-hidden">
                            <img
                                src="/images/maggie.jpg"
                                alt="Maggie Chamberlain, MS, RD, CDCES"
                                class="w-full h-full object-cover object-top"
                            />
                        </div>
                    </div>
                </div>
                <!-- Credential badge -->
                <div class="absolute -bottom-4 -left-4 bg-terracotta text-white py-4 px-6 z-20">
                    <span class="font-script text-xl block">Certified</span>
                    <span class="text-xs tracking-widest uppercase">MS, RD, CDCES</span>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { useContentStore } from '@/stores/content';
const content = useContentStore();
</script>
