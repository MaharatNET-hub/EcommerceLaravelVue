<script setup>
import { useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import LocalizedField from '@/Components/Admin/LocalizedField.vue';
import { resolveImage } from '@/helpers/format';

defineOptions({ layout: AdminLayout });

const props = defineProps({ slider: Object });
const isEdit = !!props.slider;

const form = useForm({
    title: { ar: props.slider?.title?.ar || '', en: props.slider?.title?.en || '' },
    subtitle: { ar: props.slider?.subtitle?.ar || '', en: props.slider?.subtitle?.en || '' },
    image: null,
    mobile_image: null,
    button_text: { ar: props.slider?.button_text?.ar || '', en: props.slider?.button_text?.en || '' },
    button_link: props.slider?.button_link || '',
    sort_order: props.slider?.sort_order ?? 0,
    is_active: props.slider?.is_active ?? true,
});

function submit() {
    if (isEdit) {
        form.transform((data) => ({ ...data, _method: 'put' })).post(route('admin.sliders.update', props.slider.id), { forceFormData: true });
    } else {
        form.post(route('admin.sliders.store'), { forceFormData: true });
    }
}
</script>

<template>
    <h1 class="mb-6 text-2xl font-extrabold text-gray-800">{{ isEdit ? 'تعديل الشريحة' : 'شريحة جديدة' }}</h1>

    <form @submit.prevent="submit" class="max-w-xl space-y-4 rounded-xl bg-white p-6 shadow-sm">
        <LocalizedField v-model="form.title" label="العنوان" />
        <LocalizedField v-model="form.subtitle" label="العنوان الفرعي" />
        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">الصورة</label>
            <img v-if="slider?.image" :src="resolveImage(slider.image)" class="mb-2 h-24 rounded-lg object-cover" />
            <input type="file" accept="image/*" @input="form.image = $event.target.files[0]" class="w-full text-sm" />
            <p v-if="form.errors.image" class="mt-1 text-sm text-red-600">{{ form.errors.image }}</p>
        </div>
        <LocalizedField v-model="form.button_text" label="نص الزر" />
        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">رابط الزر</label>
            <input v-model="form.button_link" type="text" class="w-full rounded-lg border-gray-300" />
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">الترتيب</label>
                <input v-model.number="form.sort_order" type="number" class="w-full rounded-lg border-gray-300" />
            </div>
            <div class="flex items-center gap-2 pt-6">
                <input id="is_active" v-model="form.is_active" type="checkbox" class="rounded border-gray-300" />
                <label for="is_active" class="text-sm text-gray-700">مفعّل</label>
            </div>
        </div>

        <button type="submit" :disabled="form.processing" class="rounded-lg bg-brand-600 px-6 py-2 text-white hover:bg-brand-700 disabled:opacity-50">
            حفظ
        </button>
    </form>
</template>
