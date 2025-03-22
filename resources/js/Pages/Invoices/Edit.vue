<template>
  <div>
      <Sidebar></Sidebar>
      <div v-if="loading" class="text-green-500 text-center">Loading Create Invoice...</div>        
      <div class="flex h-screen sm:ml-52 bg-slate-300 p-2">
          <div class="w-3/5 p-2 bg-white rounded mr-2">
              <div class="flex">
                  <button @click="fetchProducts()" class="text-gray-900 bg-white border border-gray-300 hover:bg-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 white:bg-gray-800 white:text-white white:border-gray-600 white:hover:bg-gray-700 white:hover:border-gray-600">
                      Tất cả
                  </button>
                  <button @click="filterWithCategory(category.id)" v-for="category in categories" :key="category.id" class="text-gray-900 bg-white border border-gray-300 hover:bg-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 white:bg-gray-800 white:text-white white:border-gray-600 white:hover:bg-gray-700 white:hover:border-gray-600">
                      {{ category.name }}
                  </button>
              </div>
              <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                  <div class="relative" v-for="product in products" @click="choseItem(product.id)" :key="product.id">
                      <img class="h-[150px] w-full max-w-full rounded-lg object-cover" :src="product.image" alt="">
                      <h3 class="absolute left-0 bottom-0 text-white bg-zinc-700/80 py-1 px-2 w-full">{{ product.name }}</h3>
                      <span class="absolute top-0 right-0 p-1 text-white bg-red-800">{{ formatPrice(product.price) }}</span>
                      <div v-if="showChoseQtyItem.id == product.id" class="absolute w-full h-full top-0 left-0 bg-slate-900/70 p-4 flex items-center">
                          <div class="relative flex items-center">
                              <button @click.stop="handleDecrement(product)" type="button" id="decrement-button" class="bg-gray-100 white:bg-gray-700 white:hover:bg-gray-600 white:border-gray-600 hover:bg-gray-200 border border-gray-300 rounded-s-lg py-1 px-3 h-9">
                                  <svg class="w-3 h-3 text-gray-900 white:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 2">
                                      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h16"/>
                                  </svg>
                              </button>
                              <input type="text" id="guests-input" class="bg-gray-50 border-x-0 border-gray-300 h-9 font-medium text-center text-gray-900 text-sm focus:ring-blue-500 focus:border-blue-500 block w-full pb-2 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500" :value="showChoseQtyItem.qty" />
                              <button @click.stop="handleIncrement(product)" type="button" id="increment-button" class="bg-gray-100 white:bg-gray-700 white:hover:bg-gray-600 white:border-gray-600 hover:bg-gray-200 border border-gray-300 rounded-e-lg py-1 px-3 h-9">
                                  <svg class="w-3 h-3 text-gray-900 white:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                                      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16"/>
                                  </svg>
                              </button>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
          <div class="flex flex-col bg-white rounded w-2/5 p-2">
              <select v-model="this.form.table_id" id="table_id" name="table_id" class="mb-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white">
                  <option v-for="table in tables" :key="table.id" :value="table.id">{{ table.name }}</option>
              </select>
              <div v-if="items.length > 0" class="flex-1 relative overflow-x-auto">
                  <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                      <thead class="text-xs text-gray-700 uppercase bg-gray-50 white:bg-gray-700 white:text-gray-400">
                          <tr>
                              <th scope="col" class="px-2 py-1">
                                  Product name
                              </th>
                              <th scope="col" class="px-2 py-1">
                                  Price
                              </th>
                              <th scope="col" class="px-2 py-1">
                                  Qty
                              </th>
                              <th scope="col" class="px-2 py-1">
                                  Total
                              </th>
                              <th scope="col" class="px-2 py-1">
                                  <span class="sr-only">Edit</span>
                              </th>
                          </tr>
                      </thead>
                      <tbody>
                          <tr v-for="item in items" class="bg-white border-b white:bg-gray-800 white:border-gray-700 border-gray-200 hover:bg-gray-50 white:hover:bg-gray-600">
                              <th scope="row" class="px-2 py-1 font-medium text-gray-900 whitespace-nowrap white:text-white">
                                  {{ item.name }}
                              </th>
                              <td class="px-2 py-1">
                                  {{ formatPrice(item.price) }}
                              </td>
                              <td class="px-2 py-1">
                                  {{ item.qty }}
                              </td>
                              <td class="px-2 py-1">
                                  {{ formatPrice(item.total) }}
                              </td>
                              <td class="px-2 py-1 text-right">
                                  <button @click="remoteItem(item.id)" class="rounded-full bg-red-600 mt-1">
                                      <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                      </svg>
                                  </button>
                              </td>
                          </tr>
                      </tbody>
                  </table>
              </div>
              <div class="flex-1" v-if="items.length == 0">
                  <img class="mx-auto my-[50px]" src="/uploads/general/icon-food.png" alt="" width="100" height="100">
              </div>
              <div class="text-right pt-3 pb-3">
                  <h5>Total: {{ formatPrice(invoiceTotal) }}</h5>
              </div>
              <div class="mt-auto flex justify-end">
                  <button class="flex gap-1 justify-center items-center flex-1 text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 white:bg-blue-600 white:hover:bg-blue-700">
                      <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                      <path stroke="currentColor" stroke-linejoin="round" stroke-width="2" d="M16.444 18H19a1 1 0 0 0 1-1v-5a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1h2.556M17 11V5a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v6h10ZM7 15h10v4a1 1 0 0 1-1 1H8a1 1 0 0 1-1-1v-4Z"/>
                      </svg>
                      In hóa đơn
                  </button>
                  <!-- <button @click="updateInvoice()" class="flex gap-1 justify-center items-center flex-1 text-white bg-green-700 hover:bg-green-800 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700">
                      <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 13V4M7 14H5a1 1 0 0 0-1 1v4a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-4a1 1 0 0 0-1-1h-2m-1-5-4 5-4-5m9 8h.01"/>
                      </svg>
                      Save
                  </button> -->
                  <button class="flex gap-1 justify-center items-center flex-1 text-white bg-purple-700 hover:bg-purple-800 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-green-600 dark:hover:bg-green-700">
                      <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                      <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M8 7V6a1 1 0 0 1 1-1h11a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1h-1M3 18v-7a1 1 0 0 1 1-1h11a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1Zm8-3.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>
                      </svg>
                      Thanh toán
                  </button>
              </div>
          </div>
      </div>
  </div>
</template>

<script>
  import axios from 'axios';
  import Sidebar from '@/Pages/Includes/Sidebar.vue';
  export default {
    props: {
      invoice: {
        type: Object,
        required: true
      }
    },
    components: {
        Sidebar,
    },
    data() {
        return {
            categories: [],
            products: [],
            tables: [],
            items: [],
            showChoseQtyItem: {
                id: 0,
                qty: 0,
            },
            invoiceTotal: 0,
            form: {
                name: '',
                status: '',
                items: '',
                price: 0,
                price_add: 0,
                discount: 0,
                total: 0,
                note: '',
                table_id: 3
            }
        }
    },
    mounted() {
      this.setInvoice();
      this.fetchCategories();
      this.fetchProducts();
      this.fetchTables();
    },
    methods: {
        setInvoice () {
          this.form = {...this.invoice};
          this.invoiceTotal = this.invoice.total;
          this.items = JSON.parse(this.invoice.items);
        },
        async fetchCategories () {
            try {
                const response = await axios.get('/api/categories');
                this.categories = response.data;
            } catch (error) {
                console.log('fetch categories'. error);
            }
        },
        async fetchProducts () {
            try {
                const response = await axios.get('/api/products');
                this.products = response.data;
            } catch (error) {
                console.log('fetch product'. error);
            }
        },
        async fetchTables () {
            try {
                const response = await axios.get('/api/tables');
                this.tables = response.data;
            } catch (error) {
                console.log('fetch table'. error);
            }
        },
        async filterWithCategory (category_id) {
            try {
                const response = await axios.get('/api/products', {
                    params: { category_id: category_id }
                });
                this.products = response.data;
            } catch (error) {
                console.log('fetch filterWithCategory'. error);
            }
        },
        async updateInvoice () {
            try {
                if (this.items.length > 0) {
                    this.form.name = Date.now().toString().slice(-6);
                    this.form.status = 'pending';
                    this.form.items = JSON.stringify(this.items);
                    this.form.price = this.invoiceTotal;
                    this.form.total = this.invoiceTotal;
                    console.log(this.form)
                    const reponsive = await axios.post('/api/invoices', this.form);
                    console.log('update invoice');
                } else {
                    console.log('reject save invoice')
                }
            } catch (error) {
                console.log('add invoice error', error);
            }
        },
        formatPrice (number) {
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + 'đ';
        },
        handleIncrement (product) {
            const hasItem = this.items.find(item => item.id == product.id);
            if (hasItem) {
                const items = this.items.map(item => 
                    item.id === product.id ? { ...item, qty: item.qty + 1, total: item.price * (item.qty + 1)} : item
                );
                this.items = items;
            } else {
                this.items.push({
                    id: product.id,
                    qty: 1,
                    name: product.name,
                    price: product.price,
                    total: product.price
                });
            }
            this.showChoseQtyItem.qty++;
            this.caculateInvoiceTotal();
        },
        handleDecrement (product) {
            if (this.showChoseQtyItem.qty == 0) return;
            if (this.showChoseQtyItem.qty == 1) {
                const newItems = this.items.filter(item => item.id != product.id);
                this.items = newItems;
                this.showChoseQtyItem.qty = 0;
                return;
            }
            const hasItem = this.items.find(item => item.id == product.id);
            if (hasItem) {
                const items = this.items.map(item => 
                    item.id === product.id ? { ...item, qty: item.qty - 1, total: item.price * (item.qty - 1) } : item
                );
                this.items = items;
                this.showChoseQtyItem.qty--;
            }
            this.caculateInvoiceTotal();
        },
        choseItem (productId) {
            let hasItem = this.items.find(item => item.id == productId);
            if (hasItem) {
                this.showChoseQtyItem.id = hasItem.id;
                this.showChoseQtyItem.qty = hasItem.qty;
            } else {
                this.showChoseQtyItem.id = productId;
                this.showChoseQtyItem.qty = 0;
            }
        },
        remoteItem (productId) {
            let newItems = this.items.filter(item => item.id != productId);
            this.items = newItems;
            this.caculateInvoiceTotal();
        },
        caculateInvoiceTotal () {
            this.invoiceTotal = this.items.reduce((accumulator, currentValue) => accumulator + (currentValue.price * currentValue.qty), 0);
        }
    }
  }
</script>

<style lang="scss" scoped>

</style>