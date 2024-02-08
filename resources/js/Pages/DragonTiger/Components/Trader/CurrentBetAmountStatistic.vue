<script setup>
import { computed } from 'vue';
import CurrentBetAmountStatisticCard from '@/Pages/DragonTiger/Components/Trader/CurrentBetAmountStatisticCard';
import { useGameStore } from '@/Stores/DragonTigers/Trader/GameStore';

const gameStore = useGameStore();

const currentBetAmountStatisticCardData = computed(() => {
    const total = Object.values(gameStore.betSummary).reduce((total, item)=>total + item.value, 0);
    return Object.values(gameStore.betSummary).map((item, key) => {
        return {
            name: (() => {
                let name = 'tie';

                if(key === 0 || key === 5) {
                    name = 'red';
                }

                if(key === 1 || key === 6) {
                    name = 'black';
                }

                if(key === 2) {
                    name = 'dragon';
                }

                if(key === 4) {
                    name = 'tiger';
                }

                return name;
            }
            )(),

            cardType: (() => {
                let cardType = 'tiger';

                if(key < 3) {
                    cardType = 'dragon';
                }

                if(key === 3) {
                    cardType = 'tie';
                }

                return cardType;
            }
            )(),

            amount: item.label,

            percentage: (() => Math.round(100 * item.value / total))()
        };
    });
});
</script>

<template>
    <div class="h-72 flex-none bg-[#161D1D] px-6 pt-3">
        <div class="relative h-full">
            <div class="flex h-full flex-col justify-between gap-4 text-white">
                <div class="pt-3 text-center font-rodfat text-lg">
                    Current bet amount
                </div>

                <div class="grid flex-1 grid-cols-7 gap-1">
                    <current-bet-amount-statistic-card
                        v-for="(item, key) in currentBetAmountStatisticCardData"
                        :key="key"
                        :amount="item.amount"
                        :card-type="item.cardType"
                        :percentage="item.percentage"
                    >
                        <template v-if="key >= 2 && key <= 4">
                            {{ item.name }}
                        </template>

                        <template v-else>
                            <div class="w-full mx-auto flex items-center justify-center">
                                <div
                                    class="py-0.5 px-3 mx-1" :class="[ item.name === 'red' ? 'bg-dragon-red' : 'bg-black' ]"
                                >
                                    {{ item.name }}
                                </div>
                            </div>
                        </template>
                    </current-bet-amount-statistic-card>
                </div>
            </div>

            <div class="absolute inset-0 flex items-start justify-between">
                <img
                    class="w-24"
                    :src="asset('dragon-tiger/images/dragon.svg')"
                    alt="dragon"
                />

                <img
                    class="w-24"
                    :src="asset('dragon-tiger/images/tiger.svg')"
                    alt="tiger"
                />
            </div>
        </div>
    </div>
</template>
