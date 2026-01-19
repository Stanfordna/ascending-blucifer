<template>
    <div class="min-h-screen bg-cream">
        <Navigation />
        <main>
            <HeroSection />
            <Divider />
            <ServicesSection />
            <AboutSection />
            <BlogSection />
            <CtaSection />
        </main>
        <FooterSection />
    </div>
</template>

<script setup>
import Navigation from './components/Navigation.vue';
import HeroSection from './components/HeroSection.vue';
import Divider from './components/Divider.vue';
import ServicesSection from './components/ServicesSection.vue';
import AboutSection from './components/AboutSection.vue';
import BlogSection from './components/BlogSection.vue';
import CtaSection from './components/CtaSection.vue';
import FooterSection from './components/FooterSection.vue';
</script>
