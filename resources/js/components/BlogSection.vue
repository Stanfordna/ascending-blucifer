<template>
    <section id="blog" class="py-20 px-8 bg-cream">
        <div class="max-w-7xl mx-auto">
            <div class="section-header">
                <span class="script">The Journal</span>
                <h2>Insights & Inspiration</h2>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <article
                    v-for="post in posts"
                    :key="post.title"
                    class="bg-white transition-all duration-300 hover:-translate-y-2 hover:shadow-xl"
                >
                    <!-- Image -->
                    <div
                        class="h-56 relative"
                        :style="{ background: `linear-gradient(135deg, ${post.gradientFrom}, ${post.gradientTo})` }"
                    >
                        <div class="absolute bottom-0 left-8 right-8 h-1 bg-gold"></div>
                    </div>

                    <!-- Content -->
                    <div class="p-8">
                        <span class="font-script text-xl text-terracotta">{{ post.date }}</span>
                        <h3 class="text-2xl mt-2 mb-3 text-charcoal">{{ post.title }}</h3>
                        <p class="text-charcoal-light font-light">{{ post.excerpt }}</p>
                        <a
                            href="#"
                            class="inline-block mt-4 text-mountain-blue font-semibold text-sm tracking-wide uppercase hover:text-terracotta transition-colors"
                        >
                            Read More &rarr;
                        </a>
                    </div>
                </article>
            </div>
        </div>
    </section>
</template>

<script setup>
const posts = [
    {
        date: 'January 2026',
        title: 'The Art of Mindful Eating with Diabetes',
        excerpt: 'Discover how presence and intention can transform your relationship with food and blood sugar.',
        gradientFrom: '#D99A7D',
        gradientTo: '#C4785A',
    },
    {
        date: 'December 2025',
        title: 'Mountain Adventures & Diabetes',
        excerpt: "Tips for managing your glucose while exploring Colorado's beautiful trails and peaks.",
        gradientFrom: '#7A9BB8',
        gradientTo: '#5B7FA4',
    },
    {
        date: 'November 2025',
        title: 'Understanding Your Bionic Pancreas',
        excerpt: "A gentle guide to the technology that's revolutionizing automated insulin delivery.",
        gradientFrom: '#E5C070',
        gradientTo: '#D4A84B',
    },
];
</script>
