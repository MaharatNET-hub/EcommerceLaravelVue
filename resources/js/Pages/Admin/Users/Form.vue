<script setup>
import { useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

defineOptions({ layout: AdminLayout });

const props = defineProps({
    user: Object,
    roles: Array,
});
const isEdit = !!props.user;

const form = useForm({
    name: props.user?.name || '',
    email: props.user?.email || '',
    phone: props.user?.phone || '',
    password: '',
    is_active: props.user?.is_active ?? true,
    roles: props.user?.roles?.map((r) => r.name) || [],
});

function submit() {
    if (isEdit) {
        form.put(route('admin.users.update', props.user.id));
    } else {
        form.post(route('admin.users.store'));
    }
}
</script>

<template>
    <h1 class="mb-6 text-2xl font-extrabold text-gray-800">{{ isEdit ? 'تعديل المستخدم' : 'مستخدم جديد' }}</h1>

    <form @submit.prevent="submit" class="max-w-xl space-y-4 rounded-xl bg-white p-6 shadow-sm">
        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">الاسم</label>
            <input v-model="form.name" type="text" class="w-full rounded-lg border-gray-300" required />
            <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">البريد الإلكتروني</label>
            <input v-model="form.email" type="email" class="w-full rounded-lg border-gray-300" required />
            <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</p>
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">الهاتف</label>
            <input v-model="form.phone" type="text" class="w-full rounded-lg border-gray-300" />
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">
                {{ isEdit ? 'كلمة مرور جديدة (اتركها فارغة للإبقاء الحالية)' : 'كلمة المرور' }}
            </label>
            <input v-model="form.password" type="password" class="w-full rounded-lg border-gray-300" :required="!isEdit" />
            <p v-if="form.errors.password" class="mt-1 text-sm text-red-600">{{ form.errors.password }}</p>
        </div>

        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">الأدوار</label>
            <div class="flex flex-wrap gap-2">
                <label
                    v-for="role in roles"
                    :key="role"
                    class="flex items-center gap-1.5 rounded-full border px-3 py-1.5 text-sm"
                    :class="form.roles.includes(role) ? 'border-brand-600 bg-brand-50 text-brand-700' : 'border-gray-300 text-gray-600'"
                >
                    <input type="checkbox" :value="role" v-model="form.roles" class="hidden" />
                    {{ role }}
                </label>
            </div>
        </div>

        <label class="flex items-center gap-2 text-sm text-gray-700">
            <input v-model="form.is_active" type="checkbox" class="rounded border-gray-300" /> نشط
        </label>

        <button type="submit" :disabled="form.processing" class="rounded-lg bg-brand-600 px-6 py-2 text-white hover:bg-brand-700 disabled:opacity-50">
            حفظ
        </button>
    </form>
</template>
