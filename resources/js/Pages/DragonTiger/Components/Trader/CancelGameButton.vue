<script setup>
import { useGameStore } from '@/Stores/DragonTigers/Trader/GameStore';
import ConfirmDialogBox from '@/Pages/DragonTiger/Components/Bet/ConfirmDialogBox';
import GameButton from '@/Pages/DragonTiger/Components/Trader/GameButton';

const gameStore = useGameStore();
</script>

<template>
    <ConfirmDialogBox @confirm="gameStore.cancelGame()">
        <template #title>
            <span>Do you want to cancel game?</span>
        </template>

        <template #activator="{ activate }">
            <slot 
                name="button" 
                :activate="activate"
            >
                <game-button
                    class="bg-gray-400"
                    @click.prevent="activate"
                >
                    Cancel Game
                </game-button>
            </slot>
        </template>
    </ConfirmDialogBox>
</template>

