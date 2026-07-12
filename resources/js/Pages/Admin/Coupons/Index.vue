<script setup>
import { Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import DeleteButton from '@/Components/Admin/DeleteButton.vue';
import { formatPrice } from '@/helpers/format';

defineOptions({ layout: AdminLayout });

defineProps({ coupons: Object });
</script>

<template>
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-extrabold text-gray-800">كوبونات الخصم</h1>
        <Link :href="route('admin.coupons.create')" class="rounded-lg bg-brand-600 px-4 py-2 text-sm text-white hover:bg-brand-700">
            + كوبون جديد
        </Link>
    </div>

    <div class="overflow-x-auto rounded-xl bg-white shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500">
                <tr>
                    <th class="p-3 text-start">الكود</th>
                    <th class="p-3 text-start">القيمة</th>
                    <th class="p-3 text-start">مرات الاستخدام</th>
                    <th class="p-3 text-start">الحالة</th>
                    <th class="p-3"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="coupon in coupons.data" :key="coupon.id" class="border-t border-gray-100">
                    <td class="p-3 font-medium text-gray-700">{{ coupon.code }}</td>
                    <td class="p-3 text-gray-500">
                        {{ coupon.type === 'percent' ? coupon.value + '%' : formatPrice(coupon.value) }}
                    </td>
                    <td class="p-3 text-gray-500">{{ coupon.used_count }} / {{ coupon.max_uses || '∞' }}</td>
                    <td class="p-3">
                        <span class="rounded-full px-2 py-1 text-xs" :class="coupon.is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500'">
                            {{ coupon.is_active ? 'مفعّل' : 'معطّل' }}
                        </span>
                    </td>
                    <td class="p-3 text-end">
                        <div class="flex justify-end gap-3">
                            <Link :href="route('admin.coupons.edit', coupon.id)" class="text-sm text-brand-600 hover:underline">تعديل</Link>
                            <DeleteButton :href="route('admin.coupons.destroy', coupon.id)" />
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <Pagination :links="coupons.links" />
</template>
