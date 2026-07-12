<script setup>
import { useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Tabs from '@/Components/Admin/Tabs.vue';
import { resolveImage } from '@/helpers/format';

defineOptions({ layout: AdminLayout });

const props = defineProps({
    general: Object,
    social: Object,
    seo: Object,
    logo: String,
    favicon: String,
});

const generalForm = useForm({
    group: 'general',
    site_name: props.general.site_name || '',
    contact_phone: props.general.contact_phone || '',
    contact_email: props.general.contact_email || '',
    contact_address: props.general.contact_address || '',
    currency: props.general.currency || 'USD',
    currency_symbol: props.general.currency_symbol || '$',
    logo: null,
    favicon: null,
});

const socialForm = useForm({
    group: 'social',
    social_facebook: props.social.social_facebook || '',
    social_instagram: props.social.social_instagram || '',
    social_whatsapp: props.social.social_whatsapp || '',
    social_tiktok: props.social.social_tiktok || '',
    social_x: props.social.social_x || '',
});

const seoForm = useForm({
    group: 'seo',
    seo_default_meta_title: props.seo.seo_default_meta_title || '',
    seo_default_meta_description: props.seo.seo_default_meta_description || '',
    seo_default_og_image: null,
    seo_google_verification: props.seo.seo_google_verification || '',
    seo_facebook_pixel: props.seo.seo_facebook_pixel || '',
    seo_google_analytics: props.seo.seo_google_analytics || '',
});

function submitGeneral() {
    generalForm.transform((data) => ({ ...data, _method: 'put' })).post(route('admin.settings.update'), { forceFormData: true, preserveScroll: true });
}
function submitSocial() {
    socialForm.put(route('admin.settings.update'), { preserveScroll: true });
}
function submitSeo() {
    seoForm.transform((data) => ({ ...data, _method: 'put' })).post(route('admin.settings.update'), { forceFormData: true, preserveScroll: true });
}
</script>

<template>
    <h1 class="mb-6 text-2xl font-extrabold text-gray-800">الإعدادات</h1>

    <div class="max-w-3xl rounded-xl bg-white p-6 shadow-sm">
        <Tabs :tabs="['عام', 'التواصل الاجتماعي', 'SEO']">
            <template #عام>
                <form @submit.prevent="submitGeneral" class="space-y-4">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">اسم المتجر</label>
                        <input v-model="generalForm.site_name" type="text" class="w-full rounded-lg border-gray-300" />
                    </div>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">هاتف التواصل</label>
                            <input v-model="generalForm.contact_phone" type="text" class="w-full rounded-lg border-gray-300" />
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">بريد التواصل</label>
                            <input v-model="generalForm.contact_email" type="email" class="w-full rounded-lg border-gray-300" />
                        </div>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">العنوان</label>
                        <input v-model="generalForm.contact_address" type="text" class="w-full rounded-lg border-gray-300" />
                    </div>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">العملة</label>
                            <input v-model="generalForm.currency" type="text" class="w-full rounded-lg border-gray-300" />
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">رمز العملة</label>
                            <input v-model="generalForm.currency_symbol" type="text" class="w-full rounded-lg border-gray-300" />
                        </div>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">الشعار (Logo)</label>
                        <img v-if="logo" :src="resolveImage(logo)" class="mb-2 h-12" />
                        <input type="file" accept="image/*" @input="generalForm.logo = $event.target.files[0]" class="w-full text-sm" />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">أيقونة الموقع (Favicon)</label>
                        <img v-if="favicon" :src="resolveImage(favicon)" class="mb-2 h-8" />
                        <input type="file" accept="image/*" @input="generalForm.favicon = $event.target.files[0]" class="w-full text-sm" />
                    </div>
                    <button type="submit" :disabled="generalForm.processing" class="rounded-lg bg-brand-600 px-6 py-2 text-white hover:bg-brand-700 disabled:opacity-50">
                        حفظ
                    </button>
                </form>
            </template>

            <template #التواصل_الاجتماعي>
                <form @submit.prevent="submitSocial" class="space-y-4">
                    <div v-for="field in ['social_facebook', 'social_instagram', 'social_whatsapp', 'social_tiktok', 'social_x']" :key="field">
                        <label class="mb-1 block text-sm font-medium text-gray-700">{{ field.replace('social_', '') }}</label>
                        <input v-model="socialForm[field]" type="text" class="w-full rounded-lg border-gray-300" placeholder="https://" />
                    </div>
                    <button type="submit" :disabled="socialForm.processing" class="rounded-lg bg-brand-600 px-6 py-2 text-white hover:bg-brand-700 disabled:opacity-50">
                        حفظ
                    </button>
                </form>
            </template>

            <template #SEO>
                <form @submit.prevent="submitSeo" class="space-y-4">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">عنوان SEO الافتراضي للصفحة الرئيسية</label>
                        <input v-model="seoForm.seo_default_meta_title" type="text" class="w-full rounded-lg border-gray-300" />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">وصف SEO الافتراضي</label>
                        <textarea v-model="seoForm.seo_default_meta_description" rows="3" class="w-full rounded-lg border-gray-300"></textarea>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">صورة Open Graph الافتراضية</label>
                        <input type="file" accept="image/*" @input="seoForm.seo_default_og_image = $event.target.files[0]" class="w-full text-sm" />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">رمز تحقق Google Search Console</label>
                        <input v-model="seoForm.seo_google_verification" type="text" class="w-full rounded-lg border-gray-300" />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">معرّف Google Analytics</label>
                        <input v-model="seoForm.seo_google_analytics" type="text" class="w-full rounded-lg border-gray-300" placeholder="G-XXXXXXX" />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">معرّف Facebook Pixel</label>
                        <input v-model="seoForm.seo_facebook_pixel" type="text" class="w-full rounded-lg border-gray-300" />
                    </div>
                    <button type="submit" :disabled="seoForm.processing" class="rounded-lg bg-brand-600 px-6 py-2 text-white hover:bg-brand-700 disabled:opacity-50">
                        حفظ
                    </button>
                </form>
            </template>
        </Tabs>
    </div>
</template>
