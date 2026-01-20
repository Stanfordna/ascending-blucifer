<template>
    <div class="min-h-screen bg-cream">
        <Navigation />
        <main>
            <EditableSection section="welcome">
                <HeroSection />
            </EditableSection>
            <Divider />
            <EditableSection section="services" link="/admin/services">
                <ServicesSection />
            </EditableSection>
            <EditableSection section="about">
                <AboutSection />
            </EditableSection>
            <EditableSection section="blog" link="/admin/blog">
                <BlogSection />
            </EditableSection>
            <EditableSection section="contact">
                <CtaSection />
            </EditableSection>
        </main>
        <EditableSection section="footer">
            <FooterSection />
        </EditableSection>
    </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { useContentStore } from '@/stores/content';
import { useAuthStore } from '@/stores/auth';
import Navigation from './Navigation.vue';
import HeroSection from './HeroSection.vue';
import Divider from './Divider.vue';
import ServicesSection from './ServicesSection.vue';
import AboutSection from './AboutSection.vue';
import BlogSection from './BlogSection.vue';
import CtaSection from './CtaSection.vue';
import FooterSection from './FooterSection.vue';
import EditableSection from './EditableSection.vue';

const contentStore = useContentStore();
const authStore = useAuthStore();

onMounted(async () => {
    // Fetch content blocks for dynamic content
    await contentStore.fetchBlocks();

    // Check if user is authenticated (for edit-in-place features)
    await authStore.checkAuth();
});
</script>
