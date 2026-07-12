<script setup>
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
        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">عنوان SEO (Meta Title)</label>
            <input
                :value="modelValue.meta_title"
                @input="update('meta_title', $event.target.value)"
                type="text"
                maxlength="255"
                class="w-full rounded-lg border-gray-300 text-sm"
                placeholder="يظهر في نتائج البحث ومحركات البحث"
            />
            <p v-if="errors['seo.meta_title']" class="mt-1 text-sm text-red-600">{{ errors['seo.meta_title'] }}</p>
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">وصف SEO (Meta Description)</label>
            <textarea
                :value="modelValue.meta_description"
                @input="update('meta_description', $event.target.value)"
                rows="3"
                maxlength="500"
                class="w-full rounded-lg border-gray-300 text-sm"
            ></textarea>
            <p v-if="errors['seo.meta_description']" class="mt-1 text-sm text-red-600">{{ errors['seo.meta_description'] }}</p>
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">الكلمات المفتاحية</label>
            <input
                :value="modelValue.meta_keywords"
                @input="update('meta_keywords', $event.target.value)"
                type="text"
                class="w-full rounded-lg border-gray-300 text-sm"
                placeholder="كلمة, كلمة, كلمة"
            />
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">الكلمة المفتاحية الرئيسية (Focus Keyword)</label>
            <input
                :value="modelValue.focus_keyword"
                @input="update('focus_keyword', $event.target.value)"
                type="text"
                class="w-full rounded-lg border-gray-300 text-sm"
            />
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">عنوان Open Graph</label>
                <input
                    :value="modelValue.og_title"
                    @input="update('og_title', $event.target.value)"
                    type="text"
                    class="w-full rounded-lg border-gray-300 text-sm"
                />
            </div>
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
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">وصف Open Graph</label>
            <textarea
                :value="modelValue.og_description"
                @input="update('og_description', $event.target.value)"
                rows="2"
                class="w-full rounded-lg border-gray-300 text-sm"
            ></textarea>
        </div>

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
