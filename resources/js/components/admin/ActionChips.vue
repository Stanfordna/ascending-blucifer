<template>
    <div class="action-chips">
        <component
            v-for="action in visibleActions"
            :key="action.key"
            :is="action.to ? 'router-link' : (action.href ? 'a' : 'button')"
            v-bind="linkAttrs(action)"
            @click.stop="handleClick(action)"
            class="action-chip"
        >
            <span class="action-chip__bg" :style="{ background: action.gradient }"></span>
            <svg class="action-chip__icon" :style="{ color: action.color }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                    v-for="(d, i) in action.paths"
                    :key="i"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    :d="d"
                />
            </svg>
            <span class="action-chip__tooltip">{{ action.tooltip }}</span>
        </component>
    </div>
</template>

<script>
// Reusable CRUD action presets
export const CRUD_ACTIONS = {
    view: {
        key: 'view',
        tooltip: 'View',
        color: 'var(--brand-primary)',
        gradient: 'linear-gradient(135deg, var(--brand-primary), var(--brand-accent))',
        paths: [
            'M15 12a3 3 0 11-6 0 3 3 0 016 0z',
            'M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z',
        ],
    },
    edit: {
        key: 'edit',
        tooltip: 'Edit',
        color: 'var(--brand-primary)',
        gradient: 'linear-gradient(135deg, var(--brand-primary), var(--brand-accent))',
        paths: [
            'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z',
        ],
    },
    delete: {
        key: 'delete',
        tooltip: 'Delete',
        color: 'var(--brand-secondary)',
        gradient: 'linear-gradient(135deg, var(--brand-secondary), #dc2626)',
        paths: [
            'M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16',
        ],
    },
};
</script>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    actions: { type: Array, required: true },
    exclude: { type: String, default: null },
});

const emit = defineEmits(['action']);

const visibleActions = computed(() =>
    props.actions.filter(a => a && a.key !== props.exclude)
);

function linkAttrs(action) {
    if (action.to) return { to: action.to };
    if (action.href) return { href: action.href, target: '_blank' };
    return {};
}

function handleClick(action) {
    if (!action.to && !action.href) {
        emit('action', action.key);
    }
}
</script>
