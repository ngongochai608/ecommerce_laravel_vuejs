<template>
    <div>
        <Sidebar></Sidebar>
        <h3 class="p-4 pb-0 sm:ml-52 text-2xl font-bold tracking-tight text-gray-900 white:text-white">Doanh số</h3>
        <div class="p-4 pb-0 sm:ml-52">
            <input @input="changeDate" v-model="date" type="date">
        </div>
        <div class="grid grid-cols-4 gap-4 p-4 sm:ml-52">
            <div class="block p-6 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-100 white:bg-gray-800 white:border-gray-700 white:hover:bg-gray-700">
                <h5 class="mb-2 text-xl font-bold tracking-tight text-gray-900 white:text-white">Doanh số hôm nay</h5>
                <p class="font-normal text-gray-700 white:text-gray-400">{{ formatPrice(sale) }}đ</p>
            </div>
            <div class="block p-6 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-100 white:bg-gray-800 white:border-gray-700 white:hover:bg-gray-700">
                <h5 class="mb-2 text-xl font-bold tracking-tight text-gray-900 white:text-white">Lợi nhuận hôm nay</h5>
                <p class="font-normal text-gray-700 white:text-gray-400">{{ formatPrice(profit) }}đ</p>
            </div>
            <div class="block p-6 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-100 white:bg-gray-800 white:border-gray-700 white:hover:bg-gray-700">
                <h5 class="mb-2 text-xl font-bold tracking-tight text-gray-900 white:text-white">Đơn đã bán hôm nay</h5>
                <p class="font-normal text-gray-700 white:text-gray-400">{{ countInvoice }}</p>
            </div>
        </div>
        <h3 class="p-4 pb-0 sm:ml-52 text-2xl font-bold tracking-tight text-gray-900 white:text-white">Kho</h3>
        <div class="gap-4 p-4 sm:ml-52">
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
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
                                Giá
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Danh mục
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="product in products" :key="product.id" class="odd:bg-white odd:white:bg-gray-900 even:bg-gray-50 even:white:bg-gray-800 border-b white:border-gray-700 border-gray-200">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap white:text-white">
                                {{ product.name }}
                            </th>
                            <td class="px-6 py-4">
                                {{ product.quantity }}
                            </td>
                            <td class="px-6 py-4">
                                {{ formatPrice(product.price) }}
                            </td>
                            <td class="px-6 py-4">
                                {{ product.category_id }}
                            </td>
                        </tr>
                    </tbody>
                </table>
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
        data () {
            return {
                date: new Date().toISOString().split("T")[0],
                products: [],
                sale: 0,
                profit: 0,
                countInvoice: 0,
            }
        },
        mounted () {
            this.fetchTotalInvoicesToday();
            this.fetchProducts();
        },
        methods: {
            async fetchProducts() {
                try {
                    const reponsive = await axios.get('/api/products');
                    this.products = reponsive.data;
                } catch (error) {
                    console.log('fetch products error', error);
                    this.error = error;
                } finally {
                    this.loading = false;
                }
            },
            formatPrice(price) {
                return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            },
            async fetchTotalInvoicesToday() {
                try {
                    const reponsive = await axios.get(`/api/statistical/${this.date}`);
                    this.profit = reponsive.data.profit;
                    this.sale = reponsive.data.sale;
                    this.countInvoice = reponsive.data.count;
                } catch (error) {
                    console.log('fetch products error', error);
                    this.error = error;
                } finally {
                    this.loading = false;
                }
            },
            changeDate (e) {
                this.date = e.target.value;
                this.fetchTotalInvoicesToday();
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>