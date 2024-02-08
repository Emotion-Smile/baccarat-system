<script setup>
import { ref } from "vue";
import { useGameStore } from "@/Stores/DragonTigers/Trader/GameStore"; 
import ResultHistoryItem from "@/Pages/DragonTiger/Components/Trader/ResultHistoryItem";

const gameStore = useGameStore();
</script>

<template>
    <div class="flex h-full flex-col justify-between bg-black px-3 pb-5">
        <div class="flex items-center py-3 font-bold uppercase text-white">
            <div class="w-1/2 text-lg">
                Result History
            </div>

            <div class="flex w-1/2 items-center justify-around font-rodfat">
                <div class="w-16 border border-gray-500 bg-dragon-red py-0.5 text-center">
                    red
                </div>

                <div class="w-16 border border-gray-500 bg-black py-0.5 text-center">
                    black
                </div>
            </div>
        </div>

        <div class="space-y-1">
            <result-history-item
                v-for="(resultHistory, key) in gameStore.getResultHistories"
                :key="key"
                :type="resultHistory.type"
                :title="resultHistory.title"
                :grandTotal="resultHistory.grandTotal" 
                :totalRed="resultHistory.totalRed" 
                :totalBlack="resultHistory.totalBlack"
            />
        </div>
    </div>
</template>

