<script setup>
import { useGameStore } from "@/Stores/DragonTigers/Trader/GameStore";
import Card from "@/Pages/DragonTiger/Components/Trader/Card";

const gameStore = useGameStore(); 
</script>

<template>
    <div class="space-y-3">
        <div class="text-center text-xl font-bold">
            Would you like to confirm this result?
        </div>

        <div class="mx-auto flex w-2/3 items-stretch">
            <div 
                v-for="(card, key) in gameStore.getResultCards"
                :key="key"
                class="flex w-1/2 flex-col"
            >
                <div 
                    class="text-center font-rodfat text-2xl"
                    :class="[key === 0 ? 'text-dragon-red' : 'text-dragon-blue']"
                >
                    {{ card.side }}
                </div>

                <div class="scale-75">
                    <card
                        :cardName="card.name"
                        :cardType="card.type"
                        class="scale-110"
                    />
                </div>
            </div>
        </div>
        
        <div class="flex items-center justify-center gap-3">
            <slot></slot>
        </div>
    </div>
</template>

