<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const page = usePage();
const permissions = computed(() => page.props.auth?.permissions || []);
const user = computed(() => page.props.auth?.user);
const sidebarOpen = ref(false);

function can(permission) {
    return permissions.value.includes(permission);
}

const navSections = computed(() => [
    {
        label: 'عام',
        items: [
            { name: 'لوحة التحكم', route: 'admin.dashboard', show: true },
        ],
    },
    {
        label: 'المتجر',
        items: [
            { name: 'التصنيفات', route: 'admin.categories.index', show: can('categories.view') },
            { name: 'البراندات', route: 'admin.brands.index', show: can('brands.view') },
            { name: 'المنتجات', route: 'admin.products.index', show: can('products.view') },
            { name: 'الخصائص', route: 'admin.attributes.index', show: can('products.update') },
        ],
    },
    {
        label: 'المبيعات',
        items: [
            { name: 'الطلبات', route: 'admin.orders.index', show: can('orders.view') },
            { name: 'كوبونات الخصم', route: 'admin.coupons.index', show: can('coupons.view') },
        ],
    },
    {
        label: 'المحتوى',
        items: [
            { name: 'الصفحات', route: 'admin.pages.index', show: can('pages.view') },
            { name: 'السلايدر', route: 'admin.sliders.index', show: can('sliders.view') },
        ],
    },
    {
        label: 'الإدارة',
        items: [
            { name: 'المستخدمون', route: 'admin.users.index', show: can('users.view') },
            { name: 'الأدوار والصلاحيات', route: 'admin.roles.index', show: can('roles.view') },
            { name: 'الإعدادات', route: 'admin.settings.index', show: can('settings.view') },
        ],
    },
].map((section) => ({ ...section, items: section.items.filter((i) => i.show) })).filter((s) => s.items.length));

function isActive(routeName) {
    return route().current(routeName + '*');
}
</script>

<template>
    <div class="flex min-h-screen bg-gray-100">
        <aside
            class="fixed inset-y-0 start-0 z-40 w-64 -translate-x-full transform bg-gray-900 text-gray-200 transition-transform lg:static lg:translate-x-0"
            :class="{ 'translate-x-0': sidebarOpen }"
            :dir="page.props.locale === 'ar' ? 'rtl' : undefined"
        >
            <div class="flex h-16 items-center gap-2 px-5 text-lg font-bold text-white">
                <Link :href="route('admin.dashboard')">لوحة التحكم</Link>
            </div>
            <nav class="space-y-6 overflow-y-auto px-3 pb-10" style="max-height: calc(100vh - 4rem)">
                <div v-for="section in navSections" :key="section.label">
                    <p class="mb-2 px-2 text-xs font-semibold uppercase text-gray-500">{{ section.label }}</p>
                    <Link
                        v-for="item in section.items"
                        :key="item.route"
                        :href="route(item.route)"
                        class="mb-1 block rounded-lg px-3 py-2 text-sm transition"
                        :class="isActive(item.route) ? 'bg-brand-600 text-white' : 'text-gray-300 hover:bg-gray-800'"
                    >
                        {{ item.name }}
                    </Link>
                </div>
            </nav>
        </aside>

        <div
            v-if="sidebarOpen"
            class="fixed inset-0 z-30 bg-black/40 lg:hidden"
            @click="sidebarOpen = false"
        ></div>

        <div class="flex flex-1 flex-col lg:ms-0">
            <header class="flex h-16 items-center justify-between border-b border-gray-200 bg-white px-4">
                <button class="lg:hidden" @click="sidebarOpen = true">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <div class="ms-auto flex items-center gap-4">
                    <Link :href="route('home')" class="text-sm text-gray-500 hover:text-brand-600">عرض المتجر</Link>
                    <span class="text-sm text-gray-700">{{ user?.name }}</span>
                    <Link :href="route('logout')" method="post" as="button" class="text-sm text-red-600 hover:underline">تسجيل الخروج</Link>
                </div>
            </header>

            <main class="flex-1 p-4 sm:p-6">
                <div v-if="page.props.flash?.success" class="mb-4 rounded-lg bg-emerald-50 p-3 text-sm text-emerald-700">
                    {{ page.props.flash.success }}
                </div>
                <div v-if="page.props.flash?.error" class="mb-4 rounded-lg bg-red-50 p-3 text-sm text-red-700">
                    {{ page.props.flash.error }}
                </div>
                <slot />
            </main>
        </div>
    </div>
</template>
