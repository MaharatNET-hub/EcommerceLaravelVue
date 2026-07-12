<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { formatDate, formatPrice } from '@/helpers/format';

defineOptions({ layout: AdminLayout });

const props = defineProps({
    stats: Object,
    recentOrders: Array,
    lowStockProducts: Array,
});

const page = usePage();
const currencySymbol = computed(() => page.props.siteSettings?.currency_symbol || '$');

const cards = computed(() => [
    { label: 'المنتجات', value: props.stats.products },
    { label: 'التصنيفات', value: props.stats.categories },
    { label: 'الطلبات', value: props.stats.orders },
    { label: 'العملاء', value: props.stats.customers },
    { label: 'طلبات قيد الانتظار', value: props.stats.pendingOrders },
    { label: 'الإيرادات', value: formatPrice(props.stats.revenue, currencySymbol.value) },
]);
</script>

<template>
    <h1 class="mb-6 text-2xl font-extrabold text-gray-800">لوحة التحكم</h1>

    <div class="mb-8 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
        <div v-for="card in cards" :key="card.label" class="rounded-xl bg-white p-4 shadow-sm">
            <p class="text-xs text-gray-500">{{ card.label }}</p>
            <p class="mt-1 text-xl font-bold text-gray-800">{{ card.value }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div class="rounded-xl bg-white p-5 shadow-sm">
            <h2 class="mb-3 font-bold text-gray-800">أحدث الطلبات</h2>
            <table class="w-full text-sm">
                <tbody>
                    <tr v-for="order in recentOrders" :key="order.id" class="border-t border-gray-100">
                        <td class="py-2">
                            <Link :href="route('admin.orders.show', order.id)" class="text-brand-600 hover:underline">
                                {{ order.order_number }}
                            </Link>
                        </td>
                        <td class="py-2 text-gray-500">{{ order.user?.name }}</td>
                        <td class="py-2 text-gray-500">{{ formatDate(order.created_at) }}</td>
                        <td class="py-2 font-medium">{{ formatPrice(order.total, currencySymbol) }}</td>
                    </tr>
                </tbody>
            </table>
            <p v-if="!recentOrders.length" class="py-6 text-center text-sm text-gray-400">لا توجد طلبات بعد.</p>
        </div>

        <div class="rounded-xl bg-white p-5 shadow-sm">
            <h2 class="mb-3 font-bold text-gray-800">منتجات منخفضة المخزون</h2>
            <table class="w-full text-sm">
                <tbody>
                    <tr v-for="product in lowStockProducts" :key="product.id" class="border-t border-gray-100">
                        <td class="py-2">
                            <Link :href="route('admin.products.edit', product.id)" class="text-brand-600 hover:underline">
                                {{ product.name }}
                            </Link>
                        </td>
                        <td class="py-2 text-end font-medium text-red-600">{{ product.stock_quantity }} متبقي</td>
                    </tr>
                </tbody>
            </table>
            <p v-if="!lowStockProducts.length" class="py-6 text-center text-sm text-gray-400">لا توجد منتجات منخفضة المخزون.</p>
        </div>
    </div>
</template>
