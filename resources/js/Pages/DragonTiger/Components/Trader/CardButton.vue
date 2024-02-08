<script setup>
import { ref, computed } from "vue";

const props = defineProps({
    cardName: {
        type: String,
        default: "0",
    },

    isSelected: {
        type: Boolean,
        default: false,
    },

    cardType: {
        type: String,
        default: "heart",
    },
});

const isHovering = ref(false);

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
        <img
            v-show="isSelected || isHovering"
            class="absolute inset-0 h-full w-full scale-110"
            :src="asset('dragon-tiger/images/card-active.svg')"
        />

        <div 
            class="relative overflow-hidden rounded-xl cursor-pointer"
            @mouseover="isHovering = true" 
            @mouseleave="isHovering = false"
        >
            <img
                class="h-full w-full"
                :src="asset('dragon-tiger/images/card.svg')"
            />

            <div
                class="absolute top-3 left-3 scale-150 text-2xl font-bold"
                :class="textColorClass"
            >
                {{ cardName }}
            </div>

            <img
                v-if="props.cardType !== 'default'"
                class="absolute bottom-2 right-2 h-8"
                :src="asset(suitImageUrl)"
            />
        </div>

        <img
            v-show="isSelected"
            :src="asset('dragon-tiger/images/check.svg')"
            class="absolute top-2 right-2 w-8"
        />
    </div>
</template>
