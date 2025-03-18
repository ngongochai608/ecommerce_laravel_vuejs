<template>
    <div>
        <Sidebar></Sidebar>
        <div v-if="loading" class="text-green-500 text-center">Loading Create Invoice...</div>        
        <div class="flex h-screen sm:ml-64">
            <div class="bg-red-600 w-4/6">
                <div class="flex">
                    <button v-for="category in categories" :key="category.id">
                        {{ category.name }}
                    </button>
                </div>
            </div>
            <div class="bg-amber-500 w-2/6"></div>
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
                categories: []
            }
        },
        mounted() {
            this.fetchCategories();
        },
        methods: {
            async fetchCategories () {
                try {
                    const response = await axios.get('http://127.0.0.1:8000/api/categories');
                    this.categories = response.data;
                } catch ($error) {
                    console.log('fetch categories'. $error);
                }
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>