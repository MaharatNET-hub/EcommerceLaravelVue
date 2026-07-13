<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { formatPrice, resolveImage } from '@/helpers/format';
import { useTrans } from '@/composables/useTrans';

const props = defineProps({
    product: { type: Object, required: true },
});

const { t } = useTrans();
const page = usePage();
const currencySymbol = computed(() => page.props.siteSettings?.currency_symbol || '$');

const image = computed(() => {
    const primary = props.product.images?.find((img) => img.is_primary) ?? props.product.images?.[0];
    return resolveImage(primary?.path);
});

const discount = computed(() => {
    if (!props.product.sale_price) return null;
    return Math.round((1 - Number(props.product.sale_price) / Number(props.product.price)) * 100);
});
</script>

<template>
    <Link
        :href="route('products.show', product.slug)"
        class="group block overflow-hidden rounded-xl border border-gray-200 bg-white transition hover:shadow-lg"
    >
        <div class="relative aspect-square overflow-hidden bg-gray-100">
            <img
                :src="image"
                :alt="product.name"
                loading="lazy"
                class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
            />
            <span
                v-if="discount"
                class="absolute top-2 start-2 rounded-full bg-brand-600 px-2 py-1 text-xs font-bold text-white"
            >
                -{{ discount }}%
            </span>
            <span
                v-if="product.stock_quantity === 0 && product.manage_stock"
                class="absolute inset-0 flex items-center justify-center bg-black/40 text-sm font-semibold text-white"
            >
                {{ t('product.out_of_stock') }}
            </span>
        </div>
        <div class="p-3">
            <p v-if="product.brand" class="mb-1 text-xs text-gray-400">{{ product.brand.name }}</p>
            <h3 class="mb-2 line-clamp-2 min-h-[2.5rem] text-sm font-medium text-gray-800">
                {{ product.name }}
            </h3>
            <div class="flex items-baseline gap-2">
                <span class="text-base font-bold text-brand-600">
                    {{ formatPrice(product.sale_price || product.price, currencySymbol) }}
                </span>
                <span v-if="product.sale_price" class="text-xs text-gray-400 line-through">
                    {{ formatPrice(product.price, currencySymbol) }}
                </span>
            </div>
        </div>
    </Link>
</template>
