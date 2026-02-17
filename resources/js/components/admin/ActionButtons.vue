<template>
    <div class="action-buttons">
        <a
            v-if="showView && viewHref"
            :href="viewHref"
            target="_blank"
            class="action-btn action-btn--view"
            title="View"
        >
            <span class="action-btn__bg"></span>
            <svg class="action-btn__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
        </a>
        <button
            v-else-if="showView"
            @click="$emit('view')"
            class="action-btn action-btn--view"
            title="View"
        >
            <span class="action-btn__bg"></span>
            <svg class="action-btn__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
        </button>

        <component
            :is="editHref ? 'router-link' : 'button'"
            :to="editHref"
            @click="!editHref && $emit('edit')"
            class="action-btn action-btn--edit"
            title="Edit"
        >
            <span class="action-btn__bg"></span>
            <svg class="action-btn__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
        </component>

        <button
            @click="$emit('delete')"
            class="action-btn action-btn--delete"
            title="Delete"
        >
            <span class="action-btn__bg"></span>
            <svg class="action-btn__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
        </button>
    </div>
</template>

<script setup>
defineProps({
    showView: {
        type: Boolean,
        default: false,
    },
    viewHref: {
        type: String,
        default: null,
    },
    editHref: {
        type: String,
        default: null,
    },
});

defineEmits(['view', 'edit', 'delete']);
</script>

<style scoped>
.action-buttons {
    display: flex;
    gap: 0.25rem;
    justify-content: flex-end;
}

.action-btn {
    position: relative;
    width: 2.25rem;
    height: 2.25rem;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 0.5rem;
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    flex-shrink: 0;
    border: none;
    background: transparent;
    cursor: pointer;
}

/* Diagonal background */
.action-btn__bg {
    position: absolute;
    inset: 0;
    opacity: 0.15;
    transform: skewX(-12deg) scaleX(0.7);
    transition: all 0.3s ease;
    border-radius: 0.25rem;
}

.action-btn--view .action-btn__bg,
.action-btn--edit .action-btn__bg {
    background: linear-gradient(135deg, var(--brand-primary), var(--brand-accent));
}

.action-btn--delete .action-btn__bg {
    background: linear-gradient(135deg, var(--brand-secondary), #dc2626);
}

.action-btn__icon {
    position: relative;
    z-index: 1;
    width: 1.125rem;
    height: 1.125rem;
    transition: transform 0.3s ease;
}

.action-btn--view .action-btn__icon,
.action-btn--edit .action-btn__icon {
    color: var(--brand-primary);
}

.action-btn--delete .action-btn__icon {
    color: var(--brand-secondary);
}

/* Hover effects */
.action-btn:hover .action-btn__bg {
    opacity: 0.35;
    transform: skewX(-12deg) scaleX(1);
}

.action-btn:hover .action-btn__icon {
    transform: scale(1.1);
}
</style>
