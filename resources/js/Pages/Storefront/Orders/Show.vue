<script setup>
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue';
import Seo from '@/Components/Seo.vue';
import { formatDate, formatPrice } from '@/helpers/format';

defineOptions({ layout: StorefrontLayout });

const props = defineProps({ order: Object });

const page = usePage();
const currencySymbol = computed(() => page.props.siteSettings?.currency_symbol || '$');

const statusLabels = {
    pending: 'قيد الانتظار',
    processing: 'قيد التجهيز',
    shipped: 'تم الشحن',
    delivered: 'تم التوصيل',
    cancelled: 'ملغي',
};
</script>

<template>
    <Seo :title="`طلب رقم ${order.order_number}`" robots="noindex,follow" />

    <div class="mx-auto max-w-3xl px-4 py-10">
        <h1 class="mb-1 text-2xl font-extrabold text-gray-800">طلب رقم {{ order.order_number }}</h1>
        <p class="mb-6 text-sm text-gray-500">{{ formatDate(order.created_at) }} — {{ statusLabels[order.status] || order.status }}</p>

        <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div class="rounded-lg border border-gray-100 p-4">
                <h3 class="mb-2 font-semibold text-gray-700">عنوان الشحن</h3>
                <p class="text-sm text-gray-600">{{ order.shipping_address.full_name }}</p>
                <p class="text-sm text-gray-600">{{ order.shipping_address.phone }}</p>
                <p class="text-sm text-gray-600">{{ order.shipping_address.address_line }}, {{ order.shipping_address.city }}, {{ order.shipping_address.country }}</p>
            </div>
            <div class="rounded-lg border border-gray-100 p-4">
                <h3 class="mb-2 font-semibold text-gray-700">الدفع</h3>
                <p class="text-sm text-gray-600">الدفع عند الاستلام</p>
                <p class="text-sm text-gray-600">حالة الدفع: {{ order.payment_status }}</p>
            </div>
        </div>

        <div class="overflow-x-auto rounded-xl border border-gray-100">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500">
                    <tr>
                        <th class="p-3 text-start">المنتج</th>
                        <th class="p-3 text-start">الكمية</th>
                        <th class="p-3 text-start">السعر</th>
                        <th class="p-3 text-start">الإجمالي</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in order.items" :key="item.id" class="border-t border-gray-100">
                        <td class="p-3 text-gray-700">{{ item.product_name }}</td>
                        <td class="p-3 text-gray-500">{{ item.quantity }}</td>
                        <td class="p-3 text-gray-500">{{ formatPrice(item.price, currencySymbol) }}</td>
                        <td class="p-3 font-medium text-gray-700">{{ formatPrice(item.total, currencySymbol) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-4 ms-auto max-w-xs space-y-1 text-sm">
            <div class="flex justify-between text-gray-600"><span>المجموع الفرعي</span><span>{{ formatPrice(order.subtotal, currencySymbol) }}</span></div>
            <div v-if="order.discount > 0" class="flex justify-between text-gray-600"><span>الخصم</span><span>-{{ formatPrice(order.discount, currencySymbol) }}</span></div>
            <div class="flex justify-between text-gray-600"><span>الشحن</span><span>{{ formatPrice(order.shipping_fee, currencySymbol) }}</span></div>
            <div class="flex justify-between text-base font-bold text-gray-800"><span>الإجمالي</span><span>{{ formatPrice(order.total, currencySymbol) }}</span></div>
        </div>
    </div>
</template>
