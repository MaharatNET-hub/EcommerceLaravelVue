<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    title: { type: String, default: '' },
    description: { type: String, default: '' },
    keywords: { type: String, default: '' },
    ogTitle: { type: String, default: '' },
    ogDescription: { type: String, default: '' },
    ogImage: { type: String, default: '' },
    canonicalUrl: { type: String, default: '' },
    robots: { type: String, default: 'index,follow' },
    jsonLd: { type: [Object, Array, null], default: null },
});

const page = usePage();

const siteName = computed(() => page.props.siteSettings?.site_name || 'Shop');

const fullTitle = computed(() =>
    props.title ? `${props.title} | ${siteName.value}` : siteName.value,
);

const resolvedOgImage = computed(() => {
    if (!props.ogImage) return null;
    return props.ogImage.startsWith('http') ? props.ogImage : `${window.location.origin}/storage/${props.ogImage}`;
});

const resolvedCanonical = computed(() => props.canonicalUrl || window.location.href.split('?')[0]);

const jsonLdArray = computed(() => {
    if (!props.jsonLd) return [];
    return Array.isArray(props.jsonLd) ? props.jsonLd : [props.jsonLd];
});
</script>

<template>
    <Head>
        <title>{{ fullTitle }}</title>
        <meta v-if="description" name="description" :content="description" />
        <meta v-if="keywords" name="keywords" :content="keywords" />
        <meta name="robots" :content="robots" />
        <link rel="canonical" :href="resolvedCanonical" />

        <meta property="og:site_name" :content="siteName" />
        <meta property="og:type" content="website" />
        <meta property="og:title" :content="ogTitle || fullTitle" />
        <meta v-if="ogDescription || description" property="og:description" :content="ogDescription || description" />
        <meta property="og:url" :content="resolvedCanonical" />
        <meta v-if="resolvedOgImage" property="og:image" :content="resolvedOgImage" />

        <meta name="twitter:card" :content="resolvedOgImage ? 'summary_large_image' : 'summary'" />
        <meta name="twitter:title" :content="ogTitle || fullTitle" />
        <meta v-if="ogDescription || description" name="twitter:description" :content="ogDescription || description" />
        <meta v-if="resolvedOgImage" name="twitter:image" :content="resolvedOgImage" />

        <component
            :is="'script'"
            v-for="(schema, index) in jsonLdArray"
            :key="index"
            type="application/ld+json"
            v-html="JSON.stringify(schema)"
        />
    </Head>
</template>
