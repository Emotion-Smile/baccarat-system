<script setup>
import {computed} from 'vue';
import SquaredBox from '@/Pages/DragonTiger/Components/Bet/SquaredBox.vue';
import {useGameStore} from '@/Stores/DragonTigers/GameStore';
const gameStore = useGameStore();

const tiger = computed(()=> gameStore.tiger);
const tigerColors = computed(()=> gameStore.tigerColors);
const notAllowedBet = computed(()=>gameStore.notAllowedBet);
const onBet=(betType)=>{
    gameStore.onSettleBet(betType);
};
</script>
<template>
    <div
        class="relative w-1/2 bg-cover bg-no-repeat"
        :style="[`background-image: url(${asset('dragon-tiger/images/Tiger.png')})`]"
    >
        <div class="flex h-full flex-col gap-1">
            <SquaredBox
                class="relative border-dragon-blue h-1/2 overflow-hidden border-2 bg-dragon-tiger/80 py-2 pl-12 cursor-pointer"
                :class="{
                    'border-yellow-300 shadow shadow-yellow-300': gameStore.checkMainResult('tiger'),
                    'disabled pointer-events-none': notAllowedBet,
                    'cursor-pointer': !notAllowedBet,
                }"
                @click="onBet(tiger.type)"
            >
                <template #title>
                    <div class="flex flex-col items-center">
                        <div class="font-rodfat text-base">
                            {{tiger?.title}}
                        </div>
                        <div class="text-lg">
                            1:1
                        </div>
                    </div>
                </template>
                <template #coin>
                    <div class="absolute right-0 bottom-0">
                        <div
                            class="relative flex h-14 w-14 items-center justify-center"
                        >
                            <template v-if="tiger?.totalBetAmount">
                                <img
                                    class="absolute inset-0 z-[0] w-full"
                                    :src="asset(`dragon-tiger/images/${gameStore.getChip(tiger.totalBetAmount)}.png`)"
                                    alt="bet"
                                >
                                <div class="font-saira z-[1] text-xs text-green-700">
                                    {{ tiger?.label }}
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
                <template #icon>
                    <img
                        v-if="gameStore.checkMainResult('tiger')"
                        class="absolute right-0 -top-2 h-8"
                        :src="asset('dragon-tiger/images/win.svg')"
                    >
                </template>
            </SquaredBox>
            <div class="flex flex-1 bg-dragon-tiger/80">
                <SquaredBox
                    v-for="(color, index) in tigerColors"
                    :key="index"
                    class="w-1/2 relative border-dragon-blue"
                    :class="{
                        'border-r': !gameStore.checkSubResult(`${color.betOn}_${color.betType}`) && index === 0,
                        'border-l': !gameStore.checkSubResult(`${color.betOn}_${color.betType}`) && index === 1,
                        'border-yellow-300 shadow shadow-yellow-300 border-r-2' : gameStore.checkSubResult(`${color.betOn}_${color.betType}`) && index===0,
                        'border-yellow-300 shadow shadow-yellow-300 border-l-2' : gameStore.checkSubResult(`${color.betOn}_${color.betType}`) && index===1,
                        'cursor-pointer': !notAllowedBet,
                        'disabled pointer-events-none': notAllowedBet,
                    }"
                    @click="onBet(color.type)"
                >
                    <template #small-box-title>
                        <div
                            :class="{'bg-black': color.title==='BLACK', 'bg-dragon-red': color.title==='RED'}"
                            class="mx-auto flex-row h-6 w-full items-center justify-center gap-1 px-1 py-0.5"
                        >
                            <div class="font-rodfat text-sm uppercase text-center">
                                {{ color.title }}
                            </div>
                            <div class="text-base  text-center mt-3">
                                1:0.9
                            </div>
                        </div>
                    </template>
                    <template #icon>
                        <img
                            v-if="gameStore.checkSubResult(`${color.betOn}_${color.betType}`)"
                            class="absolute right-0 -top-2 h-8"
                            :src="asset('dragon-tiger/images/win.svg')"
                        >
                    </template>
                    <template #coin>
                        <div class="absolute right-0 md:bottom-0">
                            <div
                                class="relative flex h-14 w-14 items-center justify-center"
                            >
                                <template v-if="color.totalBetAmount">
                                    <img
                                        class="absolute inset-0 z-0 w-full"
                                        :src="asset(`dragon-tiger/images/${gameStore.getChip(color.totalBetAmount)}.png`)"
                                        alt="bet"
                                    >
                                    <div class="font-saira z-[1] text-xs text-green-700">
                                        {{ color.label }}
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </SquaredBox>
            </div>
        </div>
    </div>
</template>
