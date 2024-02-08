<script setup>
import { computed } from "vue";

const props = defineProps({
    gameNumber: String,
    result: String,
});

const spanClass = computed(() => {
    switch (props.result) {
        case "T":
            return "bg-dragon-tiger border-dragon-blue";
        case "D":
            return "bg-dragon-dragon border-dragon-red";
        case "E":
            return "bg-dragon-tie border-dragon-green";
        default:
            return "bg-gray-500 border-gray-700";
    };
});

const hoverClass = computed(() => {
    switch (props.result) {
        case "T":
            return "hover:bg-dragon-tiger/50";
        case "D":
            return "hover:bg-dragon-dragon/50";
        case "E":
            return "hover:bg-dragon-tie/50";
        default:
            return "hover:bg-gray-500/50";
    };
});

const caseTitle = computed(() => {
    switch (props.result) {
        case "E":
            return "T";
        default:
            return props.result;
    };
});
</script>

<template>
    <div
        class="entry cursor-pointer gap-2 font-rodfat"
        :class="hoverClass"
    >
        <div class="text-base">{{ gameNumber }}</div>

        <div
            class="flex h-7 w-7 items-center justify-center rounded-full border text-sm uppercase leading-none"
            :class="spanClass"
        >
            <span class="pt-0.5">{{ caseTitle }}</span>
        </div>
    </div>
</template>
