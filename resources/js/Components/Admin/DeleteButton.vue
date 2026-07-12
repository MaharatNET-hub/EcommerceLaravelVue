<script setup>
import { router } from '@inertiajs/vue3';

const props = defineProps({
    href: { type: String, required: true },
    confirmText: { type: String, default: 'هل أنت متأكد من الحذف؟ لا يمكن التراجع عن هذا الإجراء.' },
});

function destroy() {
    if (confirm(props.confirmText)) {
        router.delete(props.href, { preserveScroll: true });
    }
}
</script>

<template>
    <button type="button" @click="destroy" class="text-sm text-red-600 hover:underline">
        <slot>حذف</slot>
    </button>
</template>
