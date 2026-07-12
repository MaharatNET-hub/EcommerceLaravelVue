<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import { resolveImage } from '@/helpers/format';

const page = usePage();
const site = computed(() => page.props.siteSettings);
const categories = computed(() => page.props.navCategories || []);
const footerPages = computed(() => page.props.footerPages || []);
const cartCount = computed(() => page.props.cartCount || 0);
const user = computed(() => page.props.auth?.user);
const permissions = computed(() => page.props.auth?.permissions || []);
const canAccessAdmin = computed(() => permissions.value.length > 0);

const search = ref(page.props.searchQuery || '');
const mobileMenuOpen = ref(false);

function submitSearch() {
    router.get(route('search'), { q: search.value }, { preserveState: true });
}

const successMessage = computed(() => page.props.flash?.success);
const errorMessage = computed(() => page.props.flash?.error);
</script>

<template>
    <div class="flex min-h-screen flex-col bg-gray-50">
        <div
            v-if="successMessage"
            class="bg-emerald-600 px-4 py-2 text-center text-sm text-white"
        >
            {{ successMessage }}
        </div>
        <div v-if="errorMessage" class="bg-red-600 px-4 py-2 text-center text-sm text-white">
            {{ errorMessage }}
        </div>

        <header class="sticky top-0 z-30 border-b border-gray-200 bg-white/95 backdrop-blur">
            <div class="mx-auto flex max-w-7xl items-center gap-4 px-4 py-3">
                <button class="lg:hidden" @click="mobileMenuOpen = !mobileMenuOpen">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <Link :href="route('home')" class="flex shrink-0 items-center gap-2">
                    <img v-if="site.logo" :src="resolveImage(site.logo)" :alt="site.site_name" class="h-9" />
                    <span class="text-xl font-extrabold text-brand-600">{{ site.site_name }}</span>
                </Link>

                <form @submit.prevent="submitSearch" class="hidden flex-1 md:block">
                    <div class="relative">
                        <input
                            v-model="search"
                            type="search"
                            placeholder="ابحث عن منتج..."
                            class="w-full rounded-full border-gray-300 py-2 ps-4 pe-10 text-sm focus:border-brand-500 focus:ring-brand-500"
                        />
                        <button type="submit" class="absolute inset-y-0 end-3 flex items-center text-gray-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.65 4.65a7.5 7.5 0 0011.99 11.99z" />
                            </svg>
                        </button>
                    </div>
                </form>

                <div class="ms-auto flex items-center gap-4">
                    <Link :href="route('cart.index')" class="relative">
                        <svg class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span
                            v-if="cartCount > 0"
                            class="absolute -top-2 -end-2 flex h-5 w-5 items-center justify-center rounded-full bg-brand-600 text-xs text-white"
                        >
                            {{ cartCount }}
                        </span>
                    </Link>

                    <div v-if="user" class="group relative">
                        <button class="flex items-center gap-1 text-sm text-gray-700">
                            {{ user.name }}
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div class="invisible absolute end-0 z-40 mt-2 w-48 rounded-lg border border-gray-100 bg-white py-1 opacity-0 shadow-lg transition group-hover:visible group-hover:opacity-100">
                            <Link :href="route('orders.index')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">طلباتي</Link>
                            <Link :href="route('profile.edit')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">الملف الشخصي</Link>
                            <Link v-if="canAccessAdmin" :href="route('admin.dashboard')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">لوحة التحكم</Link>
                            <Link :href="route('logout')" method="post" as="button" class="block w-full px-4 py-2 text-start text-sm text-red-600 hover:bg-gray-50">تسجيل الخروج</Link>
                        </div>
                    </div>
                    <Link v-else :href="route('login')" class="text-sm font-medium text-gray-700 hover:text-brand-600">تسجيل الدخول</Link>
                </div>
            </div>

            <nav class="hidden border-t border-gray-100 lg:block">
                <div class="mx-auto flex max-w-7xl gap-6 px-4 py-2.5 text-sm">
                    <Link
                        v-for="category in categories"
                        :key="category.id"
                        :href="route('categories.show', category.slug)"
                        class="font-medium text-gray-600 hover:text-brand-600"
                    >
                        {{ category.name }}
                    </Link>
                    <Link :href="route('brands.index')" class="font-medium text-gray-600 hover:text-brand-600">البراندات</Link>
                </div>
            </nav>

            <nav v-if="mobileMenuOpen" class="border-t border-gray-100 lg:hidden">
                <div class="flex flex-col gap-1 px-4 py-3 text-sm">
                    <Link
                        v-for="category in categories"
                        :key="category.id"
                        :href="route('categories.show', category.slug)"
                        class="py-1.5 font-medium text-gray-600"
                    >
                        {{ category.name }}
                    </Link>
                    <Link :href="route('brands.index')" class="py-1.5 font-medium text-gray-600">البراندات</Link>
                </div>
            </nav>
        </header>

        <main class="flex-1">
            <slot />
        </main>

        <footer class="mt-12 border-t border-gray-200 bg-white">
            <div class="mx-auto grid max-w-7xl grid-cols-1 gap-8 px-4 py-10 sm:grid-cols-2 lg:grid-cols-4">
                <div>
                    <h3 class="mb-3 text-lg font-bold text-brand-600">{{ site.site_name }}</h3>
                    <p class="text-sm text-gray-500">{{ site.address }}</p>
                    <p v-if="site.phone" class="mt-1 text-sm text-gray-500">{{ site.phone }}</p>
                    <p v-if="site.email" class="mt-1 text-sm text-gray-500">{{ site.email }}</p>
                </div>
                <div>
                    <h4 class="mb-3 font-semibold text-gray-800">روابط سريعة</h4>
                    <ul class="space-y-1.5 text-sm text-gray-500">
                        <li v-for="p in footerPages" :key="p.id">
                            <Link :href="route('pages.show', p.slug)">{{ p.title }}</Link>
                        </li>
                    </ul>
                </div>
                <div>
                    <h4 class="mb-3 font-semibold text-gray-800">الدعم</h4>
                    <ul class="space-y-1.5 text-sm text-gray-500">
                        <li><Link :href="route('cart.index')">سلة التسوق</Link></li>
                        <li><Link :href="route('orders.index')">تتبع الطلب</Link></li>
                    </ul>
                </div>
                <div>
                    <h4 class="mb-3 font-semibold text-gray-800">تابعنا</h4>
                    <div class="flex gap-3 text-sm text-gray-500">
                        <a v-if="site.facebook" :href="site.facebook" target="_blank" rel="noopener">فيسبوك</a>
                        <a v-if="site.instagram" :href="site.instagram" target="_blank" rel="noopener">إنستغرام</a>
                        <a v-if="site.whatsapp" :href="site.whatsapp" target="_blank" rel="noopener">واتساب</a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-100 py-4 text-center text-xs text-gray-400">
                © {{ new Date().getFullYear() }} {{ site.site_name }}. جميع الحقوق محفوظة.
            </div>
        </footer>
    </div>
</template>
