<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue';
import Seo from '@/Components/Seo.vue';
import { formatPrice, resolveImage } from '@/helpers/format';

defineOptions({ layout: StorefrontLayout });

const props = defineProps({ cart: Object });

const page = usePage();
const currencySymbol = computed(() => page.props.siteSettings?.currency_symbol || '$');

const subtotal = computed(() =>
    props.cart.items.reduce((sum, item) => sum + Number(item.price) * item.quantity, 0),
);

function updateQuantity(item, quantity) {
    if (quantity < 1) return;
    router.patch(route('cart.update', item.id), { quantity }, { preserveScroll: true });
}

function removeItem(item) {
    router.delete(route('cart.destroy', item.id), { preserveScroll: true });
}
</script>

<template>
    <Seo title="سلة التسوق" robots="noindex,follow" />

    <div class="mx-auto max-w-5xl px-4 py-10">
        <h1 class="mb-6 text-2xl font-extrabold text-gray-800">سلة التسوق</h1>

        <div v-if="!cart.items.length" class="rounded-xl border border-dashed border-gray-300 py-16 text-center">
            <p class="mb-4 text-gray-500">سلتك فارغة حالياً.</p>
            <Link :href="route('home')" class="rounded-lg bg-brand-600 px-6 py-2 text-white hover:bg-brand-700">تابع التسوق</Link>
        </div>

        <div v-else class="grid grid-cols-1 gap-8 lg:grid-cols-3">
            <div class="space-y-4 lg:col-span-2">
                <div
                    v-for="item in cart.items"
                    :key="item.id"
                    class="flex items-center gap-4 rounded-xl border border-gray-100 p-4"
                >
                    <img
                        :src="resolveImage(item.product.images?.[0]?.path)"
                        class="h-20 w-20 rounded-lg object-cover"
                        :alt="item.product.name"
                    />
                    <div class="flex-1">
                        <Link :href="route('products.show', item.product.slug)" class="font-medium text-gray-800 hover:text-brand-600">
                            {{ item.product.name }}
                        </Link>
                        <p v-if="item.variant" class="text-xs text-gray-500">
                            {{ item.variant.attribute_values?.map(v => v.value).join(' / ') }}
                        </p>
                        <p class="mt-1 text-sm font-semibold text-brand-600">{{ formatPrice(item.price, currencySymbol) }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button @click="updateQuantity(item, item.quantity - 1)" class="h-8 w-8 rounded-full border text-gray-600">-</button>
                        <span class="w-6 text-center">{{ item.quantity }}</span>
                        <button @click="updateQuantity(item, item.quantity + 1)" class="h-8 w-8 rounded-full border text-gray-600">+</button>
                    </div>
                    <button @click="removeItem(item)" class="text-sm text-red-500 hover:text-red-700">حذف</button>
                </div>
            </div>

            <div class="h-fit rounded-xl border border-gray-100 p-6">
                <h2 class="mb-4 text-lg font-bold text-gray-800">ملخص الطلب</h2>
                <div class="mb-4 flex justify-between text-sm text-gray-600">
                    <span>المجموع الفرعي</span>
                    <span>{{ formatPrice(subtotal, currencySymbol) }}</span>
                </div>
                <Link
                    :href="route('checkout.create')"
                    class="block w-full rounded-lg bg-brand-600 py-3 text-center font-semibold text-white hover:bg-brand-700"
                >
                    إتمام الطلب
                </Link>
            </div>
        </div>
    </div>
</template>
