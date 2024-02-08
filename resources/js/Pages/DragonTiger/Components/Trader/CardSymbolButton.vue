<script setup>
import { computed, ref } from "vue";

const props = defineProps({
    cardType: {
        type: String,
        default: "heart",
    },

    isSelected: {
        type: Boolean,
        default: false,
    },
});

const suitImageUrl = computed(() => {
    switch (props.cardType) {
        case "heart":
            return "dragon-tiger/images/suit_heart.svg";
        case "diamond":
            return "dragon-tiger/images/suit_diamond.svg";
        case "club":
            return "dragon-tiger/images/suit_club.svg";
        case "spade":
            return "dragon-tiger/images/suit_spade.svg";
        default:
            return "";
    };
});
</script>

<template>
    <div
        class="flex w-1/2 items-center justify-center rounded hover:bg-white cursor-pointer"
        :class="[
            props.isSelected ? 'bg-white' : 'bg-transparent'
        ]"
    >
        <img 
            class="h-8" 
            :src="asset(suitImageUrl)" 
            :alt="props.cardType" 
        />
    </div>
</template>
