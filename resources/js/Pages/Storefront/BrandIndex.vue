<script setup>
import { Link } from '@inertiajs/vue3';
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue';
import Seo from '@/Components/Seo.vue';
import { resolveImage } from '@/helpers/format';

defineOptions({ layout: StorefrontLayout });

defineProps({ brands: Array });
</script>

<template>
    <Seo title="جميع البراندات" />

    <div class="mx-auto max-w-7xl px-4 py-10">
        <h1 class="mb-6 text-2xl font-extrabold text-gray-800">البراندات</h1>
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">
            <Link
                v-for="brand in brands"
                :key="brand.id"
                :href="route('brands.show', brand.slug)"
                class="flex flex-col items-center gap-3 rounded-xl border border-gray-100 p-6 text-center transition hover:shadow"
            >
                <img :src="resolveImage(brand.logo)" :alt="brand.name" class="h-14" />
                <span class="font-medium text-gray-700">{{ brand.name }}</span>
                <span class="text-xs text-gray-400">{{ brand.products_count }} منتج</span>
            </Link>
        </div>
    </div>
</template>
