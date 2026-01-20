<template>
    <section
        id="contact"
        class="py-24 px-8 text-center text-white relative"
        style="background: linear-gradient(rgba(196, 120, 90, 0.95), rgba(196, 120, 90, 0.95)), url('data:image/svg+xml,%3Csvg width=\'40\' height=\'40\' viewBox=\'0 0 40 40\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cpath d=\'M20 0L40 20L20 40L0 20z\' fill=\'white\' opacity=\'0.05\'/%3E%3C/svg%3E')"
    >
        <span class="font-script text-3xl text-gold-light block mb-2">
            {{ content.getBlock('contact_script', "Let's Connect") }}
        </span>
        <h2 class="text-4xl lg:text-5xl mb-6">
            {{ content.getBlock('contact_title', 'Ready to Begin?') }}
        </h2>
        <p class="max-w-xl mx-auto text-lg font-light opacity-95 mb-8">
            {{ content.getBlock('contact_description', "Your journey to better health starts with a conversation. Schedule a consultation and let's explore what's possible together.") }}
        </p>
        <a href="#" class="btn btn-white">
            {{ content.getBlock('contact_button', 'Schedule Your Consultation') }}
        </a>
    </section>
</template>

<script setup>
import { useContentStore } from '@/stores/content';
const content = useContentStore();
</script>
