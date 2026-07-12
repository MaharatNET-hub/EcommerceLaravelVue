<script setup>
import { router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Tabs from '@/Components/Admin/Tabs.vue';
import SeoFields from '@/Components/Admin/SeoFields.vue';
import VariantManager from './VariantManager.vue';
import { resolveImage } from '@/helpers/format';

defineOptions({ layout: AdminLayout });

const props = defineProps({
    product: Object,
    categories: Array,
    brands: Array,
    attributes: Array,
});

const isEdit = !!props.product;

const form = useForm({
    category_id: props.product?.category_id || '',
    brand_id: props.product?.brand_id || '',
    name: props.product?.name || '',
    slug: props.product?.slug || '',
    sku: props.product?.sku || '',
    description: props.product?.description || '',
    short_description: props.product?.short_description || '',
    price: props.product?.price || '',
    sale_price: props.product?.sale_price || '',
    cost_price: props.product?.cost_price || '',
    stock_quantity: props.product?.stock_quantity ?? 0,
    manage_stock: props.product?.manage_stock ?? true,
    in_stock: props.product?.in_stock ?? true,
    is_active: props.product?.is_active ?? true,
    is_featured: props.product?.is_featured ?? false,
    weight: props.product?.weight || '',
    sort_order: props.product?.sort_order ?? 0,
    images: [],
    seo: {
        meta_title: props.product?.seo?.meta_title || '',
        meta_description: props.product?.seo?.meta_description || '',
        meta_keywords: props.product?.seo?.meta_keywords || '',
        og_title: props.product?.seo?.og_title || '',
        og_description: props.product?.seo?.og_description || '',
        og_image: props.product?.seo?.og_image || '',
        canonical_url: props.product?.seo?.canonical_url || '',
        robots: props.product?.seo?.robots || 'index,follow',
        focus_keyword: props.product?.seo?.focus_keyword || '',
    },
});

function onImagesSelected(event) {
    form.images = Array.from(event.target.files);
}

function submit() {
    if (isEdit) {
        form.transform((data) => ({ ...data, _method: 'put' })).post(route('admin.products.update', props.product.id), { forceFormData: true });
    } else {
        form.post(route('admin.products.store'), { forceFormData: true });
    }
}

function deleteImage(image) {
    if (confirm('حذف هذه الصورة؟')) {
        router.delete(route('admin.products.images.destroy', [props.product.id, image.id]), { preserveScroll: true });
    }
}
</script>

<template>
    <h1 class="mb-6 text-2xl font-extrabold text-gray-800">{{ isEdit ? 'تعديل المنتج' : 'منتج جديد' }}</h1>

    <form @submit.prevent="submit" class="max-w-4xl rounded-xl bg-white p-6 shadow-sm">
        <Tabs :tabs="isEdit ? ['عام', 'الصور', 'المتغيرات', 'SEO'] : ['عام', 'الصور', 'SEO']">
            <template #عام>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label class="mb-1 block text-sm font-medium text-gray-700">الاسم</label>
                        <input v-model="form.name" type="text" class="w-full rounded-lg border-gray-300" required />
                        <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">SKU</label>
                        <input v-model="form.sku" type="text" class="w-full rounded-lg border-gray-300" required />
                        <p v-if="form.errors.sku" class="mt-1 text-sm text-red-600">{{ form.errors.sku }}</p>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">الرابط المختصر (Slug)</label>
                        <input v-model="form.slug" type="text" class="w-full rounded-lg border-gray-300" />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">التصنيف</label>
                        <select v-model="form.category_id" class="w-full rounded-lg border-gray-300">
                            <option value="">— بدون —</option>
                            <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">البراند</label>
                        <select v-model="form.brand_id" class="w-full rounded-lg border-gray-300">
                            <option value="">— بدون —</option>
                            <option v-for="b in brands" :key="b.id" :value="b.id">{{ b.name }}</option>
                        </select>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="mb-1 block text-sm font-medium text-gray-700">وصف مختصر</label>
                        <textarea v-model="form.short_description" rows="2" class="w-full rounded-lg border-gray-300"></textarea>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-1 block text-sm font-medium text-gray-700">الوصف الكامل</label>
                        <textarea v-model="form.description" rows="5" class="w-full rounded-lg border-gray-300"></textarea>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">السعر</label>
                        <input v-model="form.price" type="number" step="0.01" class="w-full rounded-lg border-gray-300" required />
                        <p v-if="form.errors.price" class="mt-1 text-sm text-red-600">{{ form.errors.price }}</p>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">سعر التخفيض (اختياري)</label>
                        <input v-model="form.sale_price" type="number" step="0.01" class="w-full rounded-lg border-gray-300" />
                        <p v-if="form.errors.sale_price" class="mt-1 text-sm text-red-600">{{ form.errors.sale_price }}</p>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">سعر التكلفة (اختياري)</label>
                        <input v-model="form.cost_price" type="number" step="0.01" class="w-full rounded-lg border-gray-300" />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">الكمية بالمخزون</label>
                        <input v-model.number="form.stock_quantity" type="number" class="w-full rounded-lg border-gray-300" />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">الوزن (كجم، اختياري)</label>
                        <input v-model="form.weight" type="number" step="0.01" class="w-full rounded-lg border-gray-300" />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">الترتيب</label>
                        <input v-model.number="form.sort_order" type="number" class="w-full rounded-lg border-gray-300" />
                    </div>

                    <div class="flex flex-wrap items-center gap-6 sm:col-span-2">
                        <label class="flex items-center gap-2 text-sm text-gray-700">
                            <input v-model="form.manage_stock" type="checkbox" class="rounded border-gray-300" /> إدارة المخزون
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-700">
                            <input v-model="form.is_active" type="checkbox" class="rounded border-gray-300" /> مفعّل
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-700">
                            <input v-model="form.is_featured" type="checkbox" class="rounded border-gray-300" /> منتج مميز
                        </label>
                    </div>
                </div>
            </template>

            <template #الصور>
                <div v-if="isEdit && product.images?.length" class="mb-4 flex flex-wrap gap-3">
                    <div v-for="image in product.images" :key="image.id" class="relative">
                        <img :src="resolveImage(image.path)" class="h-24 w-24 rounded-lg object-cover" />
                        <button type="button" @click="deleteImage(image)" class="absolute -top-2 -end-2 h-6 w-6 rounded-full bg-red-600 text-xs text-white">✕</button>
                    </div>
                </div>
                <label class="mb-1 block text-sm font-medium text-gray-700">إضافة صور</label>
                <input type="file" accept="image/*" multiple @input="onImagesSelected" class="w-full text-sm" />
                <p v-if="form.errors.images" class="mt-1 text-sm text-red-600">{{ form.errors.images }}</p>
            </template>

            <template v-if="isEdit" #المتغيرات>
                <VariantManager :product-id="product.id" :variants="product.variants" :attributes="attributes" />
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
