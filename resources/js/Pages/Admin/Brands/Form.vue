<script setup>
import { useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Tabs from '@/Components/Admin/Tabs.vue';
import SeoFields from '@/Components/Admin/SeoFields.vue';
import LocalizedField from '@/Components/Admin/LocalizedField.vue';
import { resolveImage } from '@/helpers/format';

defineOptions({ layout: AdminLayout });

const props = defineProps({ brand: Object });

const isEdit = !!props.brand;

const form = useForm({
    name: { ar: props.brand?.name?.ar || '', en: props.brand?.name?.en || '' },
    slug: props.brand?.slug || '',
    description: { ar: props.brand?.description?.ar || '', en: props.brand?.description?.en || '' },
    website: props.brand?.website || '',
    logo: null,
    is_active: props.brand?.is_active ?? true,
    sort_order: props.brand?.sort_order ?? 0,
    seo: {
        meta_title: { ar: props.brand?.seo?.meta_title?.ar || '', en: props.brand?.seo?.meta_title?.en || '' },
        meta_description: { ar: props.brand?.seo?.meta_description?.ar || '', en: props.brand?.seo?.meta_description?.en || '' },
        meta_keywords: { ar: props.brand?.seo?.meta_keywords?.ar || '', en: props.brand?.seo?.meta_keywords?.en || '' },
        og_title: { ar: props.brand?.seo?.og_title?.ar || '', en: props.brand?.seo?.og_title?.en || '' },
        og_description: { ar: props.brand?.seo?.og_description?.ar || '', en: props.brand?.seo?.og_description?.en || '' },
        og_image: props.brand?.seo?.og_image || '',
        canonical_url: props.brand?.seo?.canonical_url || '',
        robots: props.brand?.seo?.robots || 'index,follow',
        focus_keyword: { ar: props.brand?.seo?.focus_keyword?.ar || '', en: props.brand?.seo?.focus_keyword?.en || '' },
    },
});

function submit() {
    if (isEdit) {
        form.transform((data) => ({ ...data, _method: 'put' })).post(route('admin.brands.update', props.brand.id), { forceFormData: true });
    } else {
        form.post(route('admin.brands.store'), { forceFormData: true });
    }
}
</script>

<template>
    <h1 class="mb-6 text-2xl font-extrabold text-gray-800">{{ isEdit ? 'تعديل البراند' : 'براند جديد' }}</h1>

    <form @submit.prevent="submit" class="max-w-3xl rounded-xl bg-white p-6 shadow-sm">
        <Tabs :tabs="['عام', 'SEO']">
            <template #عام>
                <div class="space-y-4">
                    <LocalizedField
                        v-model="form.name"
                        label="الاسم"
                        :errors="form.errors"
                        error-key-ar="name.ar"
                        error-key-en="name.en"
                    />
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">الرابط المختصر (Slug)</label>
                        <input v-model="form.slug" type="text" class="w-full rounded-lg border-gray-300" />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">الموقع الإلكتروني</label>
                        <input v-model="form.website" type="text" class="w-full rounded-lg border-gray-300" />
                    </div>
                    <LocalizedField v-model="form.description" label="الوصف" type="textarea" />
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">الشعار</label>
                        <img v-if="brand?.logo" :src="resolveImage(brand.logo)" class="mb-2 h-16" />
                        <input type="file" accept="image/*" @input="form.logo = $event.target.files[0]" class="w-full text-sm" />
                        <p v-if="form.errors.logo" class="mt-1 text-sm text-red-600">{{ form.errors.logo }}</p>
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
                </div>
            </template>
            <template #SEO>
                <SeoFields v-model="form.seo" :errors="form.errors" />
            </template>
        </Tabs>

        <div class="mt-6 flex justify-end">
            <button type="submit" :disabled="form.processing" class="rounded-lg bg-brand-600 px-6 py-2 text-white hover:bg-brand-700 disabled:opacity-50">
                حفظ
            </button>
        </div>
    </form>
</template>
