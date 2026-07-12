<script setup>
import { Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import DeleteButton from '@/Components/Admin/DeleteButton.vue';

defineOptions({ layout: AdminLayout });

defineProps({ roles: Array });
</script>

<template>
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-extrabold text-gray-800">الأدوار والصلاحيات</h1>
        <Link :href="route('admin.roles.create')" class="rounded-lg bg-brand-600 px-4 py-2 text-sm text-white hover:bg-brand-700">
            + دور جديد
        </Link>
    </div>

    <div class="overflow-x-auto rounded-xl bg-white shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500">
                <tr>
                    <th class="p-3 text-start">الاسم</th>
                    <th class="p-3 text-start">عدد المستخدمين</th>
                    <th class="p-3"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="role in roles" :key="role.id" class="border-t border-gray-100">
                    <td class="p-3 font-medium text-gray-700">{{ role.name }}</td>
                    <td class="p-3 text-gray-500">{{ role.users_count }}</td>
                    <td class="p-3 text-end">
                        <div class="flex justify-end gap-3">
                            <Link :href="route('admin.roles.edit', role.id)" class="text-sm text-brand-600 hover:underline">تعديل</Link>
                            <DeleteButton v-if="role.name !== 'Super Admin'" :href="route('admin.roles.destroy', role.id)" />
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
