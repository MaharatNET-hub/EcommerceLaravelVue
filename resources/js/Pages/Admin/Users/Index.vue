<script setup>
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import DeleteButton from '@/Components/Admin/DeleteButton.vue';

defineOptions({ layout: AdminLayout });

const props = defineProps({
    users: Object,
    filters: Object,
});

const search = ref(props.filters.search || '');

function applySearch() {
    router.get(route('admin.users.index'), { search: search.value }, { preserveState: true });
}
</script>

<template>
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-extrabold text-gray-800">المستخدمون</h1>
        <Link :href="route('admin.users.create')" class="rounded-lg bg-brand-600 px-4 py-2 text-sm text-white hover:bg-brand-700">
            + مستخدم جديد
        </Link>
    </div>

    <div class="mb-4">
        <input v-model="search" @keyup.enter="applySearch" type="search" placeholder="ابحث بالاسم أو البريد..." class="w-full max-w-xs rounded-lg border-gray-300 text-sm" />
    </div>

    <div class="overflow-x-auto rounded-xl bg-white shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500">
                <tr>
                    <th class="p-3 text-start">الاسم</th>
                    <th class="p-3 text-start">البريد الإلكتروني</th>
                    <th class="p-3 text-start">الأدوار</th>
                    <th class="p-3 text-start">الحالة</th>
                    <th class="p-3"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="user in users.data" :key="user.id" class="border-t border-gray-100">
                    <td class="p-3 font-medium text-gray-700">{{ user.name }}</td>
                    <td class="p-3 text-gray-500">{{ user.email }}</td>
                    <td class="p-3 text-gray-500">{{ user.roles.map(r => r.name).join(', ') || '—' }}</td>
                    <td class="p-3">
                        <span class="rounded-full px-2 py-1 text-xs" :class="user.is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500'">
                            {{ user.is_active ? 'مفعّل' : 'معطّل' }}
                        </span>
                    </td>
                    <td class="p-3 text-end">
                        <div class="flex justify-end gap-3">
                            <Link :href="route('admin.users.edit', user.id)" class="text-sm text-brand-600 hover:underline">تعديل</Link>
                            <DeleteButton :href="route('admin.users.destroy', user.id)" />
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <Pagination :links="users.links" />
</template>
