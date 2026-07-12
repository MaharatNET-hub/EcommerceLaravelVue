<script setup>
import { ref } from 'vue';

const props = defineProps({
    tabs: { type: Array, required: true },
});

const active = ref(props.tabs[0]);
</script>

<template>
    <div>
        <div class="mb-4 flex gap-1 border-b border-gray-200">
            <button
                v-for="tab in tabs"
                :key="tab"
                type="button"
                @click="active = tab"
                class="border-b-2 px-4 py-2 text-sm font-medium transition"
                :class="active === tab ? 'border-brand-600 text-brand-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
            >
                {{ tab }}
            </button>
        </div>
        <div v-for="tab in tabs" :key="tab" v-show="active === tab">
            <slot :name="tab.replace(/\s+/g, '_')" />
        </div>
    </div>
</template>
