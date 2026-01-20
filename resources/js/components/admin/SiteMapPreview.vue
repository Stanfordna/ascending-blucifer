<template>
    <div class="site-map-preview bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
        <h3 class="text-xs font-medium text-charcoal-light uppercase tracking-wide mb-3">Page Layout</h3>
        <div class="space-y-2">
            <div
                v-for="section in sections"
                :key="section.key"
                @click="handleClick(section)"
                @mouseenter="hoveredSection = section.key"
                @mouseleave="hoveredSection = null"
                class="section-block px-4 py-3 rounded-md cursor-pointer transition-all duration-200 flex items-center justify-between"
                :class="[
                    activeSection === section.key
                        ? 'bg-white border-l-4 border-gold shadow-sm'
                        : 'bg-white/50 border-l-4 border-transparent hover:bg-white hover:shadow-sm',
                    hoveredSection === section.key && activeSection !== section.key
                        ? 'border-l-mountain-blue'
                        : ''
                ]"
            >
                <div class="flex items-center gap-3">
                    <span
                        class="text-sm font-medium"
                        :class="activeSection === section.key ? 'text-charcoal' : 'text-charcoal-light'"
                    >
                        {{ section.label }}
                    </span>
                </div>
                <span
                    v-if="section.editable"
                    class="text-xs text-charcoal-light"
                    :class="activeSection === section.key ? 'text-mountain-blue' : ''"
                >
                    ✎
                </span>
                <span
                    v-else
                    class="text-xs text-charcoal-light"
                >
                    →
                </span>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';

const props = defineProps({
    sections: {
        type: Array,
        required: true,
        // Array of { key, label, editable, link? }
    },
    activeSection: {
        type: String,
        default: null,
    },
});

const emit = defineEmits(['select']);

const hoveredSection = ref(null);

function handleClick(section) {
    if (section.editable) {
        emit('select', section.key);
    } else if (section.link) {
        window.location.href = section.link;
    }
}
</script>

<style scoped>
.section-block {
    min-height: 44px;
}
</style>
