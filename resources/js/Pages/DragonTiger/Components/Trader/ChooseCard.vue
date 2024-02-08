<script setup>
import { ref } from "vue";
import { useGameStore } from "@/Stores/DragonTigers/Trader/GameStore"; 
import CardButton from "@/Pages/DragonTiger/Components/Trader/CardButton";
import CardSymbolButton from "@/Pages/DragonTiger/Components/Trader/CardSymbolButton";

const props = defineProps({
    name: String,
    icon: String,
    selected: Object
});

const gameStore = useGameStore(); 

const emit = defineEmits(['change']);

const selectedCard = ref(props.selected?.number);
const selectedCardSymbol = ref(props.selected?.symbol ?? "club");

const emitData = () => {
    emit("change", {
        number: selectedCard.value,
        symbol: selectedCardSymbol.value 
    });
};

const handleClickCard = (card) => {
    selectedCard.value = card.value;

    emitData();
};

const handleClickCardSymbol = (cardSymbol) => {
    selectedCardSymbol.value = cardSymbol;

    emitData();
};
</script>

<template>
    <div class="w-1/2">
        <div class="flex h-14 items-center justify-center gap-3 font-rodfat text-white">
            <img
                class="w-10"
                :src="icon"
                :alt="name"
            />

            <div class="text-xl">
                {{ name }}
            </div>
        </div>
        
        <div class="grid grid-cols-5 justify-center gap-1 self-center p-3">
            <div
                v-for="card in gameStore.getCards"
                :key="card.id"
                class="h-full"
                :class="{ 'col-start-2': card.value === 11 }"
            >
                <card-button
                    :cardType="selectedCardSymbol"
                    :cardName="card.text"
                    :isSelected="card.value === selectedCard"
                    @click.prevent="handleClickCard(card)"
                />
            </div>
        </div>

        <div class="flex h-14 items-stretch bg-dragon-fill p-1">
            <card-symbol-button
                v-for="(cardSymbol, key) in gameStore.getCardSymbols"
                :key="key"
                :cardType="cardSymbol"
                :isSelected="selectedCardSymbol === cardSymbol"
                @click.prevent="handleClickCardSymbol(cardSymbol)"
            />
        </div>
    </div>
</template>

