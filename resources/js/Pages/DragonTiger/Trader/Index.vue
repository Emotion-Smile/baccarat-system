<script setup>
import {useGameStore} from '@/Stores/DragonTigers/Trader/GameStore';
import TraderLayout from '@/Pages/DragonTiger/TraderLayout';
import GameBar from '@/Pages/DragonTiger/Components/Trader/GameBar';
import StreamVideo from '@/Pages/DragonTiger/Components/Trader/StreamVideo';
import CurrentBetAmountStatistic from '@/Pages/DragonTiger/Components/Trader/CurrentBetAmountStatistic';
import NewGameButton from '@/Pages/DragonTiger/Components/Trader/NewGameButton';
import SubmitResultButton from '@/Pages/DragonTiger/Components/Trader/SubmitResultButton';
import CancelGameButton from '@/Pages/DragonTiger/Components/Trader/CancelGameButton';
import ResultPopupDialog from '@/Pages/DragonTiger/Components/Trader/ResultPopupDialog';
import { useDragonTigerEventListener } from '@/Composables/GameEventListener';
import { useUserStore } from '@/Stores/DragonTigers/UserStore';
import { useEventListener } from '@vueuse/core';
import { Inertia } from '@inertiajs/inertia';
import MatchResult from '@/Pages/DragonTiger/Components/MatchResult.vue';
import { onBeforeMount, ref, watch } from 'vue';

const gameStore = useGameStore();
const userStore = useUserStore();
const betSummaryInterval = ref(null);

Promise.all([
    userStore.fetchUserProfile(),
    gameStore.loadTable(),
    gameStore.loadGameState(),
    gameStore.loadGameResultMatrix(),
    gameStore.loadBetSummary(),
    userStore.loadScoreboardCount(),
]);

useDragonTigerEventListener('resultSubmitted', function ({payload}){
    gameStore.setGameState(payload);
    gameStore.loadGameState();
    reloadState(['scoreboard', 'scoreboardCount', 'betSummary'],
        gameStore.loadGameResultMatrix,
        userStore.loadScoreboardCount,
        gameStore.loadBetSummary
    );
    userStore.$patch({userBetRecords: []});
    clearPullingInterval();
});

useDragonTigerEventListener('created', function ({payload}){
    gameStore.setGameState(payload);
    gameStore.loadGameState();
});

useEventListener('visibilitychange', function (){
    if (!document.hidden) {
        Inertia.reload({only: ['gameState'],
            onSuccess: gameStore.loadGameState
        });
    }

    if (document.hidden) {
        gameStore.destroyInterval();
        clearPullingInterval();
    }
});

onBeforeMount(clearPullingInterval);

watch(()=>gameStore.getCountDown, function(newValue){
    verifyForReloadBetSummary(newValue);
});

function verifyForReloadBetSummary(newValue){
    if (newValue > 0 && betSummaryInterval.value === null) {
        // Start reloading immediately
        reloadBetSummary();

        // Set up an interval to reload every 4 seconds
        betSummaryInterval.value = setInterval(() => {
            reloadBetSummary();
        }, 4000);
    } else if (newValue <= 0) {
        clearPullingInterval();
    }
}

function reloadBetSummary() {
    Inertia.reload({
        only: ['betSummary'],
        onSuccess: gameStore.loadBetSummary
    });
}

function onUpdateResult(oldResult){
    gameStore.$patch({gameId: oldResult.id});
    gameStore.setResultPopupDialogForResubmitResult({ gameNumber: oldResult.gameNumber});
}

function clearPullingInterval(){
    clearInterval(betSummaryInterval.value);
    betSummaryInterval.value = null;
}

function reloadState(props, ...invokable){
    Inertia.reload({only: [...props], onSuccess: ()=>{
        invokable.forEach(function (fn){
            fn();
        });
    }});
}
</script>

<template>
    <trader-layout>
        <div class="flex items-stretch gap-3">
            <div class="w-[55%]">
                <div class="space-y-2">
                    <game-bar/>
                    <stream-video
                        :src="gameStore.liveStreamLink"
                    />
                </div>
            </div>

            <div class="flex w-[45%] flex-col gap-3">
                <current-bet-amount-statistic/>

                <div class="flex flex-1 items-stretch justify-between">
                    <div class="flex h-full w-2/5 flex-col items-end justify-center gap-6">
                        <template v-if="gameStore.isResultSubmitted">
                            <new-game-button/>
                        </template>
                        <template v-else>
                            <submit-result-button/>
                            <cancel-game-button/>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-3 overflow-hidden bg-[#161d1d] p-3">
            <div class="w-full">
                <match-result
                    object-key="gameNumber"
                    show-score-count
                    @update-result="onUpdateResult"
                />
            </div>
        </div>

        <result-popup-dialog/>
    </trader-layout>
</template>
