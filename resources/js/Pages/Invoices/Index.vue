<template>
  <div>
      <Sidebar></Sidebar>
      <div v-if="loading" class="text-green-500 text-center">Loading...</div>        
      <div v-else class="p-4 sm:ml-52">
          <Link 
              :href="route('create-invoice')" 
              class="flex w-max gap-2 mb-4 mt-4 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mr-2" 
              aria-current="page"
          >
              <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 7.757v8.486M7.757 12h8.486M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
              </svg>
              <span>Tạo hóa đơn</span>
          </Link>
          <table class="w-full text-sm text-left rtl:text-right text-gray-500 white:text-gray-400">
              <thead class="text-xs text-gray-700 uppercase bg-gray-50 white:bg-gray-700 white:text-gray-400">
                  <tr>
                      <th scope="col" class="px-6 py-3">
                          Tên
                      </th>
                      <th scope="col" class="px-6 py-3">
                          Trạng thái
                      </th>
                      <th scope="col" class="px-6 py-3">
                          Ngày tạo
                      </th>
                        <th scope="col" class="px-6 py-3">
                          Phương thức
                      </th>
                        <th scope="col" class="px-6 py-3">
                          Kiểu mua
                      </th>
                      <th scope="col" class="px-6 py-3">
                          Tổng
                      </th>
                      <th scope="col" class="px-6 py-3">
                          Hành động
                      </th>
                  </tr>
              </thead>
              <tbody>
                  <tr class="bg-white border-b white:bg-gray-800 white:border-gray-700 border-gray-200"  v-for="invoice in invoices" :key="invoice.id">
                      <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap white:text-white">
                        {{ invoice.name }}
                      </th>
                      <td class="px-6 py-4">
                        {{ invoice.status }}
                      </td>
                      <td class="px-6 py-4">
                        {{ formatDate(invoice.created_at) }}
                      </td>
                        <td class="px-6 py-4">
                        {{ invoice.payment_method }}
                      </td>
                        <td class="px-6 py-4">
                        {{ invoice.table_id }}
                      </td>
                      <td class="px-6 py-4">
                        {{ formatPrice(invoice.total) }}
                      </td>
                      <td class="px-6 py-4 w-[137px]">
                            <Link 
                                :href="route('invoices.show', invoice.id)" 
                                class="inline-flex bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded mr-2" 
                                aria-current="page"
                            >
                                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z"/>
                                </svg>
                            </Link>
                            <button @click="openConfirmDelete(invoice.id)" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded">
                                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                </svg>
                            </button>
                      </td>
                  </tr>
              </tbody>
          </table>
      </div>
      <div v-if="isConfirmDelete" class="relative z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true">
          <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true"></div>
          <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
              <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
              <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                  <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                  <div class="sm:flex sm:items-start">
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
                      <button @click="deleteInvoice()" type="button" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-red-500 sm:ml-3 sm:w-auto">Delete</button>
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
  import { Link } from '@inertiajs/vue3';
  export default {
      components: {
          Sidebar,
          Link
      },
      data() {
          return {
              invoices: [],
              loading: true,
              error: null,
              isConfirmDelete: false,
              idDelete: null,
          }
      },
      mounted() {
          this.fetchInvoices();
      },
      methods: {
          async fetchInvoices() {
              try {
                  const reponsive = await axios.get('http://127.0.0.1:8000/api/invoices');
                  this.invoices = reponsive.data;
              } catch (error) {
                  console.log('fetch invoices error', error);
                  this.error = error;
              } finally {
                  this.loading = false;
              }
          },
          openConfirmDelete(invoice_id) {
              this.isConfirmDelete = true;
              this.idDelete = invoice_id;
          },
          closeModalDelete() {
              this.isConfirmDelete = false;
              this.idDelete = null;
          },
          async deleteInvoice() {
              try {
                  await axios.delete(`http://127.0.0.1:8000/api/invoices/${this.idDelete}`);
                  this.invoices = this.invoices.filter(product => product.id !== this.idDelete);
                  this.isConfirmDelete = false;
                  this.idDelete = null;
              } catch (error) {
                  console.log('delete invoices error', error);
              }
          },
          formatPrice(price) {
            return price.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' });
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