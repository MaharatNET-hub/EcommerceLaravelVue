<script setup>
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import Seo from '@/Components/Seo.vue';
import { useTrans } from '@/composables/useTrans';

defineOptions({ layout: StorefrontLayout });

const { t } = useTrans();

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <Seo :title="t('profile.title')" robots="noindex,follow" />

    <div class="mx-auto max-w-3xl px-4 py-10">
        <h1 class="mb-6 text-2xl font-extrabold text-gray-800">{{ t('profile.title') }}</h1>

        <div class="space-y-6">
            <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
                <UpdateProfileInformationForm :must-verify-email="mustVerifyEmail" :status="status" />
            </div>

            <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
                <UpdatePasswordForm />
            </div>

            <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
                <DeleteUserForm />
            </div>
        </div>
    </div>
</template>
