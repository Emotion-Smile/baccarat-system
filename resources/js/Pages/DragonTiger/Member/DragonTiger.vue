<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import StreamVideo from '@/Pages/DragonTiger/Components/StreamVideo.vue';
import DragonTiger from '@/Pages/DragonTiger/Components/DragonTiger.vue';
import BetCondition from '@/Pages/DragonTiger/Components/BetCondition.vue';
import BetHistory from '@/Pages/DragonTiger/Components/BetHistory.vue';
import MatchResult from '@/Pages/DragonTiger/Components/MatchResult.vue';
import {useDragonTigerEventListener} from '@/Composables/GameEventListener';
import {useUserStore} from '@/Stores/DragonTigers/UserStore';
import {Inertia} from '@inertiajs/inertia';
import { useEventListener, useStorage } from '@vueuse/core';
import {useGameStore} from '@/Stores/DragonTigers/GameStore';
import Sound from '@/Components/Sound.vue';
const userStore = useUserStore();
const gameStore = useGameStore();
const startBetting = useStorage('startBetting', {isPlayed: false, gameNumber: '#'});
const stopBetting = useStorage('stopBetting', {isPlayed: false, gameNumber: '#'});

Promise.all([
    userStore.fetchUserProfile(),
    gameStore.loadTable(),
    gameStore.loadGameSlots(),
    gameStore.loadGameResultMatrix(),
    gameStore.gameCountdownEvent(),
    userStore.loadUserCoins(),
    userStore.loadUserBets(),
    userStore.loadScoreboardCount(),
    userStore.loadBetLimit(),
    gameStore.loadStreamTable()
]);

useDragonTigerEventListener('resultSubmitted', function ({payload}){
    if (payload.event === 'submit'){
        gameStore.$patch({game: payload});
        gameStore.gameCountdownEvent();
    }
    reloadState(['scoreboard', 'memberBetState'],
        gameStore.loadGameResultMatrix,
        gameStore.loadGameSlots
    );
    userStore.$patch({userBetRecords: []});
});

useDragonTigerEventListener('created', async function ({payload}){
    startBetting.value.gameNumber = payload.gameNumber;
    startBetting.value.isPlayed = false;
    stopBetting.value.gameNumber =  payload.gameNumber;
    stopBetting.value.isPlayed = false ;
    gameStore.$patch({game: payload, isStartBetting: true});
    gameStore.gameCountdownEvent();
});

gameStore.$subscribe(function (mutation, state){
    if (state.isBetSucceeded){
        reloadState(['outstandingTickets','memberBetState'],
            userStore.loadUserBets,
            gameStore.loadGameSlots,
        );
        state.isBetSucceeded = false;
    }
});

useEventListener('visibilitychange', function (){
    if (!document.hidden) {
        Inertia.reload({only: ['gameState'],
            onSuccess: gameStore.gameCountdownEvent
        });
    }
    if (document.hidden) {
        gameStore.destroyInterval();
    }
});


useEventListener('message', function({data:{eventName, isPlaying}}) {
    if (['musicEvent'].includes(eventName)){
        userStore.$patch({isMusicPlay: isPlaying});
    }

    if (['soundEvent'].includes(eventName)){
        userStore.$patch({isSoundPlay: isPlaying});
    }
}, false);

function reloadState(props, ...invokable){
    Inertia.reload({only: [...props], onSuccess: ()=>{
        invokable.forEach(function (fn){
            fn();
        });
    }});
}

</script>

<template>
    <app-layout>
        <template #sound>
            <Sound />
        </template>

        <div
            class="flex-1 space-y-3 md:flex md:space-x-3 md:space-y-0 md:overflow-hidden"
        >
            <div class="flex flex-col space-y-2 overflow-hidden md:flex-1">
                <stream-video />
                <div class="hidden space-y-2 md:block">
                    <match-result object-key="result" first-letter />
                </div>
                <div class="hidden space-y-2 md:block">
                    <match-result object-key="gameNumber" show-score-count/>
                </div>
            </div>
            <div
                class="flex w-full flex-col space-y-2 overflow-hidden md:max-w-sm 2xl:max-w-md 2xl:space-y-3 3xl:max-w-lg 4xl:max-w-screen-sm"
            >
                <dragon-tiger />
                <bet-condition />
                <bet-history />
            </div>
            <div class="flex-none space-y-2 md:hidden">
                <match-result object-key="result" first-letter />
            </div>
            <div class="flex-none space-y-2 md:hidden">
                <match-result object-key="gameNumber"  show-score-count />
            </div>
        </div>
    </app-layout>
</template>

<style scoped></style>
