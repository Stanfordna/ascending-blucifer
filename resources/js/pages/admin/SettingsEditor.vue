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
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="font-semibold text-charcoal">Brand Colors</h2>
                    <div class="flex gap-2">
                        <button
                            type="button"
                            @click="revertColors"
                            :disabled="!colorsChanged"
                            class="btn btn-ghost disabled:opacity-40"
                        >
                            Revert Changes
                        </button>
                        <button
                            type="button"
                            @click="restoreDefaultColors"
                            class="btn btn-ghost text-mountain-blue hover:text-mountain-blue-dark"
                        >
                            Restore Defaults
                        </button>
                    </div>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div v-for="setting in getGroup('colors')" :key="setting.key">
                        <ColorPicker v-model="setting.value" :label="colorLabel(setting)" />
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

        <!-- Account Security (outside the settings form) -->
        <div class="bg-white rounded-lg border border-gray-200 mt-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="font-semibold text-charcoal">Account Security</h2>
            </div>
            <div class="p-6 space-y-4 max-w-md">
                <div>
                    <label for="current_password" class="block text-sm font-medium text-charcoal mb-1">Current Password</label>
                    <input
                        id="current_password"
                        v-model="passwordForm.current_password"
                        type="password"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-mountain-blue focus:ring-1 focus:ring-mountain-blue outline-none"
                    />
                    <p v-if="passwordErrors.current_password" class="text-sm text-terracotta mt-1">
                        {{ passwordErrors.current_password[0] }}
                    </p>
                </div>
                <div>
                    <label for="new_password" class="block text-sm font-medium text-charcoal mb-1">New Password</label>
                    <input
                        id="new_password"
                        v-model="passwordForm.new_password"
                        type="password"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-mountain-blue focus:ring-1 focus:ring-mountain-blue outline-none"
                    />
                    <p v-if="passwordErrors.new_password" class="text-sm text-terracotta mt-1">
                        {{ passwordErrors.new_password[0] }}
                    </p>
                </div>
                <div>
                    <label for="new_password_confirmation" class="block text-sm font-medium text-charcoal mb-1">Confirm New Password</label>
                    <input
                        id="new_password_confirmation"
                        v-model="passwordForm.new_password_confirmation"
                        type="password"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-mountain-blue focus:ring-1 focus:ring-mountain-blue outline-none"
                    />
                </div>
                <button
                    type="button"
                    @click="changePassword"
                    class="btn btn-primary"
                    :disabled="changingPassword || !passwordForm.current_password || !passwordForm.new_password"
                >
                    {{ changingPassword ? 'Changing...' : 'Change Password' }}
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import api from '@/services/api';
import { useToast } from '@/stores/toast';
import { useSettingsStore } from '@/stores/settings';
import ColorPicker from '@/components/admin/ColorPicker.vue';
import { presets as colorPresets } from '@/components/admin/ColorPicker.vue';

const toast = useToast();
const settingsStore = useSettingsStore();
const loading = ref(true);
const saving = ref(false);
const settings = ref([]);
const originalColors = ref({});

const passwordForm = ref({
    current_password: '',
    new_password: '',
    new_password_confirmation: '',
});
const changingPassword = ref(false);
const passwordErrors = ref({});

async function changePassword() {
    changingPassword.value = true;
    passwordErrors.value = {};
    try {
        await api.post('/admin/change-password', passwordForm.value);
        toast.success('Password changed successfully');
        passwordForm.value = {
            current_password: '',
            new_password: '',
            new_password_confirmation: '',
        };
    } catch (e) {
        if (e.response?.data?.errors) {
            passwordErrors.value = e.response.data.errors;
        } else {
            toast.error(e.response?.data?.message || 'Failed to change password');
        }
    } finally {
        changingPassword.value = false;
    }
}

function colorLabel(setting) {
    // Strip old preset name in parentheses and compute new one from value
    const base = setting.label.replace(/\s*\(.*\)$/, '');
    const match = colorPresets.find(p => p.hex.toLowerCase() === (setting.value || '').toLowerCase());
    return match ? `${base} (${match.name})` : base;
}

const defaultColors = {
    color_primary: '#4A7C89',
    color_secondary: '#C4785A',
    color_accent: '#D4A84B',
};

function getGroup(group) {
    return settings.value.filter(s => s.group === group);
}

const colorsChanged = computed(() => {
    const colorSettings = getGroup('colors');
    return colorSettings.some(s => s.value !== originalColors.value[s.key]);
});

function revertColors() {
    const colorSettings = getGroup('colors');
    colorSettings.forEach(s => {
        s.value = originalColors.value[s.key];
    });
}

function restoreDefaultColors() {
    const colorSettings = getGroup('colors');
    colorSettings.forEach(s => {
        if (defaultColors[s.key]) {
            s.value = defaultColors[s.key];
        }
    });
}

async function fetchSettings() {
    try {
        const response = await api.get('/admin/settings');
        // Flatten grouped response into array
        const grouped = response.data;
        settings.value = Object.values(grouped).flat();

        // Store original color values for revert functionality
        const colorSettings = getGroup('colors');
        colorSettings.forEach(s => {
            originalColors.value[s.key] = s.value;
        });
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

        // Apply colors immediately
        const colorSettings = getGroup('colors');
        const colors = {};
        colorSettings.forEach(s => {
            if (s.key === 'color_primary') colors.primary = s.value;
            if (s.key === 'color_secondary') colors.secondary = s.value;
            if (s.key === 'color_accent') colors.accent = s.value;
        });
        settingsStore.setColors(colors);

        // Update original colors for revert functionality
        colorSettings.forEach(s => {
            originalColors.value[s.key] = s.value;
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
