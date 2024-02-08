<script setup>
const props = defineProps({
    zoomIn: {
        type: Boolean,
        default: true
    }
});
</script>

<template>
    <button class="flex h-10 w-36 items-center justify-center gap-2 rounded-full border-2 border-black bg-dragon-teal font-bold text-white">
        <svg
            v-if="zoomIn"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
            class="h-6 w-6"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"
            />
        </svg>

        <svg 
            v-else
            xmlns="http://www.w3.org/2000/svg" 
            fill="none" 
            viewBox="0 0 24 24" 
            stroke-width="1.5" 
            stroke="currentColor" 
            class="h-6 w-6"
        >
            <path 
                stroke-linecap="round" 
                stroke-linejoin="round" 
                d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" 
            />
        </svg>

        <span class="text-lg">{{ zoomIn ? "Zoom In" : "Zoom Out" }}</span>
    </button>
</template>

