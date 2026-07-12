<script setup>
import { useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

defineOptions({ layout: AdminLayout });

const props = defineProps({ coupon: Object });
const isEdit = !!props.coupon;

const form = useForm({
    code: props.coupon?.code || '',
    type: props.coupon?.type || 'percent',
    value: props.coupon?.value || '',
    min_order_amount: props.coupon?.min_order_amount || '',
    max_uses: props.coupon?.max_uses || '',
    starts_at: props.coupon?.starts_at?.slice(0, 16) || '',
    expires_at: props.coupon?.expires_at?.slice(0, 16) || '',
    is_active: props.coupon?.is_active ?? true,
});

function submit() {
    if (isEdit) {
        form.put(route('admin.coupons.update', props.coupon.id));
    } else {
        form.post(route('admin.coupons.store'));
    }
}
</script>

<template>
    <h1 class="mb-6 text-2xl font-extrabold text-gray-800">{{ isEdit ? 'تعديل الكوبون' : 'كوبون جديد' }}</h1>

    <form @submit.prevent="submit" class="max-w-xl space-y-4 rounded-xl bg-white p-6 shadow-sm">
        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">الكود</label>
            <input v-model="form.code" type="text" class="w-full rounded-lg border-gray-300 uppercase" required />
            <p v-if="form.errors.code" class="mt-1 text-sm text-red-600">{{ form.errors.code }}</p>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">نوع الخصم</label>
                <select v-model="form.type" class="w-full rounded-lg border-gray-300">
                    <option value="percent">نسبة مئوية %</option>
                    <option value="fixed">مبلغ ثابت</option>
                </select>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">القيمة</label>
                <input v-model="form.value" type="number" step="0.01" class="w-full rounded-lg border-gray-300" required />
                <p v-if="form.errors.value" class="mt-1 text-sm text-red-600">{{ form.errors.value }}</p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">الحد الأدنى للطلب (اختياري)</label>
                <input v-model="form.min_order_amount" type="number" step="0.01" class="w-full rounded-lg border-gray-300" />
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">الحد الأقصى للاستخدام (اختياري)</label>
                <input v-model="form.max_uses" type="number" class="w-full rounded-lg border-gray-300" />
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">تاريخ البداية (اختياري)</label>
                <input v-model="form.starts_at" type="datetime-local" class="w-full rounded-lg border-gray-300" />
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">تاريخ الانتهاء (اختياري)</label>
                <input v-model="form.expires_at" type="datetime-local" class="w-full rounded-lg border-gray-300" />
                <p v-if="form.errors.expires_at" class="mt-1 text-sm text-red-600">{{ form.errors.expires_at }}</p>
            </div>
        </div>

        <label class="flex items-center gap-2 text-sm text-gray-700">
            <input v-model="form.is_active" type="checkbox" class="rounded border-gray-300" /> مفعّل
        </label>

        <button type="submit" :disabled="form.processing" class="rounded-lg bg-brand-600 px-6 py-2 text-white hover:bg-brand-700 disabled:opacity-50">
            حفظ
        </button>
    </form>
</template>
