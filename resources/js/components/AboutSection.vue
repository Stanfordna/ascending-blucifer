<template>
    <section id="about" class="py-20 px-8 bg-mountain-blue text-white relative overflow-hidden">
        <!-- Background pattern -->
        <div class="absolute inset-0 opacity-5">
            <div
                class="w-full h-full"
                style="background-image: url('data:image/svg+xml,%3Csvg width=\'100\' height=\'100\' viewBox=\'0 0 100 100\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Ccircle cx=\'50\' cy=\'50\' r=\'40\' stroke=\'white\' stroke-width=\'0.5\' fill=\'none\'/%3E%3C/svg%3E')"
            ></div>
        </div>

        <div class="max-w-7xl mx-auto grid lg:grid-cols-2 gap-20 items-center relative">
            <!-- Images -->
            <div class="relative">
                <div class="w-4/5 aspect-[4/3] bg-mountain-blue-light border-4 border-white overflow-hidden">
                    <img
                        src="/images/mountain-goats.jpg"
                        alt="Colorado Mountain Goats"
                        class="w-full h-full object-cover"
                    />
                </div>
                <div class="absolute -bottom-8 right-0 w-3/5 aspect-[4/3] bg-mountain-blue-dark border-4 border-gold overflow-hidden">
                    <img
                        src="/images/mountain-goats.jpg"
                        alt="Colorado Mountains"
                        class="w-full h-full object-cover object-right"
                    />
                </div>
            </div>

            <!-- Content -->
            <div>
                <span class="font-script text-3xl text-gold-light block mb-2">About Maggie</span>
                <h2 class="text-4xl lg:text-5xl mb-8">A Colorado Heart, A Healing Mission</h2>
                <p class="font-light opacity-90 mb-6 leading-relaxed">
                    With the determination of Colorado's mountain goats and over seven years of clinical
                    experience, I've dedicated my career to helping people with diabetes discover what's
                    possible for their health.
                </p>
                <p class="font-light opacity-90 mb-8 leading-relaxed">
                    From hospital bedside to cutting-edge medical device innovation with Beta Bionics and
                    Tandem Diabetes Care, my journey has always been guided by one mission: empowering
                    individuals to take control of their health with confidence and joy.
                </p>

                <!-- Credentials -->
                <div class="flex flex-wrap gap-3">
                    <span
                        v-for="credential in credentials"
                        :key="credential"
                        class="bg-white/10 px-5 py-2 text-sm tracking-wide border border-white/20"
                    >
                        {{ credential }}
                    </span>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
const credentials = [
    'Master of Science',
    'Registered Dietitian',
    'CDCES Certified',
    'Louisiana Tech University',
    'MSU Denver',
];
</script>
