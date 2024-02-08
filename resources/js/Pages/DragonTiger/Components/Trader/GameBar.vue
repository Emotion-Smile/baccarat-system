<script setup>
import { useGameStore } from '@/Stores/DragonTigers/Trader/GameStore';

const gameStore = useGameStore();
</script>

<template>
    <div class="flex items-center justify-between border border-black bg-dragon-fill py-1 px-3 font-rodfat text-lg">
        <div class="flex w-1/3 items-center gap-2 uppercase">
            <span class="text-gray-400">no:</span>
            <span class="text-dragon-primary">{{ gameStore.getGameNumber }}</span>
        </div>

        <div class="flex w-1/3 items-center justify-center text-dragon-primary">
            {{ gameStore.getBetStatus }}
        </div>

        <div class="flex w-1/3 items-center justify-end gap-3">
            <img
                :src="asset('dragon-tiger/images/count-down.svg')"
                alt="countdown"
            />

            <span class="text-dragon-primary">{{ gameStore.getCountDown }}</span>
        </div>
    </div>
</template>
