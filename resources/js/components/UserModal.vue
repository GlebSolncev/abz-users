<!-- resources/js/components/UserModal.vue -->
<template>
    <div
        v-if="visible"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
        @click.self="close"
    >
        <div class="bg-white rounded-2xl shadow-lg max-w-md w-full p-6 relative">
            <button
                @click="close"
                class="absolute top-4 right-4 text-gray-500 hover:text-gray-700"
            >
                &times;
            </button>

            <div class="flex flex-col items-center">
                <img
                    v-if="user.photo"
                    :src="user.photo"
                    alt="User Photo"
                    class="w-24 h-24 rounded-full object-cover mb-4"
                />
                <div
                    v-else
                    class="w-24 h-24 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-semibold mb-4 text-xl"
                >
                    {{ initials(user.name) }}
                </div>

                <h2 class="text-2xl font-semibold mb-2">{{ user.name }}</h2>
                <p class="text-gray-600 mb-1">{{ user.position }}</p>
                <p class="text-gray-500 text-sm mb-1">{{ user.email }}</p>
                <p class="text-gray-500 text-sm mb-4">{{ user.phone }}</p>
                <p class="text-gray-400 text-xs">
                    Registered at: {{ formatDate(user.registration_timestamp) }}
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    user: {
        type: Object,
        required: true
    },
    visible: {
        type: Boolean,
        required: true
    }
});
const emit = defineEmits(['close']);

function close() {
    emit('close');
}

function initials(name) {
    return name
        .split(' ')
        .map(part => part[0]?.toUpperCase() || '')
        .slice(0, 2)
        .join('');
}

function formatDate(ts) {
    const d = new Date(ts * 1000);
    return d.toLocaleString();
}
</script>

<style scoped>
/* Можно добавить анимацию появления через transition-group */
</style>
