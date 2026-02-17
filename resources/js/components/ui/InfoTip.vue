<template>
    <div class="relative inline-block">
        <button
            type="button"
            @click="isOpen = !isOpen"
            @blur="isOpen = false"
            class="p-1 rounded-full hover:bg-gray-100 text-charcoal-light hover:text-mountain-blue transition-colors"
            :title="title"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </button>

        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0 translate-y-1"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100 translate-y-0"
            leave-to-class="opacity-0 translate-y-1"
        >
            <div
                v-if="isOpen"
                class="absolute z-50 w-72 p-4 bg-charcoal text-white rounded-lg shadow-lg text-sm"
                :class="positionClasses"
            >
                <div class="font-medium mb-2">{{ title }}</div>
                <div class="text-white/80 space-y-2">
                    <slot></slot>
                </div>
                <div class="absolute w-3 h-3 bg-charcoal transform rotate-45" :class="arrowClasses"></div>
            </div>
        </Transition>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
    title: {
        type: String,
        default: 'Info',
    },
    position: {
        type: String,
        default: 'bottom', // 'top', 'bottom', 'left', 'right'
    },
});

const isOpen = ref(false);

const positionClasses = computed(() => {
    switch (props.position) {
        case 'top':
            return 'bottom-full left-1/2 -translate-x-1/2 mb-2';
        case 'left':
            return 'right-full top-1/2 -translate-y-1/2 mr-2';
        case 'right':
            return 'left-full top-1/2 -translate-y-1/2 ml-2';
        default:
            return 'top-full left-1/2 -translate-x-1/2 mt-2';
    }
});

const arrowClasses = computed(() => {
    switch (props.position) {
        case 'top':
            return 'top-full left-1/2 -translate-x-1/2 -mt-1.5';
        case 'left':
            return 'left-full top-1/2 -translate-y-1/2 -ml-1.5';
        case 'right':
            return 'right-full top-1/2 -translate-y-1/2 -mr-1.5';
        default:
            return 'bottom-full left-1/2 -translate-x-1/2 -mb-1.5';
    }
});
</script>
