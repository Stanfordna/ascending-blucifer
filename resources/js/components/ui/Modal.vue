<template>
    <Teleport to="body">
        <Transition name="modal">
            <div
                v-if="modelValue"
                class="fixed inset-0 z-50 flex items-center justify-center p-4"
                @click.self="closeOnBackdrop && close()"
            >
                <!-- Backdrop -->
                <div class="absolute inset-0 bg-black/50"></div>

                <!-- Modal Content -->
                <div
                    class="relative bg-white rounded-lg shadow-xl w-full max-h-[90vh] overflow-hidden flex flex-col"
                    :class="sizeClasses"
                >
                    <!-- Header -->
                    <div v-if="title || $slots.header" class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                        <slot name="header">
                            <h3 class="text-lg font-semibold text-charcoal">{{ title }}</h3>
                        </slot>
                        <button
                            v-if="showClose"
                            @click="close"
                            class="p-1 hover:bg-gray-100 rounded transition-colors"
                        >
                            <svg class="w-5 h-5 text-charcoal-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="flex-1 overflow-y-auto p-6">
                        <slot></slot>
                    </div>

                    <!-- Footer -->
                    <div v-if="$slots.footer" class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                        <slot name="footer"></slot>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
import { computed, watch, onMounted, onUnmounted } from 'vue';

const props = defineProps({
    modelValue: {
        type: Boolean,
        default: false,
    },
    title: {
        type: String,
        default: '',
    },
    size: {
        type: String,
        default: 'md',
        validator: (v) => ['sm', 'md', 'lg', 'xl', 'full'].includes(v),
    },
    showClose: {
        type: Boolean,
        default: true,
    },
    closeOnBackdrop: {
        type: Boolean,
        default: true,
    },
    closeOnEscape: {
        type: Boolean,
        default: true,
    },
});

const emit = defineEmits(['update:modelValue', 'close']);

const sizeClasses = computed(() => {
    const sizes = {
        sm: 'max-w-sm',
        md: 'max-w-lg',
        lg: 'max-w-2xl',
        xl: 'max-w-4xl',
        full: 'max-w-[90vw]',
    };
    return sizes[props.size];
});

function close() {
    emit('update:modelValue', false);
    emit('close');
}

function handleEscape(e) {
    if (e.key === 'Escape' && props.closeOnEscape && props.modelValue) {
        close();
    }
}

watch(() => props.modelValue, (isOpen) => {
    if (isOpen) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = '';
    }
});

onMounted(() => {
    document.addEventListener('keydown', handleEscape);
});

onUnmounted(() => {
    document.removeEventListener('keydown', handleEscape);
    document.body.style.overflow = '';
});
</script>

<style scoped>
.modal-enter-active,
.modal-leave-active {
    transition: opacity 0.2s ease;
}

.modal-enter-active .relative,
.modal-leave-active .relative {
    transition: transform 0.2s ease;
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
}

.modal-enter-from .relative,
.modal-leave-to .relative {
    transform: scale(0.95) translateY(-10px);
}
</style>
