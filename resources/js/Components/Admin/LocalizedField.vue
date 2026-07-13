<script setup>
const props = defineProps({
    modelValue: { type: Object, required: true },
    label: { type: String, default: '' },
    type: { type: String, default: 'text' }, // 'text' | 'textarea'
    rows: { type: Number, default: 3 },
    errors: { type: Object, default: () => ({}) },
    errorKeyAr: { type: String, default: '' },
    errorKeyEn: { type: String, default: '' },
});

const emit = defineEmits(['update:modelValue']);

function update(locale, value) {
    emit('update:modelValue', { ...props.modelValue, [locale]: value });
}
</script>

<template>
    <div>
        <label v-if="label" class="mb-1 block text-sm font-medium text-gray-700">{{ label }}</label>
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
            <div>
                <span class="mb-1 block text-xs text-gray-400">العربية</span>
                <textarea
                    v-if="type === 'textarea'"
                    :value="modelValue.ar"
                    @input="update('ar', $event.target.value)"
                    :rows="rows"
                    dir="rtl"
                    class="w-full rounded-lg border-gray-300 text-sm"
                ></textarea>
                <input
                    v-else
                    :value="modelValue.ar"
                    @input="update('ar', $event.target.value)"
                    type="text"
                    dir="rtl"
                    class="w-full rounded-lg border-gray-300 text-sm"
                />
                <p v-if="errorKeyAr && errors[errorKeyAr]" class="mt-1 text-xs text-red-600">{{ errors[errorKeyAr] }}</p>
            </div>
            <div>
                <span class="mb-1 block text-xs text-gray-400">English</span>
                <textarea
                    v-if="type === 'textarea'"
                    :value="modelValue.en"
                    @input="update('en', $event.target.value)"
                    :rows="rows"
                    dir="ltr"
                    class="w-full rounded-lg border-gray-300 text-sm"
                ></textarea>
                <input
                    v-else
                    :value="modelValue.en"
                    @input="update('en', $event.target.value)"
                    type="text"
                    dir="ltr"
                    class="w-full rounded-lg border-gray-300 text-sm"
                />
                <p v-if="errorKeyEn && errors[errorKeyEn]" class="mt-1 text-xs text-red-600">{{ errors[errorKeyEn] }}</p>
            </div>
        </div>
    </div>
</template>
