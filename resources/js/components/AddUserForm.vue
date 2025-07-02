<template>
    <form @submit.prevent="submit" class="space-y-4">
        <h2 class="text-xl font-semibold">Add New User</h2>

        <div>
            <label class="block mb-1">Name</label>
            <input v-model="form.name" type="text" class="w-full p-2 border rounded" placeholder="Enter full name" />
            <p v-if="errors.name" class="text-red-500 text-sm">{{ errors.name[0] }}</p>
        </div>

        <div>
            <label class="block mb-1">Email</label>
            <input v-model="form.email" type="email" class="w-full p-2 border rounded" placeholder="user@example.com" />
            <p v-if="errors.email" class="text-red-500 text-sm">{{ errors.email[0] }}</p>
        </div>

        <div>
            <label class="block mb-1">Phone</label>
            <input v-model="form.phone" type="text" placeholder="+380XXXXXXXXX" class="w-full p-2 border rounded" />
            <p v-if="errors.phone" class="text-red-500 text-sm">{{ errors.phone[0] }}</p>
        </div>

        <div>
            <label class="block mb-1">Position</label>
            <select v-model="form.position_id" class="w-full p-2 border rounded">
                <option value="">Select a position</option>
                <option v-for="pos in positions" :key="pos.id" :value="pos.id">
                    {{ pos.name }}
                </option>
            </select>
            <p v-if="errors.position_id" class="text-red-500 text-sm">{{ errors.position_id[0] }}</p>
        </div>

        <div>
            <label class="block mb-1">Photo</label>
            <input @change="onFileChange" type="file" accept="image/jpeg,image/jpg" class="w-full" />
            <p v-if="errors.photo" class="text-red-500 text-sm">{{ errors.photo[0] }}</p>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Add User
        </button>

        <p v-if="success" class="text-green-600 mt-2">User successfully added!</p>
    </form>
</template>

<script setup>
import {ref, onMounted, nextTick} from 'vue';

const form = ref({
    name: '',
    email: '',
    phone: '',
    position_id: '',
    photo: null,
});
const errors = ref({});
const positions = ref([]);
const success = ref(false);
let token = '';

onMounted(async () => {
    makeToken()

    // load positions
    const res = await fetch('/api/positions');
    const js = await res.json();
    positions.value = js.positions;
});

async function makeToken() {
    const t = await fetch('/api/token', {
        method: 'POST'
    });
    const jt = await t.json();
    token = jt.token;
}

function onFileChange(e) {
    form.value.photo = e.target.files[0];
}

let tries = 3
async function submit() {
    errors.value = {};
    success.value = false;

    const fd = new FormData();
    ['name','email','phone','position_id'].forEach(key =>
        fd.append(key, form.value[key])
    );
    fd.append('photo', form.value.photo);

    const res = await fetch('/api/users', {
        method: 'POST',
        headers: { 'Authorization': 'Bearer ' + token },
        body: fd,
    });

    if (res.status === 201) {
        success.value = true;
        form.value = { name:'', email:'', phone:'', position_id:'', photo:null };
        window.dispatchEvent(new Event('user-added'));
    }
    else if (res.status === 401 ) {
        await makeToken()
        nextTick()

        if(tries >= 0) {
            tries--
            submit()
        }

    } else {
        const js = await res.json();
        errors.value = js.fails || {};
    }
}
</script>

<style scoped>
/* optional custom styles */
</style>
