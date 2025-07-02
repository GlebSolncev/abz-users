<template>
    <div>
        <div
            v-for="user in users"
            :key="user.id"
            @click="openModal(user)"
            class="flex items-center gap-4 mb-6 p-4 bg-white rounded-2xl shadow"
        >
            <img
                :src="user.photo"
                alt="User photo"
                class="w-16 h-16 rounded-full object-cover"
            />
            <div class="flex-1">
                <h2 class="text-lg font-semibold">{{ user.name }}</h2>
                <p class="text-gray-600 text-sm">{{ user.position }}</p>
                <p class="text-gray-500 text-xs">{{ user.email }}</p>
                <p class="text-gray-500 text-xs">{{ user.phone }}</p>
            </div>
        </div>

        <button
            v-if="hasMore"
            @click="loadMore"
            class="block mx-auto px-6 py-2 bg-blue-500 text-white rounded-2xl shadow hover:bg-blue-600"
        >
            Load More
        </button>

        <p v-if="!users.length" class="text-center text-gray-500">No users found</p>
        <UserModal
            :user="selectedUser"
            :visible="isModalOpen"
            @close="isModalOpen = false"
        />
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import UserModal from './UserModal.vue'


const users   = ref([]);
const page    = ref(1);
const count   = ref(6);
const hasMore = ref(false);
const isModalOpen   = ref(false)
const selectedUser  = ref({})

async function fetchUsers(reset = false) {
    if (reset) {
        users.value = [];
        page.value = 1;
    }
    const res = await fetch(`/api/users?page=${page.value}&count=${count.value}`, {
        headers: { 'Accept': 'application/json' }
    });
    if (!res.ok) {
        console.error('Failed to load users:', res.statusText);
        return;
    }
    const json = await res.json();
    users.value.push(...json.users);
    hasMore.value = json.links.next_url !== null;
}

function openModal(user) {
    selectedUser.value = user
    isModalOpen.value = true
}

function loadMore() {
    page.value++;
    fetchUsers();
}

window.addEventListener('user-added', () => fetchUsers(true));

onMounted(() => {
    fetchUsers();
});
</script>

<style scoped>
/* optional custom styles */
</style>
