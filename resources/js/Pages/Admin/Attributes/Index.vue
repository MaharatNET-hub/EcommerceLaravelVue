<script setup>
import { router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import DeleteButton from '@/Components/Admin/DeleteButton.vue';

defineOptions({ layout: AdminLayout });

const props = defineProps({ attributes: Array });

const attributeForm = useForm({ name: '' });
const valueForms = {};

function addAttribute() {
    attributeForm.post(route('admin.attributes.store'), { onSuccess: () => attributeForm.reset() });
}

function valueForm(attributeId) {
    if (!valueForms[attributeId]) {
        valueForms[attributeId] = useForm({ value: '', color_code: '' });
    }
    return valueForms[attributeId];
}

function addValue(attribute) {
    const form = valueForm(attribute.id);
    form.post(route('admin.attributes.values.store', attribute.id), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
}

function removeValue(attribute, value) {
    if (confirm('حذف هذه القيمة؟')) {
        router.delete(route('admin.attributes.values.destroy', [attribute.id, value.id]), { preserveScroll: true });
    }
}
</script>

<template>
    <h1 class="mb-6 text-2xl font-extrabold text-gray-800">خصائص المنتجات (اللون، المقاس...)</h1>

    <form @submit.prevent="addAttribute" class="mb-6 flex max-w-md gap-2">
        <input v-model="attributeForm.name" type="text" placeholder="اسم خاصية جديدة مثل: اللون" class="flex-1 rounded-lg border-gray-300 text-sm" />
        <button type="submit" class="rounded-lg bg-brand-600 px-4 py-2 text-sm text-white hover:bg-brand-700">إضافة</button>
    </form>

    <div class="space-y-4">
        <div v-for="attribute in attributes" :key="attribute.id" class="rounded-xl bg-white p-5 shadow-sm">
            <div class="mb-3 flex items-center justify-between">
                <h3 class="font-bold text-gray-800">{{ attribute.name }}</h3>
                <DeleteButton :href="route('admin.attributes.destroy', attribute.id)" confirm-text="حذف هذه الخاصية وكل قيمها؟" />
            </div>

            <div class="mb-3 flex flex-wrap gap-2">
                <span v-for="value in attribute.values" :key="value.id" class="flex items-center gap-2 rounded-full border border-gray-200 px-3 py-1 text-sm">
                    <span v-if="value.color_code" class="h-3 w-3 rounded-full" :style="{ backgroundColor: value.color_code }"></span>
                    {{ value.value }}
                    <button type="button" @click="removeValue(attribute, value)" class="text-red-500">✕</button>
                </span>
            </div>

            <form @submit.prevent="addValue(attribute)" class="flex flex-wrap gap-2">
                <input v-model="valueForm(attribute.id).value" type="text" placeholder="قيمة جديدة" class="rounded-lg border-gray-300 text-sm" />
                <input v-model="valueForm(attribute.id).color_code" type="text" placeholder="كود اللون (اختياري) #000000" class="rounded-lg border-gray-300 text-sm" />
                <button type="submit" class="rounded-lg bg-gray-800 px-3 py-1.5 text-sm text-white hover:bg-gray-900">إضافة قيمة</button>
            </form>
        </div>
    </div>
</template>
