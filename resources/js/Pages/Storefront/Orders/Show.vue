<script setup>
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue';
import Seo from '@/Components/Seo.vue';
import { formatDate, formatPrice } from '@/helpers/format';
import { useTrans } from '@/composables/useTrans';

defineOptions({ layout: StorefrontLayout });

const props = defineProps({ order: Object });

const { t, locale } = useTrans();
const page = usePage();
const currencySymbol = computed(() => page.props.siteSettings?.currency_symbol || '$');

const statusLabels = computed(() => ({
    pending: t('orders.status_pending'),
    processing: t('orders.status_processing'),
    shipped: t('orders.status_shipped'),
    delivered: t('orders.status_delivered'),
    cancelled: t('orders.status_cancelled'),
}));
</script>

<template>
    <Seo :title="`${t('orders.order_number')} ${order.order_number}`" robots="noindex,follow" />

    <div class="mx-auto max-w-3xl px-4 py-10">
        <h1 class="mb-1 text-2xl font-extrabold text-gray-800">{{ t('orders.order_number') }} {{ order.order_number }}</h1>
        <p class="mb-6 text-sm text-gray-500">{{ formatDate(order.created_at, locale) }} — {{ statusLabels[order.status] || order.status }}</p>

        <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div class="rounded-lg border border-gray-100 p-4">
                <h3 class="mb-2 font-semibold text-gray-700">{{ t('orders.shipping_address') }}</h3>
                <p class="text-sm text-gray-600">{{ order.shipping_address.full_name }}</p>
                <p class="text-sm text-gray-600">{{ order.shipping_address.phone }}</p>
                <p class="text-sm text-gray-600">{{ order.shipping_address.address_line }}, {{ order.shipping_address.city }}, {{ order.shipping_address.country }}</p>
            </div>
            <div class="rounded-lg border border-gray-100 p-4">
                <h3 class="mb-2 font-semibold text-gray-700">{{ t('orders.payment') }}</h3>
                <p class="text-sm text-gray-600">{{ t('orders.payment_cod') }}</p>
                <p class="text-sm text-gray-600">{{ t('orders.payment_status') }}: {{ order.payment_status }}</p>
            </div>
        </div>

        <div class="overflow-x-auto rounded-xl border border-gray-100">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500">
                    <tr>
                        <th class="p-3 text-start">{{ t('orders.product') }}</th>
                        <th class="p-3 text-start">{{ t('orders.quantity') }}</th>
                        <th class="p-3 text-start">{{ t('orders.price') }}</th>
                        <th class="p-3 text-start">{{ t('orders.item_total') }}</th>
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
            <div class="flex justify-between text-gray-600"><span>{{ t('orders.subtotal') }}</span><span>{{ formatPrice(order.subtotal, currencySymbol) }}</span></div>
            <div v-if="order.discount > 0" class="flex justify-between text-gray-600"><span>{{ t('orders.discount') }}</span><span>-{{ formatPrice(order.discount, currencySymbol) }}</span></div>
            <div class="flex justify-between text-gray-600"><span>{{ t('checkout.shipping') }}</span><span>{{ formatPrice(order.shipping_fee, currencySymbol) }}</span></div>
            <div class="flex justify-between text-base font-bold text-gray-800"><span>{{ t('checkout.total') }}</span><span>{{ formatPrice(order.total, currencySymbol) }}</span></div>
        </div>
    </div>
</template>
