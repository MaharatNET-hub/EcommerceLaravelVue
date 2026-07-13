<script setup>
import { Link } from '@inertiajs/vue3';
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue';
import Seo from '@/Components/Seo.vue';
import ProductCard from '@/Components/ProductCard.vue';
import { resolveImage } from '@/helpers/format';
import { useTrans } from '@/composables/useTrans';

defineOptions({ layout: StorefrontLayout });

const { t } = useTrans();

defineProps({
    sliders: Array,
    categories: Array,
    featuredProducts: Array,
    newProducts: Array,
    brands: Array,
    seo: Object,
});
</script>

<template>
    <Seo :title="seo.title" :description="seo.description" :og-image="seo.og_image" />

    <section v-if="sliders.length" class="relative">
        <div class="overflow-x-auto scroll-smooth">
            <div class="flex snap-x">
                <div v-for="slide in sliders" :key="slide.id" class="w-full shrink-0 snap-center">
                    <div class="relative h-64 w-full sm:h-80 lg:h-[420px]">
                        <img :src="resolveImage(slide.image)" :alt="slide.title" class="h-full w-full object-cover" />
                        <div class="absolute inset-0 flex flex-col items-center justify-center bg-black/30 px-4 text-center text-white">
                            <h2 class="text-2xl font-extrabold sm:text-4xl">{{ slide.title }}</h2>
                            <p class="mt-2 text-sm sm:text-lg">{{ slide.subtitle }}</p>
                            <Link
                                v-if="slide.button_text"
                                :href="slide.button_link || '/'"
                                class="mt-4 rounded-full bg-brand-600 px-6 py-2 text-sm font-semibold hover:bg-brand-700"
                            >
                                {{ slide.button_text }}
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-10">
        <h2 class="mb-5 text-xl font-bold text-gray-800">{{ t('home.shop_by_category') }}</h2>
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-4 lg:grid-cols-8">
            <Link
                v-for="category in categories"
                :key="category.id"
                :href="route('categories.show', category.slug)"
                class="group flex flex-col items-center gap-2 rounded-xl border border-gray-100 p-3 text-center transition hover:border-brand-200 hover:shadow"
            >
                <img :src="resolveImage(category.image)" :alt="category.name" class="h-16 w-16 rounded-full object-cover" />
                <span class="text-xs font-medium text-gray-700 group-hover:text-brand-600">{{ category.name }}</span>
            </Link>
        </div>
    </section>

    <section v-if="featuredProducts.length" class="bg-white py-10">
        <div class="mx-auto max-w-7xl px-4">
            <h2 class="mb-5 text-xl font-bold text-gray-800">{{ t('home.featured_products') }}</h2>
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
                <ProductCard v-for="product in featuredProducts" :key="product.id" :product="product" />
            </div>
        </div>
    </section>

    <section v-if="newProducts.length" class="mx-auto max-w-7xl px-4 py-10">
        <h2 class="mb-5 text-xl font-bold text-gray-800">{{ t('home.new_arrivals') }}</h2>
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
            <ProductCard v-for="product in newProducts" :key="product.id" :product="product" />
        </div>
    </section>

    <section v-if="brands.length" class="bg-white py-10">
        <div class="mx-auto max-w-7xl px-4">
            <h2 class="mb-5 text-xl font-bold text-gray-800">{{ t('home.brands') }}</h2>
            <div class="flex flex-wrap items-center gap-8">
                <Link v-for="brand in brands" :key="brand.id" :href="route('brands.show', brand.slug)">
                    <img :src="resolveImage(brand.logo)" :alt="brand.name" class="h-12 grayscale transition hover:grayscale-0" />
                </Link>
            </div>
        </div>
    </section>
</template>
