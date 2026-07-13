<script setup>
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue';
import Seo from '@/Components/Seo.vue';
import ProductCard from '@/Components/ProductCard.vue';
import Pagination from '@/Components/Pagination.vue';
import { resolveImage } from '@/helpers/format';
import { useTrans } from '@/composables/useTrans';

defineOptions({ layout: StorefrontLayout });

const { t } = useTrans();

const props = defineProps({
    brand: Object,
    products: Object,
});

const seo = props.brand.seo;
</script>

<template>
    <Seo
        :title="seo?.meta_title || brand.name"
        :description="seo?.meta_description || brand.description"
        :og-image="seo?.og_image || brand.logo"
    />

    <div class="mx-auto max-w-7xl px-4 py-10">
        <div class="mb-8 flex items-center gap-4">
            <img :src="resolveImage(brand.logo)" :alt="brand.name" class="h-16" />
            <div>
                <h1 class="text-2xl font-extrabold text-gray-800">{{ brand.name }}</h1>
                <p class="text-sm text-gray-500">{{ brand.description }}</p>
            </div>
        </div>

        <div v-if="products.data.length" class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
            <ProductCard v-for="product in products.data" :key="product.id" :product="product" />
        </div>
        <p v-else class="py-12 text-center text-gray-500">{{ t('brand.no_products') }}</p>

        <Pagination :links="products.links" />
    </div>
</template>
