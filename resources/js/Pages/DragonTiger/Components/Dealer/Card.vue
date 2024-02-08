<script setup>
import { useGameStore } from "@/Stores/DragonTigers/Trader/GameStore";

const props = defineProps({
    side: String
});

const gameStore = useGameStore();
</script>

<template>
    <div class="flex flex-col h-full lg:h-[92%] w-2/5 items-center justify-end">
        <div
            class="flex flex-1 h-full w-full items-center justify-center overflow-hidden"
        >
            <img
                class="h-full"
                :src="asset(gameStore.getResultCardImage(side))"
                alt="card"
            />
        </div>

        <div
            class="flex h-20 text-3xl flex-none items-center justify-center font-rodfat underline"
        >
            {{ side }}
        </div>
    </div>
</template>

