<script setup>
import NewGameButton from "@/Pages/DragonTiger/Components/Trader/NewGameButton";
</script>

<template>
    <new-game-button>
        <template #button="{ activate }">
            <button 
                class="flex w-full items-center justify-center text-white bg-[#9335c7] h-16 font-bold rounded-lg border border-dragon-blue"
                @click.prevent="activate"
            >
                Create New Game
            </button>
        </template>
    </new-game-button>
</template>

