<script setup>
import { computed, defineProps } from 'vue';

const props = defineProps({
    cardType: {
        type: String,
        default: '',
    },

    amount: {
        type: String,
        default: '0',
    },

    percentage: {
        type: Number,
        default: 0,
    },
});

const validatedPercentage = computed(() => {
    let validPercentage = Math.max( props.percentage >0 ? props.percentage + 5 : 0, Math.min(100, props.percentage));

    if (Number.isNaN(validPercentage)) {
        validPercentage = 0;
    }

    return validPercentage;
});

const textColorClass = computed(() => {
    switch (props.cardType) {
    case 'dragon':
        return 'border-dragon-red bg-dragon-dragon';
    case 'tiger':
        return 'border-dragon-blue bg-dragon-tiger';
    case 'tie':
        return 'border-dragon-green bg-dragon-tie';
    default:
        return '';
    }
});
</script>

<template>
    <div class="flex h-full flex-col">
        <div class="flex h-full flex-1 items-end justify-end">
            <div class="w-full flex flex-col text-center text-sm h-full justify-end uppercase">
                <slot />
                <div
                    class="text-center font-rodfat text-sm transition-all duration-700 ease-in-out m-1"
                    :class="[textColorClass, validatedPercentage>0? 'border-2': 'border-b-2']"
                    :style="{ height: `${validatedPercentage}%` }"
                >
                </div>
            </div>
        </div>
        <div class="flex h-8 flex-none items-center justify-center font-rodfat">
            {{ props.amount }}
        </div>
    </div>
</template>
