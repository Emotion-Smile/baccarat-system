<script setup>
import CancelGameButton from "@/Pages/DragonTiger/Components/Trader/CancelGameButton";
</script>

<template>
    <cancel-game-button>
        <template #button="{ activate }">
            <button
                @click.prevent="activate" 
                class="flex w-full items-center justify-center text-white bg-gray-400 h-16 font-bold rounded-lg border border-gray-500"
            >
                Cancel Game
            </button>
        </template>
    </cancel-game-button>
</template>

