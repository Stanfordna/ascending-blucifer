<template>
    <Modal
        :model-value="modelValue"
        @update:model-value="$emit('update:modelValue', $event)"
        size="sm"
        :close-on-backdrop="false"
    >
        <div class="text-center">
            <!-- Icon -->
            <div
                class="mx-auto w-12 h-12 rounded-full flex items-center justify-center mb-4"
                :class="iconClasses"
            >
                <svg v-if="type === 'danger'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <svg v-else-if="type === 'warning'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>

            <!-- Title -->
            <h3 class="text-lg font-semibold text-charcoal mb-2">{{ title }}</h3>

            <!-- Message -->
            <p class="text-charcoal-light">{{ message }}</p>
        </div>

        <template #footer>
            <div class="flex justify-end gap-3">
                <button
                    @click="$emit('update:modelValue', false)"
                    class="px-4 py-2 text-sm font-medium text-charcoal-light hover:text-charcoal border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
                    :disabled="loading"
                >
                    {{ cancelText }}
                </button>
                <button
                    @click="$emit('confirm')"
                    class="px-4 py-2 text-sm font-medium text-white rounded-lg transition-colors disabled:opacity-50"
                    :class="confirmClasses"
                    :disabled="loading"
                >
                    <span v-if="loading" class="flex items-center gap-2">
                        <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Processing...
                    </span>
                    <span v-else>{{ confirmText }}</span>
                </button>
            </div>
        </template>
    </Modal>
</template>

<script setup>
import { computed } from 'vue';
import Modal from './Modal.vue';

const props = defineProps({
    modelValue: {
        type: Boolean,
        default: false,
    },
    type: {
        type: String,
        default: 'danger',
        validator: (v) => ['danger', 'warning', 'info'].includes(v),
    },
    title: {
        type: String,
        default: 'Are you sure?',
    },
    message: {
        type: String,
        default: 'This action cannot be undone.',
    },
    confirmText: {
        type: String,
        default: 'Confirm',
    },
    cancelText: {
        type: String,
        default: 'Cancel',
    },
    loading: {
        type: Boolean,
        default: false,
    },
});

defineEmits(['update:modelValue', 'confirm']);

const iconClasses = computed(() => {
    const classes = {
        danger: 'bg-terracotta/10 text-terracotta',
        warning: 'bg-gold/10 text-gold',
        info: 'bg-mountain-blue/10 text-mountain-blue',
    };
    return classes[props.type];
});

const confirmClasses = computed(() => {
    const classes = {
        danger: 'bg-terracotta hover:bg-terracotta/90',
        warning: 'bg-gold hover:bg-gold/90',
        info: 'bg-mountain-blue hover:bg-mountain-blue/90',
    };
    return classes[props.type];
});
</script>
