import './bootstrap'
import { createApp } from 'vue';
import UserList from './components/UserList.vue';
import AddUserForm from './components/AddUserForm.vue';

const app = createApp({});
app.component('user-list', UserList);
app.component('add-user-form', AddUserForm);
app.mount('#app');