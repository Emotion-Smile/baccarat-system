<script setup>
import { defineProps } from 'vue';

const props = defineProps({
    cardName: {
        type: String,
        default: '0',
    },
    cardType: {
        type: String,
        default: 'heart',
    },
});

const getSuitImage = () => {
    switch (props.cardType) {
    case 'heart':
        return 'dragon-tiger/images/suit_heart.svg';
    case 'diamond':
        return 'dragon-tiger/images/suit_diamond.svg';
    case 'club':
        return 'dragon-tiger/images/suit_club.svg';
    case 'spade':
        return 'dragon-tiger/images/suit_spade.svg';
    default:
        return '';
    }
};
const getTextColorClass = () => {
    switch (props.cardType) {
    case 'heart':
        return 'text-red-500';
    case 'diamond':
        return 'text-red-500';
    case 'club':
        return 'text-black';
    case 'spade':
        return 'text-black';
    default:
        return '';
    }
};
</script>

<template>
    <div class="relative overflow-hidden">
        <img
            class="h-full w-full"
            :src="asset('dragon-tiger/images/card.svg')"
        />
        <div
            class="absolute top-0 left-0 pl-0.5 pt-0.5 font-bold leading-none"
            :class="getTextColorClass()"
        >
            {{ cardName }}
        </div>
        <img
            v-if="props.cardType !== 'default'"
            class="absolute bottom-0.5 right-0.5 h-4"
            :src="asset(getSuitImage())"
        />
    </div>
</template>
