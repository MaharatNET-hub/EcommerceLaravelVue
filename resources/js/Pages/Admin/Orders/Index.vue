<script setup>
import { Link, router } from '@inertiajs/vue3';
import { reactive } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { formatDate, formatPrice } from '@/helpers/format';

defineOptions({ layout: AdminLayout });

const props = defineProps({
    orders: Object,
    statuses: Array,
    filters: Object,
});

const filters = reactive({
    search: props.filters.search || '',
    status: props.filters.status || '',
});

function applyFilters() {
    router.get(route('admin.orders.index'), filters, { preserveState: true });
}

const statusLabels = {
    pending: 'قيد الانتظار',
    processing: 'قيد التجهيز',
    shipped: 'تم الشحن',
    delivered: 'تم التوصيل',
    cancelled: 'ملغي',
};
</script>

<template>
    <h1 class="mb-6 text-2xl font-extrabold text-gray-800">الطلبات</h1>

    <div class="mb-4 flex flex-wrap gap-3">
        <input v-model="filters.search" @keyup.enter="applyFilters" type="search" placeholder="رقم الطلب..." class="rounded-lg border-gray-300 text-sm" />
        <select v-model="filters.status" @change="applyFilters" class="rounded-lg border-gray-300 text-sm">
            <option value="">كل الحالات</option>
            <option v-for="s in statuses" :key="s" :value="s">{{ statusLabels[s] || s }}</option>
        </select>
    </div>

    <div class="overflow-x-auto rounded-xl bg-white shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500">
                <tr>
                    <th class="p-3 text-start">رقم الطلب</th>
                    <th class="p-3 text-start">العميل</th>
                    <th class="p-3 text-start">التاريخ</th>
                    <th class="p-3 text-start">الحالة</th>
                    <th class="p-3 text-start">الإجمالي</th>
                    <th class="p-3"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="order in orders.data" :key="order.id" class="border-t border-gray-100">
                    <td class="p-3 font-medium text-gray-700">{{ order.order_number }}</td>
                    <td class="p-3 text-gray-500">{{ order.user?.name }}</td>
                    <td class="p-3 text-gray-500">{{ formatDate(order.created_at) }}</td>
                    <td class="p-3 text-gray-500">{{ statusLabels[order.status] || order.status }}</td>
                    <td class="p-3 font-medium">{{ formatPrice(order.total) }}</td>
                    <td class="p-3 text-end">
                        <Link :href="route('admin.orders.show', order.id)" class="text-sm text-brand-600 hover:underline">عرض</Link>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <Pagination :links="orders.links" />
</template>
