<script setup>
import { computed } from "vue";

const props = defineProps({
    type: {
        type: String,
        default: "",
    },

    title: {
        type: String,
        default: "",
    },

    grandTotal: {
        type: String,
        default: "0",
    },

    totalRed: {
        type: String,
        default: "0",
    },

    totalBlack: {
        type: String,
        default: "0",
    },
});

const textColorClass = computed(() => {
    switch (props.type) {
        case "tie":
            return "text-dragon-teal";
        case "tiger":
            return "text-dragon-blue";
        case "dragon":
            return "text-dragon-red";
        default:
            return "text-black";
    }
});
</script>

<template>
    <div class="rounded-md bg-white py-2 font-bold">
        <div class="flex items-stretch divide-x divide-gray-500 font-bold">
            <div class="flex w-1/2 items-center justify-between px-6 uppercase">
                <span :class="textColorClass">
                    {{ `${props.title}:` }}
                </span>

                <span :class="textColorClass">
                    {{ props.grandTotal }}
                </span>
            </div>

            <div class="flex w-1/2 items-center justify-around font-rodfat">
                <div class="text-dragon-red">
                    {{ props.totalRed }}
                </div>

                <div class="text-black">
                    {{ props.totalBlack }}
                </div>
            </div>
        </div>
    </div>
</template>
