<script setup>
import { useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';

defineOptions({ layout: AdminLayout });

const props = defineProps({
    role: Object,
    permissions: Array,
});
const isEdit = !!props.role;

const form = useForm({
    name: props.role?.name || '',
    permissions: props.role?.permissions?.map((p) => p.name) || [],
});

const groups = computed(() => {
    const map = {};
    props.permissions.forEach((permission) => {
        const [resource] = permission.name.split('.');
        map[resource] = map[resource] || [];
        map[resource].push(permission.name);
    });
    return map;
});

function toggleGroup(names) {
    const allSelected = names.every((n) => form.permissions.includes(n));
    if (allSelected) {
        form.permissions = form.permissions.filter((p) => !names.includes(p));
    } else {
        form.permissions = [...new Set([...form.permissions, ...names])];
    }
}

function submit() {
    if (isEdit) {
        form.put(route('admin.roles.update', props.role.id));
    } else {
        form.post(route('admin.roles.store'));
    }
}
</script>

<template>
    <h1 class="mb-6 text-2xl font-extrabold text-gray-800">{{ isEdit ? 'تعديل الدور' : 'دور جديد' }}</h1>

    <form @submit.prevent="submit" class="max-w-3xl space-y-6 rounded-xl bg-white p-6 shadow-sm">
        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">اسم الدور</label>
            <input v-model="form.name" type="text" class="w-full max-w-sm rounded-lg border-gray-300" required :disabled="role?.name === 'Super Admin'" />
            <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
        </div>

        <div>
            <label class="mb-3 block text-sm font-medium text-gray-700">الصلاحيات</label>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div v-for="(names, resource) in groups" :key="resource" class="rounded-lg border border-gray-100 p-3">
                    <div class="mb-2 flex items-center justify-between">
                        <span class="text-sm font-semibold text-gray-700">{{ resource }}</span>
                        <button type="button" @click="toggleGroup(names)" class="text-xs text-brand-600 hover:underline">تحديد الكل</button>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <label
                            v-for="name in names"
                            :key="name"
                            class="flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-xs"
                            :class="form.permissions.includes(name) ? 'border-brand-600 bg-brand-50 text-brand-700' : 'border-gray-300 text-gray-600'"
                        >
                            <input type="checkbox" :value="name" v-model="form.permissions" class="hidden" />
                            {{ name.split('.')[1] }}
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" :disabled="form.processing" class="rounded-lg bg-brand-600 px-6 py-2 text-white hover:bg-brand-700 disabled:opacity-50">
            حفظ
        </button>
    </form>
</template>
