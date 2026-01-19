<template>
    <nav class="fixed top-0 left-0 right-0 bg-cream/95 backdrop-blur-sm z-50 px-8 py-4">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <a href="#" class="font-serif text-2xl text-mountain-blue-dark">
                Maggie <span class="font-script text-terracotta">Chamberlain</span>
            </a>
            <ul class="hidden md:flex gap-8">
                <li v-for="link in navLinks" :key="link.href">
                    <a
                        :href="link.href"
                        class="text-charcoal-light font-medium text-sm tracking-wide uppercase transition-colors hover:text-terracotta"
                    >
                        {{ link.label }}
                    </a>
                </li>
            </ul>
            <!-- Mobile menu button -->
            <button
                @click="mobileMenuOpen = !mobileMenuOpen"
                class="md:hidden text-charcoal p-2"
                aria-label="Toggle menu"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                        v-if="!mobileMenuOpen"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16"
                    />
                    <path
                        v-else
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"
                    />
                </svg>
            </button>
        </div>
        <!-- Mobile menu -->
        <div v-if="mobileMenuOpen" class="md:hidden mt-4 pb-4">
            <ul class="flex flex-col gap-4">
                <li v-for="link in navLinks" :key="link.href">
                    <a
                        :href="link.href"
                        class="block text-charcoal-light font-medium text-sm tracking-wide uppercase transition-colors hover:text-terracotta"
                        @click="mobileMenuOpen = false"
                    >
                        {{ link.label }}
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</template>

<script setup>
import { ref } from 'vue';

const mobileMenuOpen = ref(false);

const navLinks = [
    { href: '#services', label: 'Services' },
    { href: '#about', label: 'About' },
    { href: '#blog', label: 'Journal' },
    { href: '#contact', label: 'Connect' },
];
</script>
