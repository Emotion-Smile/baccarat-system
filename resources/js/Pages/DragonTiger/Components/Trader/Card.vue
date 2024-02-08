<script setup>
import { ref, computed } from "vue";

const props = defineProps({
    cardName: {
        type: String,
        default: "0",
    },

    cardType: {
        type: String,
        default: "heart",
    },
});

const textColorClass = computed(() => {
    switch (props.cardType) {
        case "heart":
            return "text-red-500";
        case "diamond":
            return "text-red-500";
        case "club":
            return "text-black";
        case "spade":
            return "text-black";
        default:
            return "";
    };
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
    <div class="relative h-full">
        <div class="relative overflow-hidden rounded-xl">
            <img
                :src="asset('dragon-tiger/images/card.svg')"
                class="h-full w-full"
            />

            <div
                :class="textColorClass"
                class="absolute top-3 left-3 scale-150 text-2xl font-bold"
            >
                {{ cardName }}
            </div>

            <img
                v-if="props.cardType !== 'default'"
                :src="asset(suitImageUrl)"
                class="absolute bottom-2 right-2 h-8"
            />
        </div>
    </div>
</template>
