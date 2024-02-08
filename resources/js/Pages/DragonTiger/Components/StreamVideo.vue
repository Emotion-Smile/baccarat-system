<script setup>
import ResultCard from '@/Pages/DragonTiger/Components/FormKits/ResultCard.vue';
import {useGameStore} from '@/Stores/DragonTigers/GameStore';
import { ref} from 'vue';
import { Inertia } from '@inertiajs/inertia';

const gameStore = useGameStore();
const isShowed = ref(false);
gameStore.$subscribe(function (mutation) {
    const game = mutation.payload?.game;
    if (game?.mainResult.length) {
        isShowed.value = true;
        setTimeout(() => isShowed.value = false, 5000);
    }
});

function  switchStreamChanel (streamTable){
    Inertia.put(route('dragon-tiger.switch-table'), {
        tableId: streamTable.id
    }, {
        preserveState: false,
        preserveScroll: true,
    });
}

</script>

<template>
    <div class=" relative bg-black">
        <div class="relative w-full">
            <div class="flex divide-x">
                <div
                    v-for="streamTable in  gameStore.streamTables"
                    class="inline-flex items-center justify-center flex-1 h-10 space-x-2 bg-white cursor-pointer"
                    :class="{'bg-channel-1 text-white' : streamTable.id===gameStore.table.id}"
                    @click.prevent="switchStreamChanel(streamTable)"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-6 h-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                        />
                    </svg>
                    <span>{{streamTable.label}}</span>
                </div>
            </div>
            <div class="aspect-w-16 aspect-h-9 relative">
                <iframe
                    class="inset-0"
                    :src="gameStore.liveStreamLink"
                    title="YouTube video player"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                    allowfullscreen
                ></iframe>
                <div v-if="gameStore.isResultSubmitted && isShowed" class="inset-0 flex items-stretch">
                    <ResultCard
                        class="-translate-x-[50%] 2xl:-translate-x-[70%]"
                    >
                        <img
                            v-if="gameStore.checkMainResult('dragon') || gameStore.checkMainResult('tie')"

                            class="absolute -right-5 -top-5 h-8"
                            :src="asset('dragon-tiger/images/win.svg')"
                            alt="dragon win"
                        >
                        <div
                            class="flex h-6 w-full items-center justify-center rounded-lg border-2 border-dragon-red bg-dragon-dragon pt-0.5 font-rodfat text-xs leading-none text-white 2xl:h-8 2xl:text-lg"
                        >
                            dragon
                        </div>
                        <div class="h-auto w-full overflow-hidden p-1">
                            <img
                                class="h-full w-full"
                                :src="asset(gameStore.dragonResultCard)"
                                alt=""
                            />
                        </div>
                    </ResultCard>

                    <ResultCard
                        class="translate-x-[50%] 2xl:translate-x-[70%]"
                    >
                        <img
                            v-if="gameStore.checkMainResult('tiger') || gameStore.checkMainResult('tie')"
                            class="absolute -left-5 -top-5 0 h-8"
                            :src="asset('dragon-tiger/images/crown-left.svg')"
                            alt="tiger win"
                        >
                        <div
                            class="flex h-6 w-full items-center justify-center rounded-lg border-2 border-dragon-blue bg-dragon-tiger pt-0.5 font-rodfat text-xs leading-none text-white 2xl:h-8 2xl:text-lg"
                        >
                            tiger
                        </div>

                        <div class="h-auto w-full overflow-hidden p-1">
                            <img
                                class="h-full w-full"
                                :src="asset(gameStore.tigerResultCard)"
                                alt=""
                            />
                        </div>
                    </ResultCard>
                </div>
            </div>
        </div>
    </div>
</template>
