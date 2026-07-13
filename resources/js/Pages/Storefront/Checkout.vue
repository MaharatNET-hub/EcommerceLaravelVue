<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue';
import Seo from '@/Components/Seo.vue';
import { formatPrice } from '@/helpers/format';
import { useTrans } from '@/composables/useTrans';

defineOptions({ layout: StorefrontLayout });

const { t } = useTrans();

const props = defineProps({
    cart: Object,
    addresses: Array,
    shippingFee: Number,
});

const page = usePage();
const currencySymbol = computed(() => page.props.siteSettings?.currency_symbol || '$');

const subtotal = computed(() =>
    props.cart.items.reduce((sum, item) => sum + Number(item.price) * item.quantity, 0),
);
const total = computed(() => subtotal.value + Number(props.shippingFee || 0));

const defaultAddress = props.addresses.find((a) => a.is_default) || props.addresses[0];

const form = useForm({
    full_name: defaultAddress?.full_name || '',
    phone: defaultAddress?.phone || '',
    country: defaultAddress?.country || '',
    city: defaultAddress?.city || '',
    address_line: defaultAddress?.address_line || '',
    postal_code: defaultAddress?.postal_code || '',
    coupon_code: '',
    notes: '',
});

function submit() {
    form.post(route('checkout.store'));
}
</script>

<template>
    <Seo :title="t('checkout.title')" robots="noindex,follow" />

    <div class="mx-auto max-w-5xl px-4 py-10">
        <h1 class="mb-6 text-2xl font-extrabold text-gray-800">{{ t('checkout.title') }}</h1>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
            <form @submit.prevent="submit" class="space-y-4 lg:col-span-2">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">{{ t('checkout.full_name') }}</label>
                        <input v-model="form.full_name" type="text" class="w-full rounded-lg border-gray-300" required />
                        <p v-if="form.errors.full_name" class="mt-1 text-sm text-red-600">{{ form.errors.full_name }}</p>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">{{ t('checkout.phone') }}</label>
                        <input v-model="form.phone" type="text" class="w-full rounded-lg border-gray-300" required />
                        <p v-if="form.errors.phone" class="mt-1 text-sm text-red-600">{{ form.errors.phone }}</p>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">{{ t('checkout.country') }}</label>
                        <input v-model="form.country" type="text" class="w-full rounded-lg border-gray-300" required />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">{{ t('checkout.city') }}</label>
                        <input v-model="form.city" type="text" class="w-full rounded-lg border-gray-300" required />
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-1 block text-sm font-medium text-gray-700">{{ t('checkout.address_line') }}</label>
                        <input v-model="form.address_line" type="text" class="w-full rounded-lg border-gray-300" required />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">{{ t('checkout.postal_code') }}</label>
                        <input v-model="form.postal_code" type="text" class="w-full rounded-lg border-gray-300" />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">{{ t('checkout.coupon_code') }}</label>
                        <input v-model="form.coupon_code" type="text" class="w-full rounded-lg border-gray-300" />
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-1 block text-sm font-medium text-gray-700">{{ t('checkout.notes') }}</label>
                        <textarea v-model="form.notes" rows="3" class="w-full rounded-lg border-gray-300"></textarea>
                    </div>
                </div>

                <div class="rounded-lg border border-gray-100 p-4 text-sm text-gray-600">
                    {{ t('checkout.cod_note') }}
                </div>

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full rounded-lg bg-brand-600 py-3 font-semibold text-white hover:bg-brand-700 disabled:opacity-50"
                >
                    {{ t('checkout.confirm_order') }}
                </button>
            </form>

            <div class="h-fit rounded-xl border border-gray-100 p-6">
                <h2 class="mb-4 text-lg font-bold text-gray-800">{{ t('checkout.order_summary') }}</h2>
                <ul class="mb-4 space-y-2 text-sm text-gray-600">
                    <li v-for="item in cart.items" :key="item.id" class="flex justify-between">
                        <span>{{ item.product.name }} × {{ item.quantity }}</span>
                        <span>{{ formatPrice(item.price * item.quantity, currencySymbol) }}</span>
                    </li>
                </ul>
                <div class="space-y-1 border-t border-gray-100 pt-3 text-sm">
                    <div class="flex justify-between text-gray-600">
                        <span>{{ t('cart.subtotal') }}</span>
                        <span>{{ formatPrice(subtotal, currencySymbol) }}</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>{{ t('checkout.shipping') }}</span>
                        <span>{{ formatPrice(shippingFee, currencySymbol) }}</span>
                    </div>
                    <div class="flex justify-between text-base font-bold text-gray-800">
                        <span>{{ t('checkout.total') }}</span>
                        <span>{{ formatPrice(total, currencySymbol) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
