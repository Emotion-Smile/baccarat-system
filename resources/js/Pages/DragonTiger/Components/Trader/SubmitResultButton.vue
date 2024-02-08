<script setup>
import { useGameStore } from '@/Stores/DragonTigers/Trader/GameStore';
import GameButton from '@/Pages/DragonTiger/Components/Trader/GameButton';

const gameStore = useGameStore();
</script>

<template>
    <game-button
        class="btn-new-match"
        @click.prevent="gameStore.setResultPopupDialogForSubmitResult()"
    >
        Submit Result
    </game-button>
</template>

