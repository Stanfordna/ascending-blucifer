<template>
    <div class="max-w-4xl">
        <div v-if="loading" class="flex items-center justify-center py-12">
            <div class="animate-spin w-8 h-8 border-2 border-mountain-blue border-t-transparent rounded-full"></div>
        </div>

        <form v-else @submit.prevent="saveSettings" class="space-y-8">
            <!-- Group: General -->
            <div class="bg-white rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="font-semibold text-charcoal">General Settings</h2>
                </div>
                <div class="p-6 space-y-4">
                    <div v-for="setting in getGroup('general')" :key="setting.key">
                        <label :for="setting.key" class="block text-sm font-medium text-charcoal mb-1">
                            {{ setting.label }}
                        </label>
                        <input
                            :id="setting.key"
                            v-model="setting.value"
                            :type="setting.type === 'email' ? 'email' : 'text'"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-mountain-blue focus:ring-1 focus:ring-mountain-blue outline-none"
                        />
                    </div>
                </div>
            </div>

            <!-- Group: Contact -->
            <div class="bg-white rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="font-semibold text-charcoal">Contact Information</h2>
                </div>
                <div class="p-6 space-y-4">
                    <div v-for="setting in getGroup('contact')" :key="setting.key">
                        <label :for="setting.key" class="block text-sm font-medium text-charcoal mb-1">
                            {{ setting.label }}
                        </label>
                        <input
                            :id="setting.key"
                            v-model="setting.value"
                            :type="setting.type === 'email' ? 'email' : 'text'"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-mountain-blue focus:ring-1 focus:ring-mountain-blue outline-none"
                        />
                    </div>
                </div>
            </div>

            <!-- Group: Social -->
            <div class="bg-white rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="font-semibold text-charcoal">Social Links</h2>
                </div>
                <div class="p-6 space-y-4">
                    <div v-for="setting in getGroup('social')" :key="setting.key">
                        <label :for="setting.key" class="block text-sm font-medium text-charcoal mb-1">
                            {{ setting.label }}
                        </label>
                        <input
                            :id="setting.key"
                            v-model="setting.value"
                            type="url"
                            placeholder="https://"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-mountain-blue focus:ring-1 focus:ring-mountain-blue outline-none"
                        />
                    </div>
                </div>
            </div>

            <!-- Group: Colors -->
            <div class="bg-white rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="font-semibold text-charcoal">Brand Colors</h2>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div v-for="setting in getGroup('colors')" :key="setting.key">
                        <label :for="setting.key" class="block text-sm font-medium text-charcoal mb-1">
                            {{ setting.label }}
                        </label>
                        <div class="flex items-center gap-2">
                            <input
                                :id="setting.key"
                                v-model="setting.value"
                                type="color"
                                class="w-12 h-10 border border-gray-300 rounded cursor-pointer"
                            />
                            <input
                                v-model="setting.value"
                                type="text"
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:border-mountain-blue focus:ring-1 focus:ring-mountain-blue outline-none font-mono text-sm"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Save Button -->
            <div class="flex justify-end">
                <button
                    type="submit"
                    class="btn btn-primary"
                    :disabled="saving"
                >
                    <span v-if="saving">Saving...</span>
                    <span v-else>Save Settings</span>
                </button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '@/services/api';
import { useToast } from '@/stores/toast';

const toast = useToast();
const loading = ref(true);
const saving = ref(false);
const settings = ref([]);

function getGroup(group) {
    return settings.value.filter(s => s.group === group);
}

async function fetchSettings() {
    try {
        const response = await api.get('/admin/settings');
        // Flatten grouped response into array
        const grouped = response.data;
        settings.value = Object.values(grouped).flat();
    } catch (e) {
        toast.error('Failed to load settings');
    } finally {
        loading.value = false;
    }
}

async function saveSettings() {
    saving.value = true;
    try {
        await api.put('/admin/settings', {
            settings: settings.value.map(s => ({
                key: s.key,
                value: s.value,
            })),
        });
        toast.success('Settings saved successfully');
    } catch (e) {
        toast.error('Failed to save settings');
    } finally {
        saving.value = false;
    }
}

onMounted(() => {
    fetchSettings();
});
</script>
