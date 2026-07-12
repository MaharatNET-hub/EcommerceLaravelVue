<script setup>
import { Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import DeleteButton from '@/Components/Admin/DeleteButton.vue';

defineOptions({ layout: AdminLayout });

defineProps({ pages: Object });
</script>

<template>
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-extrabold text-gray-800">صفحات المحتوى</h1>
        <Link :href="route('admin.pages.create')" class="rounded-lg bg-brand-600 px-4 py-2 text-sm text-white hover:bg-brand-700">
            + صفحة جديدة
        </Link>
    </div>

    <div class="overflow-x-auto rounded-xl bg-white shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500">
                <tr>
                    <th class="p-3 text-start">العنوان</th>
                    <th class="p-3 text-start">الرابط</th>
                    <th class="p-3 text-start">الحالة</th>
                    <th class="p-3"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="p in pages.data" :key="p.id" class="border-t border-gray-100">
                    <td class="p-3 font-medium text-gray-700">{{ p.title }}</td>
                    <td class="p-3 text-gray-500">/p/{{ p.slug }}</td>
                    <td class="p-3">
                        <span class="rounded-full px-2 py-1 text-xs" :class="p.is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500'">
                            {{ p.is_active ? 'منشورة' : 'مسودة' }}
                        </span>
                    </td>
                    <td class="p-3 text-end">
                        <div class="flex justify-end gap-3">
                            <Link :href="route('admin.pages.edit', p.id)" class="text-sm text-brand-600 hover:underline">تعديل</Link>
                            <DeleteButton :href="route('admin.pages.destroy', p.id)" />
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <Pagination :links="pages.links" />
</template>
