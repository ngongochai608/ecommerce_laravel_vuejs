<template>
    <div>
        <Sidebar></Sidebar>
        <div v-if="loading" class="text-green-500 text-center">Loading...</div>        
        <div v-else class="p-4 sm:ml-52">
            <button @click="openModal(null)" class="flex gap-2 mb-4 mt-4 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mr-2">
                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 7.757v8.486M7.757 12h8.486M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
                Thêm kiểu
            </button>
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 white:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 white:bg-gray-700 white:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Tên
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Hành động
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="bg-white border-b white:bg-gray-800 white:border-gray-700 border-gray-200"  v-for="table in tables" :key="table.id">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap white:text-white">
                            {{ table.name }}
                        </th>
                        <td class="px-6 py-4 w-[137px]">
                            <button @click="openModal(table)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded mr-2">
                                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z"/>
                                </svg>
                            </button>
                            <button @click="openConfirmDelete(table.id)" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded">
                                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                </svg>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div v-if="isOpenModal" class="relative z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true"></div>
            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                        <form @submit.prevent="handleSubmit">
                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <h2 class="text-xl font-semibold mb-4">{{ isEditing ? 'Sửa kiểu' : 'Thêm kiểu' }}</h2>
                                <div class="grid grid-cols-1 gap-x-12 gap-y-8">
                                    <div class="sm:col-span-4">
                                        <label for="name" class="block text-sm/6 font-medium text-gray-900">Tên kiểu</label>
                                        <div class="mt-2">
                                            <div class="flex items-center rounded-md bg-white outline-1 -outline-offset-1 outline-gray-300 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-600">
                                                <input v-model="form.name" autocomplete="off" type="text" name="name" id="name" class="rounded-lg block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none sm:text-sm/6">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                                <button type="submit" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-red-500 sm:ml-3 sm:w-auto">Lưu</button>
                                <button @click="closeModal()" type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 shadow-xs ring-gray-300 ring-inset hover:bg-gray-50 sm:mt-0 sm:w-auto">Hủy</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="isConfirmDelete" class="relative z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true"></div>
            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-center">
                        <div class="mx-auto flex size-12 shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:size-10">
                        <svg class="size-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                        </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-base font-semibold text-gray-900" id="modal-title">Confirm Delete</h3>
                        </div>
                    </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button @click="deleteTable()" type="button" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-red-500 sm:ml-3 sm:w-auto">Delete</button>
                        <button @click="closeModalDelete()" type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 shadow-xs ring-gray-300 ring-inset hover:bg-gray-50 sm:mt-0 sm:w-auto">Cancel</button>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import Sidebar from '@/Pages/Includes/Sidebar.vue';
    export default {
        components: {
            Sidebar,
        },
        data() {
            return {
                tables: [],
                loading: true,
                error: null,
                isEditing: false,
                isOpenModal: false,
                isConfirmDelete: false,
                idDelete: null,
                form: {
                    id: null,
                    name: '',
                },
            }
        },
        mounted() {
            this.fetchTables();
        },
        methods: {
            async fetchTables() {
                try {
                    const reponsive = await axios.get('/api/tables');
                    this.tables = reponsive.data;
                } catch (error) {
                    console.log('fetch tables error', error);
                    this.error = error;
                } finally {
                    this.loading = false;
                }
            },
            openModal(table) {
                if (table) {
                    this.isEditing = true;
                    this.form = {...table};
                } else {
                    this.isEditing = false;
                    this.form = {
                        id: null,
                        name: ''
                    };
                }
                this.isOpenModal = true;
            },
            closeModal() {
                this.isOpenModal = false;
            },
            async addTable () {
                try {
                    const reponsive = await axios.post('/api/tables', this.form);
                    this.tables.push(reponsive.data);
                    this.isOpenModal = false;
                } catch (error) {
                    console.log('add tables error', error);
                }
            },
            async updateTable () {
                try {
                    const reponsive = await axios.put(`/api/tables/${this.form.id}`, this.form);
                    const index = this.tables.findIndex(table => table.id === this.form.id);
                    if (index !== -1) {
                        this.tables[index] = reponsive.data;
                        this.isOpenModal = false;
                    }
                } catch (error) {
                    console.log('update table error', error);
                }
            },
            async handleSubmit () {
                if (this.isEditing) {
                    await this.updateTable();
                } else {
                    await this.addTable();
                }
            },
            openConfirmDelete(table_id) {
                this.isConfirmDelete = true;
                this.idDelete = table_id;
            },
            closeModalDelete() {
                this.isConfirmDelete = false;
                this.idDelete = null;
            },
            async deleteTable() {
                try {
                    await axios.delete(`/api/tables/${this.idDelete}`);
                    this.tables = this.tables.filter(table => table.id !== this.idDelete);
                    this.isConfirmDelete = false;
                    this.idDelete = null;
                } catch (error) {
                    console.log('delete table error', error);
                }
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>