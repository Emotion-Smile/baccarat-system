<script setup>
import { useGameStore } from '@/Stores/DragonTigers/Trader/GameStore';

const gameStore = useGameStore();
</script>

<template>
    <div class="flex-1 flex items-center justify-center font-rodfat gap-16 text-xl lg:grap-28 lg:text-6xl">
        <div class="space-x-3">
            <span class="text-white">No :</span>
            <span class="text-[#f1c71e]">
                {{ gameStore.getGameNumber }}
            </span>
        </div>

        <div class="space-x-3">
            <span class="text-white">Table :</span>
            <span class="text-[#f1c71e]">
                {{ gameStore.getTableNumber }}
            </span>
        </div>

        <div class="space-x-3">
            <span class="text-white">Status :</span>
            <span class="text-[#f1c71e]">
                {{ gameStore.getBetStatus }}
            </span>
        </div>

        <div class="flex space-x-3">
            <img
                class="w-6 xl:w-12 lg:w-32"
                :src="asset('dragon-tiger/images/count-down.svg')"
                alt="countdown"
            />

            <span class="text-[#f1c71e]">
                {{ gameStore.getCountDown }}
            </span>
        </div>
    </div>
</template>
