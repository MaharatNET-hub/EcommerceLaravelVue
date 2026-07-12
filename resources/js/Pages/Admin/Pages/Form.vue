<script setup>
import { useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Tabs from '@/Components/Admin/Tabs.vue';
import SeoFields from '@/Components/Admin/SeoFields.vue';

defineOptions({ layout: AdminLayout });

const props = defineProps({ page: Object });
const isEdit = !!props.page;

const form = useForm({
    title: props.page?.title || '',
    slug: props.page?.slug || '',
    content: props.page?.content || '',
    is_active: props.page?.is_active ?? true,
    published_at: props.page?.published_at?.slice(0, 16) || '',
    seo: {
        meta_title: props.page?.seo?.meta_title || '',
        meta_description: props.page?.seo?.meta_description || '',
        meta_keywords: props.page?.seo?.meta_keywords || '',
        og_title: props.page?.seo?.og_title || '',
        og_description: props.page?.seo?.og_description || '',
        og_image: props.page?.seo?.og_image || '',
        canonical_url: props.page?.seo?.canonical_url || '',
        robots: props.page?.seo?.robots || 'index,follow',
        focus_keyword: props.page?.seo?.focus_keyword || '',
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
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">العنوان</label>
                        <input v-model="form.title" type="text" class="w-full rounded-lg border-gray-300" required />
                        <p v-if="form.errors.title" class="mt-1 text-sm text-red-600">{{ form.errors.title }}</p>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">الرابط المختصر (Slug)</label>
                        <input v-model="form.slug" type="text" class="w-full rounded-lg border-gray-300" />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">المحتوى (HTML)</label>
                        <textarea v-model="form.content" rows="10" class="w-full rounded-lg border-gray-300 font-mono text-sm"></textarea>
                    </div>
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
