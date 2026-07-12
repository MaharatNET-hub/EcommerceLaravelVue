<script setup>
import { Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import DeleteButton from '@/Components/Admin/DeleteButton.vue';
import { resolveImage } from '@/helpers/format';

defineOptions({ layout: AdminLayout });

defineProps({ sliders: Array });
</script>

<template>
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-extrabold text-gray-800">السلايدر الرئيسي</h1>
        <Link :href="route('admin.sliders.create')" class="rounded-lg bg-brand-600 px-4 py-2 text-sm text-white hover:bg-brand-700">
            + شريحة جديدة
        </Link>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <div v-for="slider in sliders" :key="slider.id" class="overflow-hidden rounded-xl bg-white shadow-sm">
            <img :src="resolveImage(slider.image)" class="h-40 w-full object-cover" />
            <div class="p-4">
                <h3 class="font-semibold text-gray-800">{{ slider.title }}</h3>
                <p class="text-sm text-gray-500">{{ slider.subtitle }}</p>
                <div class="mt-3 flex items-center justify-between">
                    <span class="rounded-full px-2 py-1 text-xs" :class="slider.is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500'">
                        {{ slider.is_active ? 'مفعّل' : 'معطّل' }}
                    </span>
                    <div class="flex gap-3">
                        <Link :href="route('admin.sliders.edit', slider.id)" class="text-sm text-brand-600 hover:underline">تعديل</Link>
                        <DeleteButton :href="route('admin.sliders.destroy', slider.id)" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
