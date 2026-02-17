<template>
    <nav class="fixed top-0 left-0 right-0 bg-cream/95 backdrop-blur-sm z-50 px-8 py-4">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <a href="#" class="font-serif text-2xl text-mountain-blue-dark">
                Maggie <span class="font-script text-terracotta">Chamberlain</span>
            </a>
            <ul class="hidden md:flex gap-8 items-center">
                <li v-for="link in navLinks" :key="link.href">
                    <a
                        :href="link.href"
                        class="text-charcoal-light font-medium text-sm tracking-wide uppercase transition-colors hover:text-terracotta"
                    >
                        {{ link.label }}
                    </a>
                </li>
                <li v-if="isAuthenticated">
                    <a
                        href="/admin/dashboard"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-white bg-mountain-blue rounded hover:bg-mountain-blue-dark transition-colors"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Admin
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
                <li v-if="isAuthenticated">
                    <a
                        href="/admin/dashboard"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-white bg-mountain-blue rounded hover:bg-mountain-blue-dark transition-colors"
                        @click="mobileMenuOpen = false"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Admin
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useAuthStore } from '@/stores/auth';

const auth = useAuthStore();
const mobileMenuOpen = ref(false);

const isAuthenticated = computed(() => auth.isAuthenticated);

const navLinks = [
    { href: '/#services', label: 'Services' },
    { href: '/#about', label: 'About' },
    { href: '/#blog', label: 'Journal' },
    { href: '/#contact', label: 'Connect' },
];
</script>
