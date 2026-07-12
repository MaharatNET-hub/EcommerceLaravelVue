<script setup>
import { Link, router } from '@inertiajs/vue3';
import { reactive } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import DeleteButton from '@/Components/Admin/DeleteButton.vue';
import { formatPrice } from '@/helpers/format';

defineOptions({ layout: AdminLayout });

const props = defineProps({
    products: Object,
    categories: Array,
    filters: Object,
});

const filters = reactive({
    search: props.filters.search || '',
    category_id: props.filters.category_id || '',
});

function applyFilters() {
    router.get(route('admin.products.index'), filters, { preserveState: true });
}
</script>

<template>
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-extrabold text-gray-800">المنتجات</h1>
        <Link :href="route('admin.products.create')" class="rounded-lg bg-brand-600 px-4 py-2 text-sm text-white hover:bg-brand-700">
            + منتج جديد
        </Link>
    </div>

    <div class="mb-4 flex flex-wrap gap-3">
        <input
            v-model="filters.search"
            @keyup.enter="applyFilters"
            type="search"
            placeholder="ابحث بالاسم أو SKU..."
            class="w-full max-w-xs rounded-lg border-gray-300 text-sm"
        />
        <select v-model="filters.category_id" @change="applyFilters" class="rounded-lg border-gray-300 text-sm">
            <option value="">كل التصنيفات</option>
            <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
        </select>
    </div>

    <div class="overflow-x-auto rounded-xl bg-white shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500">
                <tr>
                    <th class="p-3 text-start">المنتج</th>
                    <th class="p-3 text-start">SKU</th>
                    <th class="p-3 text-start">التصنيف</th>
                    <th class="p-3 text-start">السعر</th>
                    <th class="p-3 text-start">المخزون</th>
                    <th class="p-3 text-start">الحالة</th>
                    <th class="p-3"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="product in products.data" :key="product.id" class="border-t border-gray-100">
                    <td class="p-3 font-medium text-gray-700">{{ product.name }}</td>
                    <td class="p-3 text-gray-500">{{ product.sku }}</td>
                    <td class="p-3 text-gray-500">{{ product.category?.name || '—' }}</td>
                    <td class="p-3 text-gray-500">
                        <span v-if="product.sale_price" class="text-brand-600">{{ formatPrice(product.sale_price) }}</span>
                        <span v-else>{{ formatPrice(product.price) }}</span>
                    </td>
                    <td class="p-3" :class="product.stock_quantity <= 5 ? 'text-red-600' : 'text-gray-500'">{{ product.stock_quantity }}</td>
                    <td class="p-3">
                        <span class="rounded-full px-2 py-1 text-xs" :class="product.is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500'">
                            {{ product.is_active ? 'مفعّل' : 'معطّل' }}
                        </span>
                    </td>
                    <td class="p-3 text-end">
                        <div class="flex justify-end gap-3">
                            <Link :href="route('admin.products.edit', product.id)" class="text-sm text-brand-600 hover:underline">تعديل</Link>
                            <DeleteButton :href="route('admin.products.destroy', product.id)" />
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <Pagination :links="products.links" />
</template>
