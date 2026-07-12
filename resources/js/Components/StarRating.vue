<script setup>
defineProps({
    rating: { type: Number, default: 0 },
    size: { type: String, default: 'h-4 w-4' },
});
</script>

<template>
    <div class="flex items-center gap-0.5" :aria-label="`${rating} من 5`">
        <svg
            v-for="star in 5"
            :key="star"
            :class="[size, star <= Math.round(rating) ? 'text-amber-400' : 'text-gray-300']"
            fill="currentColor"
            viewBox="0 0 20 20"
        >
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.958a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447a1 1 0 00-.363 1.118l1.287 3.957c.3.922-.755 1.688-1.538 1.118l-3.367-2.447a1 1 0 00-1.176 0l-3.367 2.447c-.783.57-1.838-.196-1.538-1.118l1.287-3.957a1 1 0 00-.363-1.118L2.063 9.385c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.286-3.958z" />
        </svg>
    </div>
</template>
