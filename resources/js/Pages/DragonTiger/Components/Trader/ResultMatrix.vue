<script setup>
import { ref } from "vue";
import { useGameStore } from "@/Stores/DragonTigers/Trader/GameStore";
import ResultMatrixButton from "@/Pages/DragonTiger/Components/Trader/ResultMatrixButton";

const gameStore = useGameStore();
</script>

<template>
    <div class="mt-3 overflow-hidden bg-[#161d1d] p-3">
        <div class="w-full">
            <div class="dragon-box h-full pt-[1px] pl-px">
                <result-matrix-button
                    v-for="(resultMatrix, index) in gameStore.getGameResultMatrix"
                    :key="index"
                    :result="resultMatrix.result"
                    :gameNumber="resultMatrix.gameNumber"
                    @click.prevent="gameStore.setResultPopupDialogForResubmitResult(resultMatrix)"
                />
            </div>
        </div>
    </div>
</template>
