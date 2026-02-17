<template>
    <div class="relative" ref="wrapper">
        <button
            type="button"
            @click="open = !open"
            class="select-menu-trigger w-full flex items-center justify-between gap-2 rounded-lg outline-none transition-colors cursor-pointer border"
            :class="[
                sizeClass,
                open
                    ? 'border-mountain-blue ring-1 ring-mountain-blue'
                    : 'border-gray-300 hover:border-gray-400',
            ]"
        >
            <span class="truncate" :class="hasValue ? 'text-charcoal' : 'text-charcoal-light'">
                {{ displayLabel }}
            </span>
            <svg
                class="w-4 h-4 text-charcoal-light shrink-0 transition-transform duration-200"
                :class="{ 'rotate-180': open }"
                fill="none" stroke="currentColor" viewBox="0 0 24 24"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <Transition name="select-drop">
            <div
                v-if="open"
                class="select-menu-dropdown absolute z-50 mt-1 w-full bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden py-1"
            >
                <button
                    v-for="option in normalizedOptions"
                    :key="option.value"
                    type="button"
                    @click="select(option.value)"
                    class="select-menu-option w-full text-left px-4 py-2 text-sm transition-colors cursor-pointer"
                    :class="modelValue === option.value
                        ? 'bg-mountain-blue/10 text-mountain-blue font-medium'
                        : 'text-charcoal hover:bg-gray-50'"
                >
                    {{ option.label }}
                </button>
            </div>
        </Transition>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';

const props = defineProps({
    modelValue: { type: [String, Number, null], default: '' },
    options: { type: Array, required: true },
    placeholder: { type: String, default: 'Select...' },
    size: { type: String, default: 'md' },
});

const emit = defineEmits(['update:modelValue', 'change']);

const open = ref(false);
const wrapper = ref(null);

const sizeClass = computed(() => {
    const sizes = {
        sm: 'pl-3 pr-8 py-1.5 text-sm',
        md: 'pl-4 pr-8 py-2',
        lg: 'pl-4 pr-8 py-2.5',
    };
    return sizes[props.size] || sizes.md;
});

const normalizedOptions = computed(() =>
    props.options.map(opt =>
        typeof opt === 'object' ? opt : { value: opt, label: String(opt) }
    )
);

const hasValue = computed(() => props.modelValue !== '' && props.modelValue !== null && props.modelValue !== undefined);

const displayLabel = computed(() => {
    if (!hasValue.value) return props.placeholder;
    const match = normalizedOptions.value.find(o => o.value === props.modelValue);
    return match ? match.label : String(props.modelValue);
});

function select(value) {
    emit('update:modelValue', value);
    emit('change', value);
    open.value = false;
}

function handleClickOutside(e) {
    if (wrapper.value && !wrapper.value.contains(e.target)) {
        open.value = false;
    }
}

onMounted(() => document.addEventListener('click', handleClickOutside));
onUnmounted(() => document.removeEventListener('click', handleClickOutside));
</script>

<style scoped>
.select-drop-enter-active,
.select-drop-leave-active {
    transition: opacity 0.15s ease, transform 0.15s ease;
}
.select-drop-enter-from,
.select-drop-leave-to {
    opacity: 0;
    transform: translateY(-4px);
}
</style>
