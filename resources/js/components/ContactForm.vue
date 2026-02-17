<template>
    <div class="max-w-xl mx-auto mt-10">
        <!-- Loading -->
        <div v-if="loading" class="flex justify-center py-8">
            <div class="animate-spin w-8 h-8 border-2 border-white border-t-transparent rounded-full"></div>
        </div>

        <!-- Success -->
        <div v-else-if="submitted" class="text-center py-8">
            <svg class="w-16 h-16 text-gold-light mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-lg font-light">{{ config.success_message }}</p>
        </div>

        <!-- Form -->
        <form v-else @submit.prevent="submitForm" class="space-y-5">
            <div class="text-center mb-6">
                <h3 class="text-2xl font-serif mb-2">{{ config.heading }}</h3>
                <p class="font-light opacity-90 text-sm">{{ config.description }}</p>
            </div>

            <!-- Name -->
            <div>
                <label for="contact-name" class="block text-sm font-medium mb-1">Name *</label>
                <input
                    id="contact-name"
                    v-model="form.name"
                    type="text"
                    required
                    class="w-full px-4 py-2.5 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/50 focus:border-gold-light focus:ring-1 focus:ring-gold-light outline-none"
                    placeholder="Your name"
                />
            </div>

            <!-- Email -->
            <div>
                <label for="contact-email" class="block text-sm font-medium mb-1">Email *</label>
                <input
                    id="contact-email"
                    v-model="form.email"
                    type="email"
                    required
                    class="w-full px-4 py-2.5 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/50 focus:border-gold-light focus:ring-1 focus:ring-gold-light outline-none"
                    placeholder="your@email.com"
                />
            </div>

            <!-- Phone -->
            <div v-if="config.show_phone">
                <label for="contact-phone" class="block text-sm font-medium mb-1">Phone</label>
                <input
                    id="contact-phone"
                    v-model="form.phone"
                    type="tel"
                    class="w-full px-4 py-2.5 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/50 focus:border-gold-light focus:ring-1 focus:ring-gold-light outline-none"
                    placeholder="(555) 123-4567"
                />
            </div>

            <!-- Service Selection -->
            <div v-if="config.show_services && config.services?.length">
                <label for="contact-service" class="block text-sm font-medium mb-1">{{ config.service_label }}</label>
                <select
                    id="contact-service"
                    v-model="form.service"
                    class="w-full px-4 py-2.5 bg-white/10 border border-white/20 rounded-lg text-white focus:border-gold-light focus:ring-1 focus:ring-gold-light outline-none"
                >
                    <option value="" class="text-charcoal">Select a service...</option>
                    <option
                        v-for="svc in config.services"
                        :key="svc.id"
                        :value="svc.title"
                        class="text-charcoal"
                    >
                        {{ svc.title }}
                    </option>
                </select>
            </div>

            <!-- Availability -->
            <div v-if="config.show_availability && config.booking_enabled">
                <label for="contact-availability" class="block text-sm font-medium mb-1">{{ config.availability_label }}</label>
                <textarea
                    id="contact-availability"
                    v-model="form.availability"
                    rows="2"
                    class="w-full px-4 py-2.5 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/50 focus:border-gold-light focus:ring-1 focus:ring-gold-light outline-none resize-none"
                    placeholder="e.g. Weekday mornings, Tuesday/Thursday afternoons"
                ></textarea>
            </div>

            <!-- Message -->
            <div>
                <label for="contact-message" class="block text-sm font-medium mb-1">{{ config.message_label }} *</label>
                <textarea
                    id="contact-message"
                    v-model="form.message"
                    rows="4"
                    required
                    class="w-full px-4 py-2.5 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/50 focus:border-gold-light focus:ring-1 focus:ring-gold-light outline-none resize-none"
                    placeholder="Tell me about your health goals..."
                ></textarea>
            </div>

            <!-- Honeypot (hidden) -->
            <input v-model="form.honeypot_field" type="text" class="sr-only" tabindex="-1" autocomplete="off" aria-hidden="true" />

            <!-- Error -->
            <p v-if="errorMsg" class="text-gold-light text-sm">{{ errorMsg }}</p>

            <!-- Submit -->
            <button
                type="submit"
                :disabled="submitting"
                class="w-full py-3 bg-white text-terracotta font-semibold rounded-lg hover:bg-white/90 transition-colors disabled:opacity-50"
            >
                {{ submitting ? 'Sending...' : 'Send Message' }}
            </button>
        </form>

        <!-- Mailto fallback -->
        <div v-if="!submitted && config.contact_email" class="text-center mt-4">
            <p class="text-sm opacity-80">
                Or
                <a
                    :href="mailtoLink"
                    class="underline hover:text-gold-light transition-colors"
                >
                    send an email directly
                </a>
            </p>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import api from '@/services/api';

const loading = ref(true);
const submitting = ref(false);
const submitted = ref(false);
const errorMsg = ref('');
const startTime = ref(null);

const config = ref({
    heading: 'Get in Touch',
    description: '',
    success_message: 'Thank you!',
    show_services: false,
    show_availability: false,
    show_phone: false,
    booking_enabled: false,
    service_label: 'Which service are you interested in?',
    availability_label: 'Preferred availability',
    message_label: 'How can I help you?',
    email_subject: 'Consultation Inquiry',
    email_body: '',
    contact_email: '',
    services: [],
});

const form = ref({
    name: '',
    email: '',
    phone: '',
    service: '',
    availability: '',
    message: '',
    honeypot_field: '',
});

const mailtoLink = computed(() => {
    const subject = encodeURIComponent(config.value.email_subject);
    const body = encodeURIComponent(config.value.email_body);
    return `mailto:${config.value.contact_email}?subject=${subject}&body=${body}`;
});

async function fetchConfig() {
    try {
        const response = await api.get('/contact-config');
        config.value = response.data;
    } catch (e) {
        // Use defaults
    } finally {
        loading.value = false;
        startTime.value = Date.now();
    }
}

async function submitForm() {
    submitting.value = true;
    errorMsg.value = '';

    const seconds = Math.floor((Date.now() - startTime.value) / 1000);

    try {
        await api.post('/contact', {
            ...form.value,
            submission_time_seconds: seconds,
        });
        submitted.value = true;
    } catch (e) {
        if (e.response?.data?.errors) {
            const firstError = Object.values(e.response.data.errors)[0];
            errorMsg.value = Array.isArray(firstError) ? firstError[0] : firstError;
        } else {
            errorMsg.value = 'Something went wrong. Please try again.';
        }
    } finally {
        submitting.value = false;
    }
}

onMounted(() => {
    fetchConfig();
});
</script>
