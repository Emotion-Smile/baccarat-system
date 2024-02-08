<script setup>
import { computed } from 'vue';
import { useStorage } from '@vueuse/core';
import { useGameStore } from '@/Stores/DragonTigers/Trader/GameStore';
import ConfirmDialogBox from '@/Pages/DragonTiger/Components/Bet/ConfirmDialogBox';


const gameStore = useGameStore();

const autoCreateNewGameIntervalInSecond = computed({
    get() {
        return gameStore.autoCreateNewGameIntervalInSecond;
    },

    set(newValue) {
        const autoCreateNewGameIntervalInSecond = useStorage('autoCreateNewGameIntervalInSecond', 60);

        if (autoCreateNewGameIntervalInSecond.value){
            autoCreateNewGameIntervalInSecond.value = +newValue;
        }

        gameStore.$patch({ autoCreateNewGameIntervalInSecond: +newValue });
    }
});
</script>

<template>
    <ConfirmDialogBox @confirm="gameStore.submitResultAndCreateNewGame()">
        <template #title>
            <span>Are you sure to submit result and create new game?</span>
        </template>

        <template #activator="{ activate }">
            <div class="w-full">
                <div class="font-bold mb-2">
                    Auto Create New Game After
                </div>

                <select 
                    v-model.number="autoCreateNewGameIntervalInSecond"
                    class="w-full rounded-lg text-black py-1"
                >
                    <option
                        v-for="second in 12"
                        class="text-black" 
                        :value="second"
                        :key="second"
                    >
                        {{ `${second} second${second > 1 ? 's' : ''}` }}
                    </option>
                </select>
            </div>

             <button 
                class="flex w-full items-center justify-center text-black bg-[#f1c71e] h-16 font-bold rounded-lg border border-dragon-blue disabled:cursor-not-allowed"
                @click.prevent="activate" 
                :disabled="gameStore.isDisabledSubmit"
            >
                Submit and Create New Game
            </button>
        </template>
    </ConfirmDialogBox>
</template>

