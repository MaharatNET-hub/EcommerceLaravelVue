<script setup>
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue';
import Seo from '@/Components/Seo.vue';

defineOptions({ layout: StorefrontLayout });

const props = defineProps({ page: Object });

const seo = props.page.seo;
</script>

<template>
    <Seo :title="seo?.meta_title || page.title" :description="seo?.meta_description" :og-image="seo?.og_image" />

    <div class="mx-auto max-w-3xl px-4 py-10">
        <h1 class="mb-6 text-2xl font-extrabold text-gray-800">{{ page.title }}</h1>
        <div class="prose max-w-none text-gray-600" v-html="page.content"></div>
    </div>
</template>
