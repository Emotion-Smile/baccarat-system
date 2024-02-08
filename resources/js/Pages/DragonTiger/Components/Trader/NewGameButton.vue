<script setup>
import { useGameStore } from '@/Stores/DragonTigers/Trader/GameStore';
import ConfirmDialogBox from '@/Pages/DragonTiger/Components/Bet/ConfirmDialogBox';
import GameButton from '@/Pages/DragonTiger/Components/Trader/GameButton';
import {  useStorage } from '@vueuse/core';
import { computed } from 'vue';

const gameStore = useGameStore();
const betIntervalInSecond =  computed({
    get() {
        return gameStore.betIntervalInSecond;
    },
    set(newValue) {
        const betIntervalInSecondStorage = useStorage('betIntervalInSecond', 60);
        if (betIntervalInSecondStorage.value){
            betIntervalInSecondStorage.value = +newValue;
        }
        gameStore.$patch({betIntervalInSecond: newValue});
    }
});

</script>

<template>
    <ConfirmDialogBox @confirm="gameStore.startNewGame()">
        <template #title>
            <span class="header">Do you want to start a new game?</span>

            <div class="flex gap-1 items-center justify-around">
                <label class="inline-flex items-center mt-3">
                    <input
                        v-model="gameStore.isNextRound"
                        type="checkbox"
                        class="form-checkbox h-5 w-5 text-gray-600 rounded"
                    >
                    <span class="ml-2 text-gray-700 text-sm">
                        Check for next round
                    </span>
                </label>
                <div class="flex items-center gap-2">
                    <label for="betIntervalInSecond" class="block text-gray-700 text-sm mt-3">Duration:</label>
                    <select v-model.number="betIntervalInSecond" id="betIntervalInSecond" name="location" class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        <option value="60">60 seconds</option>
                        <option value="50">50 seconds</option>
                        <option value="40">40 seconds</option>
                        <option value="30">30 seconds</option>
                        <option value="20">20 seconds</option>
                        <option value="15">15 seconds</option>
                    </select>
                </div>
            </div>
        </template>

        <template #activator="{ activate }">
            <slot 
                name="button" 
                :activate="activate"
            >
                <game-button
                    class="btn-new-round"
                    @click.prevent="activate"
                >
                    New Game
                </game-button>
            </slot>
        </template>
    </ConfirmDialogBox>
</template>

