<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue';
import Seo from '@/Components/Seo.vue';
import Pagination from '@/Components/Pagination.vue';
import { formatDate, formatPrice } from '@/helpers/format';
import { useTrans } from '@/composables/useTrans';

defineOptions({ layout: StorefrontLayout });

defineProps({ orders: Object });

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
    <Seo :title="t('orders.title')" robots="noindex,follow" />

    <div class="mx-auto max-w-4xl px-4 py-10">
        <h1 class="mb-6 text-2xl font-extrabold text-gray-800">{{ t('orders.title') }}</h1>

        <div v-if="!orders.data.length" class="rounded-xl border border-dashed border-gray-300 py-16 text-center text-gray-500">
            {{ t('orders.empty') }}
        </div>

        <div v-else class="overflow-x-auto rounded-xl border border-gray-100">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500">
                    <tr>
                        <th class="p-3 text-start">{{ t('orders.order_number') }}</th>
                        <th class="p-3 text-start">{{ t('orders.date') }}</th>
                        <th class="p-3 text-start">{{ t('orders.status') }}</th>
                        <th class="p-3 text-start">{{ t('orders.total') }}</th>
                        <th class="p-3"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="order in orders.data" :key="order.id" class="border-t border-gray-100">
                        <td class="p-3 font-medium text-gray-700">{{ order.order_number }}</td>
                        <td class="p-3 text-gray-500">{{ formatDate(order.created_at, locale) }}</td>
                        <td class="p-3 text-gray-500">{{ statusLabels[order.status] || order.status }}</td>
                        <td class="p-3 font-semibold text-brand-600">{{ formatPrice(order.total, currencySymbol) }}</td>
                        <td class="p-3">
                            <Link :href="route('orders.show', order.id)" class="text-brand-600 hover:underline">{{ t('orders.details') }}</Link>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <Pagination :links="orders.links" />
    </div>
</template>
