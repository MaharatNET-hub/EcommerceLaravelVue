<script setup>
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import DeleteButton from '@/Components/Admin/DeleteButton.vue';
import { resolveImage } from '@/helpers/format';

defineOptions({ layout: AdminLayout });

const props = defineProps({
    categories: Object,
    filters: Object,
});

const search = ref(props.filters.search || '');

function applySearch() {
    router.get(route('admin.categories.index'), { search: search.value }, { preserveState: true });
}
</script>

<template>
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-extrabold text-gray-800">التصنيفات</h1>
        <Link :href="route('admin.categories.create')" class="rounded-lg bg-brand-600 px-4 py-2 text-sm text-white hover:bg-brand-700">
            + تصنيف جديد
        </Link>
    </div>

    <div class="mb-4">
        <input
            v-model="search"
            @keyup.enter="applySearch"
            type="search"
            placeholder="ابحث عن تصنيف..."
            class="w-full max-w-xs rounded-lg border-gray-300 text-sm"
        />
    </div>

    <div class="overflow-x-auto rounded-xl bg-white shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500">
                <tr>
                    <th class="p-3 text-start"></th>
                    <th class="p-3 text-start">الاسم</th>
                    <th class="p-3 text-start">التصنيف الأب</th>
                    <th class="p-3 text-start">المنتجات</th>
                    <th class="p-3 text-start">الحالة</th>
                    <th class="p-3"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="category in categories.data" :key="category.id" class="border-t border-gray-100">
                    <td class="p-3">
                        <img v-if="category.image" :src="resolveImage(category.image)" class="h-10 w-10 rounded-lg object-cover" />
                    </td>
                    <td class="p-3 font-medium text-gray-700">{{ category.name }}</td>
                    <td class="p-3 text-gray-500">{{ category.parent?.name || '—' }}</td>
                    <td class="p-3 text-gray-500">{{ category.products_count }}</td>
                    <td class="p-3">
                        <span
                            class="rounded-full px-2 py-1 text-xs"
                            :class="category.is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500'"
                        >
                            {{ category.is_active ? 'مفعّل' : 'معطّل' }}
                        </span>
                    </td>
                    <td class="p-3 text-end">
                        <div class="flex justify-end gap-3">
                            <Link :href="route('admin.categories.edit', category.id)" class="text-sm text-brand-600 hover:underline">تعديل</Link>
                            <DeleteButton :href="route('admin.categories.destroy', category.id)" />
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <Pagination :links="categories.links" />
</template>
