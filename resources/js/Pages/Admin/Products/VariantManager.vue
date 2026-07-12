<script setup>
import { router, useForm } from '@inertiajs/vue3';
import { reactive } from 'vue';
import DeleteButton from '@/Components/Admin/DeleteButton.vue';

const props = defineProps({
    productId: { type: Number, required: true },
    variants: { type: Array, default: () => [] },
    attributes: { type: Array, default: () => [] },
});

const newVariant = useForm({
    sku: '',
    price: '',
    sale_price: '',
    stock_quantity: 0,
    is_active: true,
    attribute_value_ids: [],
});

const editing = reactive({});

function addVariant() {
    newVariant.post(route('admin.products.variants.store', props.productId), {
        preserveScroll: true,
        onSuccess: () => newVariant.reset(),
    });
}

function removeVariant(variant) {
    if (confirm('حذف هذا المتغير؟')) {
        router.delete(route('admin.products.variants.destroy', [props.productId, variant.id]), { preserveScroll: true });
    }
}

function toggleAttribute(list, id) {
    const index = list.indexOf(id);
    if (index === -1) list.push(id);
    else list.splice(index, 1);
}
</script>

<template>
    <div class="space-y-6">
        <div v-if="variants.length" class="space-y-2">
            <div v-for="variant in variants" :key="variant.id" class="flex items-center justify-between rounded-lg border border-gray-100 p-3">
                <div class="text-sm">
                    <span class="font-medium text-gray-700">{{ variant.sku }}</span>
                    <span class="ms-2 text-gray-500">
                        {{ (variant.attribute_values || []).map(v => v.value).join(' / ') }}
                    </span>
                    <span class="ms-2 text-gray-500">مخزون: {{ variant.stock_quantity }}</span>
                    <span v-if="variant.price" class="ms-2 text-gray-500">سعر: {{ variant.price }}</span>
                </div>
                <DeleteButton :href="route('admin.products.variants.destroy', [productId, variant.id])" confirm-text="حذف هذا المتغير؟" />
            </div>
        </div>
        <p v-else class="text-sm text-gray-400">لا توجد متغيرات بعد.</p>

        <div class="rounded-lg border border-dashed border-gray-300 p-4">
            <h4 class="mb-3 text-sm font-semibold text-gray-700">إضافة متغير جديد</h4>
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                <input v-model="newVariant.sku" type="text" placeholder="SKU" class="rounded-lg border-gray-300 text-sm" />
                <input v-model="newVariant.stock_quantity" type="number" placeholder="المخزون" class="rounded-lg border-gray-300 text-sm" />
                <input v-model="newVariant.price" type="number" step="0.01" placeholder="السعر (اختياري)" class="rounded-lg border-gray-300 text-sm" />
                <input v-model="newVariant.sale_price" type="number" step="0.01" placeholder="سعر التخفيض (اختياري)" class="rounded-lg border-gray-300 text-sm" />
            </div>

            <div v-for="attribute in attributes" :key="attribute.id" class="mt-3">
                <p class="mb-1 text-xs font-semibold text-gray-500">{{ attribute.name }}</p>
                <div class="flex flex-wrap gap-2">
                    <button
                        v-for="value in attribute.values"
                        :key="value.id"
                        type="button"
                        @click="toggleAttribute(newVariant.attribute_value_ids, value.id)"
                        class="rounded-full border px-3 py-1 text-xs"
                        :class="newVariant.attribute_value_ids.includes(value.id) ? 'border-brand-600 bg-brand-50 text-brand-700' : 'border-gray-300 text-gray-600'"
                    >
                        {{ value.value }}
                    </button>
                </div>
            </div>

            <button
                type="button"
                @click="addVariant"
                :disabled="newVariant.processing"
                class="mt-4 rounded-lg bg-gray-800 px-4 py-2 text-sm text-white hover:bg-gray-900 disabled:opacity-50"
            >
                إضافة المتغير
            </button>
        </div>
    </div>
</template>
