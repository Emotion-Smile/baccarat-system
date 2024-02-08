<script setup>
import { useGameStore } from '@/Stores/DragonTigers/Trader/GameStore';
import ChooseCard from '@/Pages/DragonTiger/Components/Trader/ChooseCard';

const gameStore = useGameStore();

const handleDragonCardChange = (card) => {
    gameStore.setDragonCard(card);
};

const handleTigerCardChange = (card) => {
    gameStore.setTigerCard(card);
};
</script>

<template>
    <div class="flex gap-3 pb-10">
        <div class="flex-1 bg-dragon-fill">
            <div class="relative font-rodfat text-lg">
                <div class="py-2 text-center text-xl text-dragon-primary">
                    please {{ gameStore.getResultPopupDialog.titleCase }} the result!!
                </div>

                <div class="absolute left-0 top-0 bottom-0 flex h-full items-center gap-1 pl-3">
                    <span class="text-white">
                        No:
                    </span>

                    <span class="text-dragon-primary">
                        {{ gameStore.getResultPopupDialog.gameNumber }}
                    </span>
                </div>
            </div>

            <div class="flex items-stretch divide-x-2 divide-black bg-dragon-teal">
                <choose-card
                    class="w-1/2 flex-1"
                    name="Dragon"
                    :icon="asset('dragon-tiger/images/dragon-small.svg')"
                    :selected="gameStore.dragonCard"
                    @change="handleDragonCardChange"
                />

                <choose-card
                    class="w-1/2 flex-1"
                    name="Tiger"
                    :icon="asset('dragon-tiger/images/tiger-small.svg')"
                    :selected="gameStore.tigerCard"
                    @change="handleTigerCardChange"
                />
            </div>
        </div>

        <div class="w-40 flex-none">
            <slot></slot>
        </div>
    </div>
</template>

