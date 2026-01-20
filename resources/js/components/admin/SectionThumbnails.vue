<template>
    <div class="section-thumbnails">
        <div
            v-for="section in sections"
            :key="section.key"
            @click="handleClick(section)"
            class="thumbnail-wrapper cursor-pointer transition-all duration-200"
            :class="[
                activeSection === section.key ? 'ring-2 ring-gold ring-offset-2' : 'hover:ring-2 hover:ring-mountain-blue/50 hover:ring-offset-1',
                !section.editable && 'opacity-60'
            ]"
        >
            <!-- Welcome Banner Thumbnail -->
            <svg v-if="section.key === 'welcome'" viewBox="0 0 200 120" class="w-full h-auto rounded shadow-sm">
                <rect width="200" height="120" fill="#FDF8F3"/>
                <!-- Background blobs -->
                <circle cx="50" cy="30" r="25" fill="#D4A853" opacity="0.1"/>
                <circle cx="150" cy="90" r="30" fill="#4A6FA5" opacity="0.1"/>
                <!-- Content area -->
                <g transform="translate(15, 25)">
                    <rect x="0" y="0" width="40" height="4" rx="2" fill="#C4785A" opacity="0.7"/>
                    <rect x="0" y="10" width="70" height="8" rx="2" fill="#2D3748"/>
                    <rect x="0" y="22" width="55" height="8" rx="2" fill="#4A6FA5"/>
                    <rect x="0" y="38" width="80" height="3" rx="1" fill="#2D3748" opacity="0.4"/>
                    <rect x="0" y="44" width="65" height="3" rx="1" fill="#2D3748" opacity="0.4"/>
                    <rect x="0" y="56" width="45" height="10" rx="2" fill="#4A6FA5"/>
                </g>
                <!-- Photo placeholder -->
                <g transform="translate(115, 15)">
                    <rect x="0" y="0" width="70" height="90" fill="#4A6FA5" opacity="0.3"/>
                    <rect x="5" y="5" width="60" height="80" fill="#4A6FA5" opacity="0.5"/>
                </g>
            </svg>

            <!-- Services Thumbnail -->
            <svg v-else-if="section.key === 'services'" viewBox="0 0 200 80" class="w-full h-auto rounded shadow-sm">
                <rect width="200" height="80" fill="#FDF8F3"/>
                <!-- Service cards -->
                <g transform="translate(10, 15)">
                    <rect x="0" y="0" width="55" height="50" rx="3" fill="white" stroke="#E5E7EB" stroke-width="1"/>
                    <rect x="10" y="10" width="35" height="4" rx="1" fill="#2D3748" opacity="0.6"/>
                    <rect x="10" y="18" width="40" height="2" rx="1" fill="#2D3748" opacity="0.3"/>
                    <rect x="10" y="22" width="30" height="2" rx="1" fill="#2D3748" opacity="0.3"/>
                </g>
                <g transform="translate(72, 15)">
                    <rect x="0" y="0" width="55" height="50" rx="3" fill="white" stroke="#E5E7EB" stroke-width="1"/>
                    <rect x="10" y="10" width="35" height="4" rx="1" fill="#2D3748" opacity="0.6"/>
                    <rect x="10" y="18" width="40" height="2" rx="1" fill="#2D3748" opacity="0.3"/>
                    <rect x="10" y="22" width="30" height="2" rx="1" fill="#2D3748" opacity="0.3"/>
                </g>
                <g transform="translate(134, 15)">
                    <rect x="0" y="0" width="55" height="50" rx="3" fill="white" stroke="#E5E7EB" stroke-width="1"/>
                    <rect x="10" y="10" width="35" height="4" rx="1" fill="#2D3748" opacity="0.6"/>
                    <rect x="10" y="18" width="40" height="2" rx="1" fill="#2D3748" opacity="0.3"/>
                    <rect x="10" y="22" width="30" height="2" rx="1" fill="#2D3748" opacity="0.3"/>
                </g>
                <!-- Link indicator -->
                <text x="100" y="75" text-anchor="middle" font-size="6" fill="#4A6FA5" opacity="0.7">Manage Services →</text>
            </svg>

            <!-- About Thumbnail -->
            <svg v-else-if="section.key === 'about'" viewBox="0 0 200 100" class="w-full h-auto rounded shadow-sm">
                <rect width="200" height="100" fill="#4A6FA5"/>
                <!-- Photo placeholders -->
                <g transform="translate(10, 15)">
                    <rect x="0" y="0" width="60" height="45" fill="white" opacity="0.2"/>
                    <rect x="25" y="30" width="50" height="40" fill="#D4A853" opacity="0.3" stroke="#D4A853" stroke-width="2"/>
                </g>
                <!-- Content -->
                <g transform="translate(100, 20)">
                    <rect x="0" y="0" width="30" height="4" rx="2" fill="#D4A853" opacity="0.8"/>
                    <rect x="0" y="10" width="85" height="6" rx="2" fill="white" opacity="0.9"/>
                    <rect x="0" y="22" width="80" height="3" rx="1" fill="white" opacity="0.5"/>
                    <rect x="0" y="28" width="70" height="3" rx="1" fill="white" opacity="0.5"/>
                    <rect x="0" y="34" width="75" height="3" rx="1" fill="white" opacity="0.5"/>
                    <!-- Credential badges -->
                    <rect x="0" y="48" width="25" height="8" rx="2" fill="white" opacity="0.2"/>
                    <rect x="28" y="48" width="30" height="8" rx="2" fill="white" opacity="0.2"/>
                    <rect x="61" y="48" width="25" height="8" rx="2" fill="white" opacity="0.2"/>
                </g>
            </svg>

            <!-- Blog Thumbnail -->
            <svg v-else-if="section.key === 'blog'" viewBox="0 0 200 70" class="w-full h-auto rounded shadow-sm">
                <rect width="200" height="70" fill="#FDF8F3"/>
                <!-- Blog post cards -->
                <g transform="translate(10, 10)">
                    <rect x="0" y="0" width="55" height="50" rx="3" fill="white" stroke="#E5E7EB" stroke-width="1"/>
                    <rect x="5" y="5" width="45" height="20" rx="2" fill="#4A6FA5" opacity="0.2"/>
                    <rect x="5" y="30" width="40" height="3" rx="1" fill="#2D3748" opacity="0.6"/>
                    <rect x="5" y="36" width="45" height="2" rx="1" fill="#2D3748" opacity="0.3"/>
                    <rect x="5" y="40" width="35" height="2" rx="1" fill="#2D3748" opacity="0.3"/>
                </g>
                <g transform="translate(72, 10)">
                    <rect x="0" y="0" width="55" height="50" rx="3" fill="white" stroke="#E5E7EB" stroke-width="1"/>
                    <rect x="5" y="5" width="45" height="20" rx="2" fill="#C4785A" opacity="0.2"/>
                    <rect x="5" y="30" width="40" height="3" rx="1" fill="#2D3748" opacity="0.6"/>
                    <rect x="5" y="36" width="45" height="2" rx="1" fill="#2D3748" opacity="0.3"/>
                    <rect x="5" y="40" width="35" height="2" rx="1" fill="#2D3748" opacity="0.3"/>
                </g>
                <g transform="translate(134, 10)">
                    <rect x="0" y="0" width="55" height="50" rx="3" fill="white" stroke="#E5E7EB" stroke-width="1"/>
                    <rect x="5" y="5" width="45" height="20" rx="2" fill="#D4A853" opacity="0.2"/>
                    <rect x="5" y="30" width="40" height="3" rx="1" fill="#2D3748" opacity="0.6"/>
                    <rect x="5" y="36" width="45" height="2" rx="1" fill="#2D3748" opacity="0.3"/>
                    <rect x="5" y="40" width="35" height="2" rx="1" fill="#2D3748" opacity="0.3"/>
                </g>
                <text x="100" y="67" text-anchor="middle" font-size="6" fill="#4A6FA5" opacity="0.7">Manage Blog →</text>
            </svg>

            <!-- Contact Thumbnail -->
            <svg v-else-if="section.key === 'contact'" viewBox="0 0 200 60" class="w-full h-auto rounded shadow-sm">
                <rect width="200" height="60" fill="#C4785A"/>
                <!-- Diamond pattern -->
                <pattern id="diamonds" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                    <path d="M10 0L20 10L10 20L0 10Z" fill="white" opacity="0.05"/>
                </pattern>
                <rect width="200" height="60" fill="url(#diamonds)"/>
                <!-- Content centered -->
                <g transform="translate(100, 12)">
                    <rect x="-20" y="0" width="40" height="4" rx="2" fill="#D4A853" opacity="0.8"/>
                    <rect x="-30" y="10" width="60" height="6" rx="2" fill="white" opacity="0.9"/>
                    <rect x="-50" y="22" width="100" height="3" rx="1" fill="white" opacity="0.5"/>
                    <rect x="-35" y="32" width="70" height="10" rx="3" fill="white" opacity="0.9"/>
                </g>
            </svg>

            <!-- Footer Thumbnail -->
            <svg v-else-if="section.key === 'footer'" viewBox="0 0 200 50" class="w-full h-auto rounded shadow-sm">
                <rect width="200" height="50" fill="#2D3748"/>
                <!-- Footer columns -->
                <g transform="translate(10, 10)">
                    <rect x="0" y="0" width="35" height="5" rx="1" fill="white" opacity="0.8"/>
                    <rect x="0" y="10" width="45" height="2" rx="1" fill="white" opacity="0.3"/>
                    <rect x="0" y="14" width="40" height="2" rx="1" fill="white" opacity="0.3"/>
                </g>
                <g transform="translate(65, 10)">
                    <rect x="0" y="0" width="25" height="3" rx="1" fill="#D4A853" opacity="0.8"/>
                    <rect x="0" y="8" width="30" height="2" rx="1" fill="white" opacity="0.4"/>
                    <rect x="0" y="12" width="28" height="2" rx="1" fill="white" opacity="0.4"/>
                    <rect x="0" y="16" width="32" height="2" rx="1" fill="white" opacity="0.4"/>
                </g>
                <g transform="translate(110, 10)">
                    <rect x="0" y="0" width="25" height="3" rx="1" fill="#D4A853" opacity="0.8"/>
                    <rect x="0" y="8" width="30" height="2" rx="1" fill="white" opacity="0.4"/>
                    <rect x="0" y="12" width="28" height="2" rx="1" fill="white" opacity="0.4"/>
                </g>
                <g transform="translate(155, 10)">
                    <rect x="0" y="0" width="25" height="3" rx="1" fill="#D4A853" opacity="0.8"/>
                    <rect x="0" y="8" width="30" height="2" rx="1" fill="white" opacity="0.4"/>
                    <rect x="0" y="12" width="28" height="2" rx="1" fill="white" opacity="0.4"/>
                </g>
                <!-- Bottom bar -->
                <rect x="10" y="38" width="180" height="1" fill="white" opacity="0.1"/>
                <rect x="10" y="42" width="60" height="2" rx="1" fill="white" opacity="0.2"/>
            </svg>

            <!-- Section label -->
            <div
                class="mt-1 text-xs text-center font-medium transition-colors"
                :class="activeSection === section.key ? 'text-charcoal' : 'text-charcoal-light'"
            >
                {{ section.label }}
                <span v-if="!section.editable" class="text-mountain-blue">→</span>
            </div>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    sections: {
        type: Array,
        required: true,
    },
    activeSection: {
        type: String,
        default: null,
    },
});

const emit = defineEmits(['select']);

function handleClick(section) {
    if (section.editable) {
        emit('select', section.key);
    } else if (section.link) {
        window.location.href = section.link;
    }
}
</script>

<style scoped>
.section-thumbnails {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.thumbnail-wrapper {
    border-radius: 0.375rem;
    padding: 0.25rem;
    background: white;
}
</style>
