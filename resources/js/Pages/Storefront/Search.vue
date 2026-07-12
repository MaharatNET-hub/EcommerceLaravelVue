<script setup>
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue';
import Seo from '@/Components/Seo.vue';
import ProductCard from '@/Components/ProductCard.vue';
import Pagination from '@/Components/Pagination.vue';

defineOptions({ layout: StorefrontLayout });

defineProps({
    products: Object,
    query: String,
});
</script>

<template>
    <Seo :title="`نتائج البحث عن: ${query}`" robots="noindex,follow" />

    <div class="mx-auto max-w-7xl px-4 py-10">
        <h1 class="mb-6 text-2xl font-extrabold text-gray-800">نتائج البحث عن "{{ query }}"</h1>

        <div v-if="products.data.length" class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
            <ProductCard v-for="product in products.data" :key="product.id" :product="product" />
        </div>
        <p v-else class="py-12 text-center text-gray-500">لم يتم العثور على منتجات مطابقة.</p>

        <Pagination :links="products.links" />
    </div>
</template>
