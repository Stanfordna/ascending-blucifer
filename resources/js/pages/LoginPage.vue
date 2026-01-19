<template>
    <div class="min-h-screen bg-cream flex items-center justify-center px-4">
        <div class="max-w-md w-full">
            <!-- Logo/Brand -->
            <div class="text-center mb-8">
                <h1 class="font-serif text-3xl text-charcoal">
                    Maggie <span class="font-script text-gold">Chamberlain</span>
                </h1>
                <p class="text-charcoal-light mt-2">Admin Login</p>
            </div>

            <!-- Login Form -->
            <form
                @submit.prevent="handleSubmit"
                class="bg-white p-8 border border-charcoal/10"
            >
                <!-- Error Message -->
                <div
                    v-if="auth.error"
                    class="mb-6 p-4 bg-terracotta/10 border border-terracotta text-terracotta text-sm"
                >
                    {{ auth.error }}
                </div>

                <!-- Email -->
                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-charcoal mb-2">
                        Email Address
                    </label>
                    <input
                        id="email"
                        v-model="form.email"
                        type="email"
                        required
                        autocomplete="email"
                        class="w-full px-4 py-3 border border-charcoal/20 focus:border-mountain-blue focus:ring-1 focus:ring-mountain-blue outline-none transition-colors"
                        :disabled="auth.loading"
                    />
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-charcoal mb-2">
                        Password
                    </label>
                    <input
                        id="password"
                        v-model="form.password"
                        type="password"
                        required
                        autocomplete="current-password"
                        class="w-full px-4 py-3 border border-charcoal/20 focus:border-mountain-blue focus:ring-1 focus:ring-mountain-blue outline-none transition-colors"
                        :disabled="auth.loading"
                    />
                </div>

                <!-- Remember Me -->
                <div class="mb-6">
                    <label class="flex items-center">
                        <input
                            v-model="form.remember"
                            type="checkbox"
                            class="w-4 h-4 border-charcoal/20 text-mountain-blue focus:ring-mountain-blue"
                        />
                        <span class="ml-2 text-sm text-charcoal-light">Remember me</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full btn btn-primary"
                    :disabled="auth.loading"
                >
                    <span v-if="auth.loading">Signing in...</span>
                    <span v-else>Sign In</span>
                </button>
            </form>

            <!-- Back to site -->
            <div class="mt-6 text-center">
                <router-link to="/" class="text-sm text-charcoal-light hover:text-mountain-blue transition-colors">
                    &larr; Back to site
                </router-link>
            </div>
        </div>
    </div>
</template>

<script setup>
import { reactive } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useAuthStore } from '@/stores/auth';

const router = useRouter();
const route = useRoute();
const auth = useAuthStore();

const form = reactive({
    email: '',
    password: '',
    remember: false,
});

async function handleSubmit() {
    const success = await auth.login(form.email, form.password, form.remember);
    if (success) {
        const redirect = route.query.redirect || '/admin/dashboard';
        router.push(redirect);
    }
}
</script>
