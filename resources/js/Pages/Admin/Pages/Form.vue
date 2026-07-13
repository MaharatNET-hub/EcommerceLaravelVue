<script setup>
import { useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Tabs from '@/Components/Admin/Tabs.vue';
import SeoFields from '@/Components/Admin/SeoFields.vue';
import LocalizedField from '@/Components/Admin/LocalizedField.vue';

defineOptions({ layout: AdminLayout });

const props = defineProps({ page: Object });
const isEdit = !!props.page;

const form = useForm({
    title: { ar: props.page?.title?.ar || '', en: props.page?.title?.en || '' },
    slug: props.page?.slug || '',
    content: { ar: props.page?.content?.ar || '', en: props.page?.content?.en || '' },
    is_active: props.page?.is_active ?? true,
    published_at: props.page?.published_at?.slice(0, 16) || '',
    seo: {
        meta_title: { ar: props.page?.seo?.meta_title?.ar || '', en: props.page?.seo?.meta_title?.en || '' },
        meta_description: { ar: props.page?.seo?.meta_description?.ar || '', en: props.page?.seo?.meta_description?.en || '' },
        meta_keywords: { ar: props.page?.seo?.meta_keywords?.ar || '', en: props.page?.seo?.meta_keywords?.en || '' },
        og_title: { ar: props.page?.seo?.og_title?.ar || '', en: props.page?.seo?.og_title?.en || '' },
        og_description: { ar: props.page?.seo?.og_description?.ar || '', en: props.page?.seo?.og_description?.en || '' },
        og_image: props.page?.seo?.og_image || '',
        canonical_url: props.page?.seo?.canonical_url || '',
        robots: props.page?.seo?.robots || 'index,follow',
        focus_keyword: { ar: props.page?.seo?.focus_keyword?.ar || '', en: props.page?.seo?.focus_keyword?.en || '' },
    },
});

function submit() {
    if (isEdit) {
        form.put(route('admin.pages.update', props.page.id));
    } else {
        form.post(route('admin.pages.store'));
    }
}
</script>

<template>
    <h1 class="mb-6 text-2xl font-extrabold text-gray-800">{{ isEdit ? 'تعديل الصفحة' : 'صفحة جديدة' }}</h1>

    <form @submit.prevent="submit" class="max-w-3xl rounded-xl bg-white p-6 shadow-sm">
        <Tabs :tabs="['المحتوى', 'SEO']">
            <template #المحتوى>
                <div class="space-y-4">
                    <LocalizedField
                        v-model="form.title"
                        label="العنوان"
                        :errors="form.errors"
                        error-key-ar="title.ar"
                        error-key-en="title.en"
                    />
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">الرابط المختصر (Slug)</label>
                        <input v-model="form.slug" type="text" class="w-full rounded-lg border-gray-300" />
                    </div>
                    <LocalizedField v-model="form.content" label="المحتوى (HTML)" type="textarea" :rows="10" />
                    <label class="flex items-center gap-2 text-sm text-gray-700">
                        <input v-model="form.is_active" type="checkbox" class="rounded border-gray-300" /> منشورة
                    </label>
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
