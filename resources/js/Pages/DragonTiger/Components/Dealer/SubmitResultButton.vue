<script setup>
import { useGameStore } from '@/Stores/DragonTigers/Trader/GameStore';
import ConfirmDialogBox from '@/Pages/DragonTiger/Components/Bet/ConfirmDialogBox';

const gameStore = useGameStore();
</script>

<template>
    <ConfirmDialogBox @confirm="gameStore.submitResult()">
        <template #title>
            <span>Are you sure to submit result?</span>
        </template>

        <template #activator="{ activate }">
            <button
                @click.prevent="activate" 
                class="flex w-full items-center justify-center text-white bg-[#0c34c0] h-16 font-bold rounded-lg border border-dragon-blue disabled:cursor-not-allowed"
                :disabled="gameStore.isDisabledSubmit"
            >
                Submit Result
            </button>
        </template>
    </ConfirmDialogBox>
</template>

