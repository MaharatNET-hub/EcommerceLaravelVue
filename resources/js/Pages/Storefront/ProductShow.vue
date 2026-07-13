<script setup>
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue';
import Seo from '@/Components/Seo.vue';
import ProductCard from '@/Components/ProductCard.vue';
import StarRating from '@/Components/StarRating.vue';
import { formatPrice, resolveImage } from '@/helpers/format';
import { useTrans } from '@/composables/useTrans';

defineOptions({ layout: StorefrontLayout });

const { t } = useTrans();

const props = defineProps({
    product: Object,
    related: Array,
});

const page = usePage();
const currencySymbol = computed(() => page.props.siteSettings?.currency_symbol || '$');
const isAuthenticated = computed(() => !!page.props.auth?.user);

const activeImage = ref(props.product.images?.find((i) => i.is_primary) ?? props.product.images?.[0]);
const selectedVariant = ref(null);

const displayPrice = computed(() => {
    if (selectedVariant.value) return selectedVariant.value.sale_price || selectedVariant.value.price || props.product.price;
    return props.product.sale_price || props.product.price;
});

const originalPrice = computed(() => {
    if (selectedVariant.value?.sale_price) return selectedVariant.value.price || props.product.price;
    return props.product.sale_price ? props.product.price : null;
});

// group variant attribute values by attribute name for selector UI
const attributeGroups = computed(() => {
    const groups = {};
    (props.product.variants || []).forEach((variant) => {
        (variant.attribute_values || []).forEach((value) => {
            const attrName = value.attribute?.name;
            if (!attrName) return;
            groups[attrName] = groups[attrName] || new Map();
            groups[attrName].set(value.id, value);
        });
    });
    return groups;
});

const cartForm = useForm({
    product_id: props.product.id,
    product_variant_id: null,
    quantity: 1,
});

function addToCart() {
    cartForm.product_variant_id = selectedVariant.value?.id ?? null;
    cartForm.post(route('cart.store'), { preserveScroll: true });
}

const reviewForm = useForm({ rating: 5, comment: '' });

function submitReview() {
    reviewForm.post(route('reviews.store', props.product.slug), {
        preserveScroll: true,
        onSuccess: () => reviewForm.reset(),
    });
}

const seo = props.product.seo;

const jsonLd = computed(() => ({
    '@context': 'https://schema.org',
    '@type': 'Product',
    name: props.product.name,
    description: props.product.short_description,
    sku: props.product.sku,
    image: props.product.images?.map((i) => resolveImage(i.path)),
    brand: props.product.brand ? { '@type': 'Brand', name: props.product.brand.name } : undefined,
    offers: {
        '@type': 'Offer',
        priceCurrency: currencySymbol.value,
        price: displayPrice.value,
        availability: props.product.in_stock ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
    },
}));
</script>

<template>
    <Seo
        :title="seo?.meta_title || product.name"
        :description="seo?.meta_description || product.short_description"
        :og-image="seo?.og_image || product.images?.[0]?.path"
        :json-ld="jsonLd"
    />

    <div class="mx-auto max-w-7xl px-4 py-8">
        <nav class="mb-6 text-sm text-gray-500">
            <Link :href="route('home')" class="hover:text-brand-600">{{ t('common.back_home') }}</Link>
            <span class="mx-1">/</span>
            <Link v-if="product.category" :href="route('categories.show', product.category.slug)" class="hover:text-brand-600">
                {{ product.category.name }}
            </Link>
            <span class="mx-1">/</span>
            <span class="text-gray-700">{{ product.name }}</span>
        </nav>

        <div class="grid grid-cols-1 gap-10 lg:grid-cols-2">
            <div>
                <div class="mb-3 aspect-square overflow-hidden rounded-xl bg-gray-100">
                    <img :src="resolveImage(activeImage?.path)" :alt="product.name" class="h-full w-full object-cover" />
                </div>
                <div v-if="product.images?.length > 1" class="flex gap-2">
                    <button
                        v-for="img in product.images"
                        :key="img.id"
                        @click="activeImage = img"
                        class="h-16 w-16 shrink-0 overflow-hidden rounded-lg border-2"
                        :class="activeImage?.id === img.id ? 'border-brand-600' : 'border-transparent'"
                    >
                        <img :src="resolveImage(img.path)" class="h-full w-full object-cover" />
                    </button>
                </div>
            </div>

            <div>
                <p v-if="product.brand" class="mb-1 text-sm text-gray-400">{{ product.brand.name }}</p>
                <h1 class="mb-3 text-2xl font-extrabold text-gray-800">{{ product.name }}</h1>

                <div class="mb-2 flex items-center gap-2">
                    <StarRating :rating="product.average_rating" />
                    <span class="text-sm text-gray-500">({{ product.approved_reviews_count ?? product.reviews?.length ?? 0 }} {{ t('product.reviews_count') }})</span>
                </div>

                <div class="mb-5 flex items-baseline gap-3">
                    <span class="text-3xl font-extrabold text-brand-600">{{ formatPrice(displayPrice, currencySymbol) }}</span>
                    <span v-if="originalPrice" class="text-lg text-gray-400 line-through">{{ formatPrice(originalPrice, currencySymbol) }}</span>
                </div>

                <p class="mb-6 text-gray-600">{{ product.short_description }}</p>

                <div v-for="(values, attrName) in attributeGroups" :key="attrName" class="mb-4">
                    <h3 class="mb-2 text-sm font-semibold text-gray-700">{{ attrName }}</h3>
                    <div class="flex flex-wrap gap-2">
                        <button
                            v-for="[id, value] in values"
                            :key="id"
                            @click="selectedVariant = product.variants.find(v => (v.attribute_values || []).some(av => av.id === id))"
                            class="rounded-full border px-4 py-1.5 text-sm"
                            :class="selectedVariant?.attribute_values?.some(av => av.id === id) ? 'border-brand-600 bg-brand-50 text-brand-700' : 'border-gray-300 text-gray-600'"
                        >
                            {{ value.value }}
                        </button>
                    </div>
                </div>

                <div class="mb-6 flex items-center gap-3">
                    <input
                        v-model.number="cartForm.quantity"
                        type="number"
                        min="1"
                        class="w-20 rounded-lg border-gray-300 text-center"
                    />
                    <button
                        @click="addToCart"
                        :disabled="cartForm.processing || (product.manage_stock && product.stock_quantity === 0)"
                        class="flex-1 rounded-lg bg-brand-600 py-3 font-semibold text-white transition hover:bg-brand-700 disabled:opacity-50"
                    >
                        {{ product.manage_stock && product.stock_quantity === 0 ? t('product.out_of_stock') : t('product.add_to_cart') }}
                    </button>
                </div>

                <div class="rounded-lg border border-gray-100 p-4 text-sm text-gray-500">
                    <p>{{ t('product.sku') }}: {{ product.sku }}</p>
                    <p v-if="product.category">{{ t('product.category') }}: {{ product.category.name }}</p>
                </div>
            </div>
        </div>

        <div class="mt-12 max-w-3xl">
            <h2 class="mb-3 text-xl font-bold text-gray-800">{{ t('product.description') }}</h2>
            <div class="prose max-w-none text-gray-600" v-html="product.description"></div>
        </div>

        <div class="mt-12 max-w-3xl">
            <h2 class="mb-4 text-xl font-bold text-gray-800">{{ t('product.reviews') }}</h2>

            <div v-if="product.approved_reviews?.length" class="mb-8 space-y-4">
                <div v-for="review in product.approved_reviews" :key="review.id" class="rounded-lg border border-gray-100 p-4">
                    <div class="mb-1 flex items-center justify-between">
                        <span class="font-semibold text-gray-700">{{ review.user?.name }}</span>
                        <StarRating :rating="review.rating" size="h-3.5 w-3.5" />
                    </div>
                    <p class="text-sm text-gray-600">{{ review.comment }}</p>
                </div>
            </div>
            <p v-else class="mb-8 text-sm text-gray-500">{{ t('product.no_reviews') }}</p>

            <form v-if="isAuthenticated" @submit.prevent="submitReview" class="rounded-lg border border-gray-100 p-4">
                <h3 class="mb-2 font-semibold text-gray-700">{{ t('product.add_review') }}</h3>
                <select v-model="reviewForm.rating" class="mb-2 rounded-lg border-gray-300 text-sm">
                    <option v-for="n in [5,4,3,2,1]" :key="n" :value="n">{{ n }} {{ t('product.stars') }}</option>
                </select>
                <textarea
                    v-model="reviewForm.comment"
                    rows="3"
                    :placeholder="t('product.comment_placeholder')"
                    class="mb-2 w-full rounded-lg border-gray-300 text-sm"
                ></textarea>
                <button type="submit" class="rounded-lg bg-gray-800 px-4 py-2 text-sm text-white hover:bg-gray-900">{{ t('product.submit') }}</button>
            </form>
            <p v-else class="text-sm text-gray-500">
                <Link :href="route('login')" class="text-brand-600">{{ t('auth.log_in') }}</Link> {{ t('product.login_to_review') }}
            </p>
        </div>

        <div v-if="related.length" class="mt-12">
            <h2 class="mb-4 text-xl font-bold text-gray-800">{{ t('product.related') }}</h2>
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                <ProductCard v-for="p in related" :key="p.id" :product="p" />
            </div>
        </div>
    </div>
</template>
