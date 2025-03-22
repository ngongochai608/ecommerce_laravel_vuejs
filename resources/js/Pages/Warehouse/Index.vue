<template>
    <div>
        <Sidebar></Sidebar>
        <div v-if="loading" class="text-green-500 text-center">Loading...</div>        
        <div v-else class="p-4 sm:ml-52">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 white:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 white:bg-gray-700 white:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Tên sản phẩm
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Số lượng
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Thời gian
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="bg-white border-b white:bg-gray-800 white:border-gray-700 border-gray-200"  v-for="history in history_warehouse" :key="history.id">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap white:text-white">
                            {{ history.name }}
                        </th>
                        <td class="px-6 py-4">
                            {{ history.quantity }}
                        </td>
                        <td class="px-6 py-4">
                            {{ formatDate(history.created_at) }}
                        </td>
                    </tr>
                </tbody>
            </table>
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
                history_warehouse: [],
                loading: true,
                error: null
            }
        },
        mounted() {
            this.fetchHistoryWareHouse();
        },
        methods: {
            async fetchHistoryWareHouse() {
                try {
                    const reponsive = await axios.get('/api/history-warehouse');
                    this.history_warehouse = reponsive.data;
                } catch (error) {
                    console.log('fetch categories error', error);
                    this.error = error;
                } finally {
                    this.loading = false;
                }
            },
            formatDate(date) {
                const dateFormat = new Date(date);
                return dateFormat.toLocaleString("vi-VN", {
                    day: "2-digit",
                    month: "2-digit",
                    year: "numeric",
                    hour: "2-digit",
                    minute: "2-digit",
                    hour12: false
                });
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>