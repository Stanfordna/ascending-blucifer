import { defineStore } from 'pinia';
import { ref } from 'vue';
import axios from 'axios';

export const useContentStore = defineStore('content', () => {
    const blocks = ref({});
    const loading = ref(false);
    const loaded = ref(false);

    async function fetchBlocks() {
        if (loaded.value) return;

        loading.value = true;
        try {
            const response = await axios.get('/api/content-blocks');
            blocks.value = response.data;
            loaded.value = true;
        } catch (e) {
            console.error('Failed to load content blocks:', e);
        } finally {
            loading.value = false;
        }
    }

    function getBlock(key, defaultValue = '') {
        return blocks.value[key] ?? defaultValue;
    }

    function getBlocksBySection(section) {
        return Object.entries(blocks.value)
            .filter(([key]) => key.startsWith(`${section}_`))
            .reduce((acc, [key, value]) => {
                acc[key] = value;
                return acc;
            }, {});
    }

    async function updateBlock(key, content) {
        blocks.value[key] = content;
    }

    function refresh() {
        loaded.value = false;
        return fetchBlocks();
    }

    return {
        blocks,
        loading,
        loaded,
        fetchBlocks,
        getBlock,
        getBlocksBySection,
        updateBlock,
        refresh,
    };
});
