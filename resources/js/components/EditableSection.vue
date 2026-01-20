<template>
    <div
        class="editable-section relative"
        @mouseenter="showEdit = true"
        @mouseleave="showEdit = false"
    >
        <slot></slot>

        <Transition name="fade">
            <button
                v-if="isAdmin && showEdit"
                @click="handleEdit"
                class="edit-button absolute top-4 right-4 z-50 bg-white/90 hover:bg-white text-charcoal p-3 rounded-full shadow-lg transition-all hover:scale-110 hover:shadow-xl"
                :title="`Edit ${displayLabel}`"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
            </button>
        </Transition>

        <SectionEditModal
            v-if="!link"
            v-model="modalOpen"
            :section="section"
            :section-label="displayLabel"
            @saved="handleSaved"
        />
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import { useContentStore } from '@/stores/content';
import SectionEditModal from './SectionEditModal.vue';

const props = defineProps({
    section: {
        type: String,
        required: true,
    },
    sectionLabel: {
        type: String,
        default: '',
    },
    link: {
        type: String,
        default: '',
    },
});

const router = useRouter();
const authStore = useAuthStore();
const contentStore = useContentStore();

const showEdit = ref(false);
const modalOpen = ref(false);

const isAdmin = computed(() => authStore.isAuthenticated);

const displayLabel = computed(() => {
    if (props.sectionLabel) return props.sectionLabel;
    const labels = {
        welcome: 'Welcome Banner',
        about: 'About',
        contact: 'Contact Prompt',
        footer: 'Footer',
        services: 'Services',
        blog: 'Blog',
    };
    return labels[props.section] || props.section;
});

function handleEdit() {
    if (props.link) {
        router.push(props.link);
    } else {
        modalOpen.value = true;
    }
}

function handleSaved() {
    contentStore.refresh();
}
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
