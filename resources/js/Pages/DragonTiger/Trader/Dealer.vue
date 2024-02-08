<script setup>
import { ref } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { useUserStore } from "@/Stores/DragonTigers/UserStore";
import { useGameStore } from "@/Stores/DragonTigers/Trader/GameStore";
import { useEventListener } from "@vueuse/core";
import { useCardScannerEventListener } from "@/Composables/CardScannerEventListener";
import { useDragonTigerEventListener } from "@/Composables/GameEventListener";
import TraderLayout from "@/Pages/DragonTiger/TraderLayout";
import NewGameButton from "@/Pages/DragonTiger/Components/Dealer/NewGameButton";
import SubmitResultAndCreateNewGameButton from "@/Pages/DragonTiger/Components/Dealer/SubmitResultAndCreateNewGameButton";
import SubmitResultButton from "@/Pages/DragonTiger/Components/Dealer/SubmitResultButton";
import CancelGameButton from "@/Pages/DragonTiger/Components/Dealer/CancelGameButton";
import Card from "@/Pages/DragonTiger/Components/Dealer/Card";
import GameBar from "@/Pages/DragonTiger/Components/Dealer/GameBar";
import Profile from '@/Pages/DragonTiger/Components/Trader/Profile.vue';

const sides = ref(['tiger', 'dragon']);

const gameStore = useGameStore();
const userStore = useUserStore();

Promise.all([
    userStore.fetchUserProfile(),
    gameStore.loadTable(),
    gameStore.loadGameState(),
]);

useCardScannerEventListener(payload => {
    const side = payload.card;
    const value = { 
        number: payload.value, 
        symbol: payload.type 
    };

    gameStore.setResultCard({ side, value });
});

useDragonTigerEventListener("created", function ({ payload }) {
    gameStore.setGameState(payload);
    gameStore.loadGameState();
});

useEventListener("visibilitychange", function () {
    if (!document.hidden) {
        Inertia.reload({
            only: ["gameState"],
            onSuccess: gameStore.loadGameState,
        });
    }

    if (document.hidden) {
        gameStore.destroyInterval();
    }
});

function reloadState(props, ...invokable) {
    Inertia.reload({
        only: [...props],
        onSuccess: () => {
            invokable.forEach(function (fn) {
                fn();
            });
        },
    });
}
</script>

<template>
    <trader-layout>
        <template #navbar>
            <div
                class="flex shrink-0 items-center gap-x-6 border-b border-white/5 bg-dragon-nav px-4 shadow-sm h-12 sm:px-6 lg:px-8 lg:h-32"
            >
                <div
                    class="flex flex-1 items-center justify-end gap-x-4 self-stretch lg:gap-x-6"
                >
                    <game-bar />
                    <profile />
                </div>
            </div>
        </template>

        <div
            class="flex h-[calc(100vh_-_4.5rem)] items-stretch justify-between overflow-hidden text-white"
        >
            <Card 
                v-for="side in sides" 
                :key="side"
                :side="side"
            />

            <div class="flex w-1/5 flex-col gap-12 pt-12">
                <template v-if="gameStore.isResultSubmitted">
                    <new-game-button />
                </template>

                <template v-else>
                    <submit-result-and-create-new-game-button />

                    <submit-result-button />
                    <cancel-game-button />
                </template>
            </div>
        </div>
    </trader-layout>
</template>
