<template>
    <div>
        <label v-if="label" class="block text-sm font-medium text-charcoal mb-1">{{ label }}</label>

        <div class="flex items-center gap-2">
            <!-- Color display box with hover pane -->
            <div class="relative" @mouseleave="showPresets = false">
                <div
                    class="w-10 h-10 rounded-lg border border-gray-300 cursor-pointer transition-shadow hover:shadow-md"
                    :style="{ backgroundColor: modelValue || '#ffffff' }"
                    @mouseenter="showPresets = true"
                />

                <!-- Preset pane dropdown (pt-2 on wrapper bridges the gap so mouseleave doesn't fire) -->
                <div v-if="showPresets" class="absolute top-full left-0 pt-2 z-50">
                <Transition name="fade">
                    <div
                        v-if="showPresets"
                        class="bg-white border border-gray-200 rounded-lg shadow-lg p-2 w-56"
                    >
                        <div class="grid grid-cols-3 gap-1">
                            <button
                                v-for="preset in presets"
                                :key="preset.hex"
                                type="button"
                                @click="selectPreset(preset.hex)"
                                class="flex flex-col items-center gap-0.5 p-1.5 rounded hover:bg-gray-50 transition-colors"
                                :class="isActive(preset.hex) ? 'ring-2 ring-mountain-blue ring-offset-1' : ''"
                            >
                                <div
                                    class="w-full h-6 rounded border border-gray-200"
                                    :style="{ backgroundColor: preset.hex }"
                                />
                                <span class="text-[10px] text-charcoal-light leading-tight text-center">{{ preset.name }}</span>
                            </button>
                        </div>
                    </div>
                </Transition>
                </div>
            </div>

            <!-- Hex text field with eyedropper -->
            <div class="relative flex-1">
                <input
                    :value="modelValue"
                    @input="$emit('update:modelValue', $event.target.value)"
                    type="text"
                    class="w-full px-3 py-2 pr-9 border border-gray-300 rounded-lg focus:border-mountain-blue focus:ring-1 focus:ring-mountain-blue outline-none font-mono text-sm"
                    placeholder="#000000"
                />
                <button
                    type="button"
                    @click="nativeColor.click()"
                    class="absolute right-2 top-1/2 -translate-y-1/2 text-charcoal-light hover:text-charcoal transition-colors cursor-pointer"
                    title="Pick color"
                >
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 22l1-1h3l9-9" />
                        <path d="M3 21v-3l9-9" />
                        <path d="M15 6l3-3a2.121 2.121 0 013 3l-3 3" />
                        <path d="M14.5 5.5l4 4" />
                    </svg>
                </button>
                <input
                    ref="nativeColor"
                    :value="modelValue || '#000000'"
                    @input="$emit('update:modelValue', $event.target.value)"
                    type="color"
                    class="sr-only"
                />
            </div>

            <!-- Clear button -->
            <button
                v-if="modelValue"
                type="button"
                @click="$emit('update:modelValue', '')"
                class="p-1.5 text-charcoal-light hover:text-terracotta transition-colors"
                title="Clear color"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>
</template>

<script>
export const presets = [
    { name: 'Mountain Blue', hex: '#5B7FA4' },
    { name: 'Terracotta', hex: '#C4785A' },
    { name: 'Gold', hex: '#D4A84B' },
    { name: 'Alpine Green', hex: '#4A7C59' },
    { name: 'Sunset Rose', hex: '#C45A6E' },
    { name: 'Slate', hex: '#5A6978' },
    { name: 'Sage', hex: '#8FA88A' },
    { name: 'Copper', hex: '#B87333' },
    { name: 'Dusk Purple', hex: '#7A5BA4' },
    { name: 'Sandstone', hex: '#C4A45A' },
    { name: 'Cranberry', hex: '#8A3A4A' },
    { name: 'Sky', hex: '#6BA4C4' },
];
</script>

<script setup>
import { ref } from 'vue';

const props = defineProps({
    modelValue: String,
    label: String,
});

const emit = defineEmits(['update:modelValue']);

const showPresets = ref(false);
const nativeColor = ref(null);

function selectPreset(hex) {
    emit('update:modelValue', hex);
    showPresets.value = false;
}

function isActive(hex) {
    return props.modelValue?.toLowerCase() === hex.toLowerCase();
}
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.15s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
