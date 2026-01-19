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
import Navigation from './Navigation.vue';
import HeroSection from './HeroSection.vue';
import Divider from './Divider.vue';
import ServicesSection from './ServicesSection.vue';
import AboutSection from './AboutSection.vue';
import BlogSection from './BlogSection.vue';
import CtaSection from './CtaSection.vue';
import FooterSection from './FooterSection.vue';
</script>
