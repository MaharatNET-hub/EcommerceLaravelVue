<script setup>
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import DeleteButton from '@/Components/Admin/DeleteButton.vue';
import { resolveImage } from '@/helpers/format';

defineOptions({ layout: AdminLayout });

const props = defineProps({
    brands: Object,
    filters: Object,
});

const search = ref(props.filters.search || '');

function applySearch() {
    router.get(route('admin.brands.index'), { search: search.value }, { preserveState: true });
}
</script>

<template>
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-extrabold text-gray-800">البراندات</h1>
        <Link :href="route('admin.brands.create')" class="rounded-lg bg-brand-600 px-4 py-2 text-sm text-white hover:bg-brand-700">
            + براند جديد
        </Link>
    </div>

    <div class="mb-4">
        <input v-model="search" @keyup.enter="applySearch" type="search" placeholder="ابحث..." class="w-full max-w-xs rounded-lg border-gray-300 text-sm" />
    </div>

    <div class="overflow-x-auto rounded-xl bg-white shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500">
                <tr>
                    <th class="p-3 text-start"></th>
                    <th class="p-3 text-start">الاسم</th>
                    <th class="p-3 text-start">المنتجات</th>
                    <th class="p-3 text-start">الحالة</th>
                    <th class="p-3"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="brand in brands.data" :key="brand.id" class="border-t border-gray-100">
                    <td class="p-3"><img v-if="brand.logo" :src="resolveImage(brand.logo)" class="h-10 w-10 rounded-lg object-cover" /></td>
                    <td class="p-3 font-medium text-gray-700">{{ brand.name }}</td>
                    <td class="p-3 text-gray-500">{{ brand.products_count }}</td>
                    <td class="p-3">
                        <span class="rounded-full px-2 py-1 text-xs" :class="brand.is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500'">
                            {{ brand.is_active ? 'مفعّل' : 'معطّل' }}
                        </span>
                    </td>
                    <td class="p-3 text-end">
                        <div class="flex justify-end gap-3">
                            <Link :href="route('admin.brands.edit', brand.id)" class="text-sm text-brand-600 hover:underline">تعديل</Link>
                            <DeleteButton :href="route('admin.brands.destroy', brand.id)" />
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <Pagination :links="brands.links" />
</template>
