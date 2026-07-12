<script setup>
import { useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { formatDate, formatPrice } from '@/helpers/format';

defineOptions({ layout: AdminLayout });

const props = defineProps({
    order: Object,
    statuses: Array,
});

const form = useForm({
    status: props.order.status,
    payment_status: props.order.payment_status,
});

const statusLabels = {
    pending: 'قيد الانتظار',
    processing: 'قيد التجهيز',
    shipped: 'تم الشحن',
    delivered: 'تم التوصيل',
    cancelled: 'ملغي',
};

function submit() {
    form.patch(route('admin.orders.update', props.order.id));
}
</script>

<template>
    <h1 class="mb-1 text-2xl font-extrabold text-gray-800">طلب رقم {{ order.order_number }}</h1>
    <p class="mb-6 text-sm text-gray-500">{{ formatDate(order.created_at) }}</p>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <div class="rounded-xl bg-white p-5 shadow-sm">
                <h2 class="mb-3 font-bold text-gray-800">المنتجات</h2>
                <table class="w-full text-sm">
                    <thead class="text-gray-500">
                        <tr>
                            <th class="p-2 text-start">المنتج</th>
                            <th class="p-2 text-start">الكمية</th>
                            <th class="p-2 text-start">السعر</th>
                            <th class="p-2 text-start">الإجمالي</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in order.items" :key="item.id" class="border-t border-gray-100">
                            <td class="p-2 text-gray-700">{{ item.product_name }}</td>
                            <td class="p-2 text-gray-500">{{ item.quantity }}</td>
                            <td class="p-2 text-gray-500">{{ formatPrice(item.price) }}</td>
                            <td class="p-2 font-medium">{{ formatPrice(item.total) }}</td>
                        </tr>
                    </tbody>
                </table>
                <div class="mt-4 ms-auto max-w-xs space-y-1 border-t border-gray-100 pt-3 text-sm">
                    <div class="flex justify-between text-gray-600"><span>المجموع الفرعي</span><span>{{ formatPrice(order.subtotal) }}</span></div>
                    <div v-if="order.discount > 0" class="flex justify-between text-gray-600"><span>الخصم</span><span>-{{ formatPrice(order.discount) }}</span></div>
                    <div class="flex justify-between text-gray-600"><span>الشحن</span><span>{{ formatPrice(order.shipping_fee) }}</span></div>
                    <div class="flex justify-between text-base font-bold text-gray-800"><span>الإجمالي</span><span>{{ formatPrice(order.total) }}</span></div>
                </div>
            </div>

            <div class="rounded-xl bg-white p-5 shadow-sm">
                <h2 class="mb-3 font-bold text-gray-800">عنوان الشحن</h2>
                <p class="text-sm text-gray-600">{{ order.shipping_address.full_name }} — {{ order.shipping_address.phone }}</p>
                <p class="text-sm text-gray-600">{{ order.shipping_address.address_line }}, {{ order.shipping_address.city }}, {{ order.shipping_address.country }}</p>
                <p v-if="order.notes" class="mt-2 text-sm text-gray-500">ملاحظات: {{ order.notes }}</p>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-xl bg-white p-5 shadow-sm">
                <h2 class="mb-3 font-bold text-gray-800">العميل</h2>
                <p class="text-sm text-gray-600">{{ order.user?.name }}</p>
                <p class="text-sm text-gray-600">{{ order.user?.email }}</p>
            </div>

            <form @submit.prevent="submit" class="rounded-xl bg-white p-5 shadow-sm">
                <h2 class="mb-3 font-bold text-gray-800">تحديث الحالة</h2>
                <label class="mb-1 block text-sm font-medium text-gray-700">حالة الطلب</label>
                <select v-model="form.status" class="mb-3 w-full rounded-lg border-gray-300 text-sm">
                    <option v-for="s in statuses" :key="s" :value="s">{{ statusLabels[s] || s }}</option>
                </select>

                <label class="mb-1 block text-sm font-medium text-gray-700">حالة الدفع</label>
                <select v-model="form.payment_status" class="mb-4 w-full rounded-lg border-gray-300 text-sm">
                    <option value="pending">قيد الانتظار</option>
                    <option value="paid">مدفوع</option>
                    <option value="refunded">مسترجع</option>
                    <option value="failed">فشل</option>
                </select>

                <button type="submit" :disabled="form.processing" class="w-full rounded-lg bg-brand-600 py-2 text-white hover:bg-brand-700 disabled:opacity-50">
                    حفظ
                </button>
            </form>
        </div>
    </div>
</template>
