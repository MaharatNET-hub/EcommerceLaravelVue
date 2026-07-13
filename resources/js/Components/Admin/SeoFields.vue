<script setup>
import LocalizedField from '@/Components/Admin/LocalizedField.vue';

const props = defineProps({
    modelValue: { type: Object, required: true },
    errors: { type: Object, default: () => ({}) },
});

defineEmits(['update:modelValue']);

function update(key, value) {
    props.modelValue[key] = value;
}
</script>

<template>
    <div class="space-y-4">
        <LocalizedField
            :model-value="modelValue.meta_title"
            @update:model-value="update('meta_title', $event)"
            label="عنوان SEO (Meta Title)"
            :errors="errors"
            error-key-ar="seo.meta_title.ar"
            error-key-en="seo.meta_title.en"
        />

        <LocalizedField
            :model-value="modelValue.meta_description"
            @update:model-value="update('meta_description', $event)"
            label="وصف SEO (Meta Description)"
            type="textarea"
            :rows="3"
            :errors="errors"
            error-key-ar="seo.meta_description.ar"
            error-key-en="seo.meta_description.en"
        />

        <LocalizedField
            :model-value="modelValue.meta_keywords"
            @update:model-value="update('meta_keywords', $event)"
            label="الكلمات المفتاحية"
        />

        <LocalizedField
            :model-value="modelValue.focus_keyword"
            @update:model-value="update('focus_keyword', $event)"
            label="الكلمة المفتاحية الرئيسية (Focus Keyword)"
        />

        <LocalizedField
            :model-value="modelValue.og_title"
            @update:model-value="update('og_title', $event)"
            label="عنوان Open Graph"
        />

        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">صورة Open Graph (رابط)</label>
            <input
                :value="modelValue.og_image"
                @input="update('og_image', $event.target.value)"
                type="text"
                class="w-full rounded-lg border-gray-300 text-sm"
                placeholder="اتركه فارغاً لاستخدام الصورة الرئيسية"
            />
        </div>

        <LocalizedField
            :model-value="modelValue.og_description"
            @update:model-value="update('og_description', $event)"
            label="وصف Open Graph"
            type="textarea"
            :rows="2"
        />

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">الرابط الأساسي (Canonical URL)</label>
                <input
                    :value="modelValue.canonical_url"
                    @input="update('canonical_url', $event.target.value)"
                    type="text"
                    class="w-full rounded-lg border-gray-300 text-sm"
                />
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Robots</label>
                <select
                    :value="modelValue.robots"
                    @change="update('robots', $event.target.value)"
                    class="w-full rounded-lg border-gray-300 text-sm"
                >
                    <option value="index,follow">index, follow</option>
                    <option value="noindex,follow">noindex, follow</option>
                    <option value="index,nofollow">index, nofollow</option>
                    <option value="noindex,nofollow">noindex, nofollow</option>
                </select>
            </div>
        </div>
    </div>
</template>
