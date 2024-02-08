<script setup>
import { defineProps } from 'vue';

const props = defineProps({
    state: String,
    no: String,
});
const getSpanClass = () => {
    switch (props.state) {
    case 'T':
        return ['bg-dragon-tiger', 'border-dragon-blue'];
    case 'D':
        return ['bg-dragon-dragon', 'border-dragon-red'];
    case 'E':
        return ['bg-dragon-tie', 'border-dragon-green'];
    case 'C':
        return ['bg-cancel', 'border-dragon-primary'];
    default:
        return ['bg-transparent', 'border-transparent'];
    }
};
</script>

<template>
    <div
        class="m-px flex h-[30px] w-[30px] items-center justify-center rounded-full border text-[8px] text-white"
        :class="getSpanClass()"
    >
        {{ props.no }}
    </div>
</template>