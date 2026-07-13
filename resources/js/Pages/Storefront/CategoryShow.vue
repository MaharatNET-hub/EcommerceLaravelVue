<script setup>
import { Link, router } from '@inertiajs/vue3';
import { reactive } from 'vue';
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue';
import Seo from '@/Components/Seo.vue';
import ProductCard from '@/Components/ProductCard.vue';
import Pagination from '@/Components/Pagination.vue';
import { useTrans } from '@/composables/useTrans';

defineOptions({ layout: StorefrontLayout });

const { t } = useTrans();

const props = defineProps({
    category: Object,
    products: Object,
    brands: Array,
    filters: Object,
    breadcrumbs: Array,
});

const form = reactive({
    brand_id: props.filters.brand_id || '',
    min_price: props.filters.min_price || '',
    max_price: props.filters.max_price || '',
    sort: props.filters.sort || '',
});

function applyFilters() {
    router.get(route('categories.show', props.category.slug), form, { preserveState: true });
}

const seo = props.category.seo;
</script>

<template>
    <Seo
        :title="seo?.meta_title || category.name"
        :description="seo?.meta_description"
        :og-image="seo?.og_image || category.image"
    />

    <div class="mx-auto max-w-7xl px-4 py-6">
        <nav class="mb-4 text-sm text-gray-500">
            <Link :href="route('home')" class="hover:text-brand-600">{{ t('common.back_home') }}</Link>
            <template v-for="crumb in breadcrumbs" :key="crumb.slug">
                <span class="mx-1">/</span>
                <Link :href="route('categories.show', crumb.slug)" class="hover:text-brand-600">{{ crumb.name }}</Link>
            </template>
        </nav>

        <h1 class="mb-6 text-2xl font-extrabold text-gray-800">{{ category.name }}</h1>

        <div v-if="category.children?.length" class="mb-6 flex flex-wrap gap-2">
            <Link
                v-for="child in category.children"
                :key="child.id"
                :href="route('categories.show', child.slug)"
                class="rounded-full border border-gray-200 px-4 py-1.5 text-sm text-gray-600 hover:border-brand-400 hover:text-brand-600"
            >
                {{ child.name }}
            </Link>
        </div>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-4">
            <aside class="space-y-6 lg:col-span-1">
                <div>
                    <h3 class="mb-2 font-semibold text-gray-800">{{ t('category.brand') }}</h3>
                    <select v-model="form.brand_id" @change="applyFilters" class="w-full rounded-lg border-gray-300 text-sm">
                        <option value="">{{ t('category.all') }}</option>
                        <option v-for="brand in brands" :key="brand.id" :value="brand.id">{{ brand.name }}</option>
                    </select>
                </div>
                <div>
                    <h3 class="mb-2 font-semibold text-gray-800">{{ t('category.price_range') }}</h3>
                    <div class="flex items-center gap-2">
                        <input v-model="form.min_price" type="number" :placeholder="t('category.from')" class="w-full rounded-lg border-gray-300 text-sm" />
                        <input v-model="form.max_price" type="number" :placeholder="t('category.to')" class="w-full rounded-lg border-gray-300 text-sm" />
                    </div>
                    <button @click="applyFilters" class="mt-2 w-full rounded-lg bg-gray-800 py-1.5 text-sm text-white hover:bg-gray-900">
                        {{ t('category.apply') }}
                    </button>
                </div>
            </aside>

            <div class="lg:col-span-3">
                <div class="mb-4 flex items-center justify-between">
                    <p class="text-sm text-gray-500">{{ products.total }} {{ t('category.products_count') }}</p>
                    <select v-model="form.sort" @change="applyFilters" class="rounded-lg border-gray-300 text-sm">
                        <option value="">{{ t('category.sort_default') }}</option>
                        <option value="newest">{{ t('category.sort_newest') }}</option>
                        <option value="price_asc">{{ t('category.sort_price_asc') }}</option>
                        <option value="price_desc">{{ t('category.sort_price_desc') }}</option>
                    </select>
                </div>

                <div v-if="products.data.length" class="grid grid-cols-2 gap-4 sm:grid-cols-3">
                    <ProductCard v-for="product in products.data" :key="product.id" :product="product" />
                </div>
                <p v-else class="py-12 text-center text-gray-500">{{ t('category.no_products') }}</p>

                <Pagination :links="products.links" />
            </div>
        </div>
    </div>
</template>
