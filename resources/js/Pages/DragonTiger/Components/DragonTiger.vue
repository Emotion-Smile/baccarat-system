<script setup>
import BetDragon from '@/Pages/DragonTiger/Components/Bet/BetDragon.vue';
import BetTiger from '@/Pages/DragonTiger/Components/Bet/BetTiger.vue';
import {computed} from 'vue';
import CircularBox from '@/Pages/DragonTiger/Components/Bet/CircularBox.vue';
import {useGameStore} from '@/Stores/DragonTigers/GameStore';
const gameStore = useGameStore();

const tie = computed(() => gameStore.tie);
const totalBetAmount = computed(() => gameStore.tie?.totalBetAmount);
const notAllowedBet = computed(() => gameStore.notAllowedBet);
const gameNumber = computed(()=>gameStore.game.gameNumber);

const onBet = (betType) => {
    gameStore.onSettleBet(betType);
};

</script>

<template>
    <div>
        <div
            class="relative flex items-center justify-center rounded-md bg-black px-2 py-2 font-rodfat text-xl"
        >
            <div class="text-dragon-primary">{{gameStore.betStatus}}</div>
            <div class="absolute left-2 flex items-center gap-2 uppercase">
                <span class="text-gray-400">no:</span>
                <span class="text-dragon-primary">{{gameNumber}}</span>
            </div>
            <div class="absolute right-2 flex gap-3">
                <img
                    :src="asset('dragon-tiger/images/count-down.svg')"
                    alt="count down"
                />
                <span class="text-dragon-primary w-6 flex items-center justify-center">{{ gameStore.countdown }}</span>
            </div>
        </div>
        <div class="relative mt-3 h-48 text-white lg:h-52">
            <div class="relative z-0 flex h-full w-full items-stretch gap-1">
                <bet-dragon/>
                <bet-tiger/>
            </div>
            <div
                class="absolute left-[calc(50%-3.5rem)] right-[calc(50%-3.5rem)] top-0"
            >
                <CircularBox
                    class="border-dragon-green bg-dragon-tie ring-4 ring-dragon-bg"
                    :class="{
                        'border-yellow-300 shadow shadow-yellow-300': gameStore.checkMainResult('tie'),
                        'disabled pointer-events-none': notAllowedBet,
                        'cursor-pointer': !notAllowedBet,
                    }"
                    @click="onBet(tie?.type)"
                >
                    <template #default>
                        <div
                            class="mt-5 flex items-center justify-center gap-2"
                        >
                            <span class="font-rodfat text-lg">{{ tie?.title }}</span>
                            <span class="font-bold">8:1</span>
                        </div>
                        <img
                            v-if="gameStore.checkMainResult('tie')"
                            class="absolute right-0 h-8"
                            :src="asset('dragon-tiger/images/win.svg')"
                            alt="win"
                        >
                        <div
                            class="relative flex h-14 w-14 items-center justify-center"
                        >

                            <!--                                    <template v-if="tie?.coin">-->
                            <!--                                        <img-->
                            <!--                                            class="absolute inset-0 h-full w-full"-->
                            <!--                                            :src="asset(tie?.coin)"-->
                            <!--                                            alt="bet"-->
                            <!--                                        />-->
                            <!--                                    </template>-->
                            <template v-if="totalBetAmount">
                                <img
                                    class="absolute inset-0 h-full w-full"
                                    :src=" asset( 'dragon-tiger/images/default-bet.png')"
                                    alt="bet"
                                />
                                <div
                                    class="font-saira z-[1] text-xs text-green-700"
                                >
                                    {{ tie.label }}
                                </div>
                            </template>
                        </div>
                    </template>
                </CircularBox>
            </div>
        </div>
    </div>
</template>
