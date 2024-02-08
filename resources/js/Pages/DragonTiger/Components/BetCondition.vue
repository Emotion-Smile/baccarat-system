<script setup>
import { computed, ref } from 'vue';
import InputGroupField from '@/Pages/DragonTiger/Components/Bet/InputGroupField.vue';
import button from '@/Jetstream/Button.vue';
import { useUserStore } from '@/Stores/DragonTigers/UserStore';
import { useGameStore } from '@/Stores/DragonTigers/GameStore';
import { useTranslate } from '@/Functions/useTranslate';

const refs = ref({});
const userStore = useUserStore();
const gameStore = useGameStore();
const getCoins = computed(() => userStore.coins);
const scrollContainer = ref(null);
const scrollPosition = ref(0);
const scrollStep = ref(200);
const {__} = useTranslate();

const dragonTiger = computed(() => userStore.betLimit?.dragon_tiger);
const tie = computed(() => userStore.betLimit?.tie);
const redBlack = computed(() => userStore.betLimit?.red_black);

const inputAttrs = computed(() => {
    return {
        class: 'font-saira block w-full h-full rounded-none rounded-l-lg border-2 border-black bg-dragon-teal font-rodfat text-white shadow-lg focus:border-win focus:ring-win sm:text-sm',
        type: 'number',
        id: 'betAmount',
    };
});
const buttonAttrs = computed(() => {
    return {
        class: 'text-md relative h-full flex w-24 items-center justify-center space-x-2 rounded-r-lg border-2 border-l-0 border-black bg-dragon-clear text-center text-sm font-black uppercase text-black hover:bg-green-600 focus:border-win focus:outline-none focus:ring-1 focus:ring-win sm:w-40 lg:w-24 xl:w-44',
        type: button,
    };
});

function onClear() {
    gameStore.$patch({ betAmount: 0 });
}

function scrollLeft() {
    if (scrollPosition.value <= -100){
        scrollPosition.value += scrollStep.value;
    }
}

function scrollRight() {
    if(scrollPosition.value >= -200){
        scrollPosition.value -= scrollStep.value;
    }
}

const handleClick = (ref) => {
    let amount = +gameStore.betAmount ?? 0;
    amount += getCoins.value.find((coin) => coin.ref === ref).amount;
    gameStore.$patch({ betAmount: amount });
};

</script>

<template>
    <div class="mt-5 rounded-md bg-white p-1 lg:p-1">
        <div class="flex h-9 items-center justify-between gap-1 xl:gap-3">
            <div
                class="flex flex-none items-center justify-center gap-1 xl:gap-3"
            >
                <input
                    id="auto"
                    v-model="gameStore.isAutoConfirm"
                    type="checkbox"
                    name="auto"
                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600"
                />
                <label for="auto" class="text-sm xl:text-base">{{__('auto_confirm')}}</label
                >
            </div>
            <div class="h-7 w-0.5 rounded bg-black xl:h-6"></div>
            <button
                class="h-9 w-20 rounded-lg border-2 border-black bg-dragon-clear text-sm font-bold uppercase text-black"
                @click.prevent="gameStore.gameRebetting"
            >
                {{__('rebet')}}
            </button>
            <div class="h-7 w-0.5 rounded bg-black xl:h-6"></div>
            <div class="flex-1">
                <InputGroupField
                    v-model.number="gameStore.betAmount"
                    :input-attrs="inputAttrs"
                    :button-attrs="buttonAttrs"
                    @click="onClear"
                />
            </div>
        </div>

        <div class="mt-3 flex flex-col items-stretch gap-1">
            <div class="flex flex-1 justify-center overflow-hidden">
                <!-- <div class="grid grid-cols-4 items-stretch">
                    <button
                        v-for="(coin, index) in getCoins"
                        :key="index"
                        type="button"
                        class="group relative w-full"
                        @click.prevent="handleClick(coin.ref)"
                    >
                        <img
                            :ref="(el) => (refs[coin.ref] = el)"
                            class="w-full transition-all duration-500 ease-in-out hover:cursor-pointer group-hover:scale-125"
                            :src="asset(coin.url)"
                            :alt="coin.name"
                        />
                        <span
                            class="font-saira absolute inset-0 flex items-center justify-center text-xs duration-500 ease-in-out group-hover:scale-125 xl:text-sm 4xl:text-lg"
                        >
                            {{ coin.amountText }}</span
                        >
                    </button>
                </div> -->
                <div
                    class="float-none flex flex-1 items-center justify-center px-3"
                >
                    <button
                        class="flex h-8 w-8 items-center justify-center rounded-full bg-dragon-teal"
                        @click.prevent="scrollLeft"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="currentColor"
                            class="h-5 w-5 text-white"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M7.72 12.53a.75.75 0 010-1.06l7.5-7.5a.75.75 0 111.06 1.06L9.31 12l6.97 6.97a.75.75 0 11-1.06 1.06l-7.5-7.5z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </button>
                </div>
                <div
                    class="relative flex h-24 items-center overflow-auto xl:h-28"
                >
                    <div  class="min-w-0 max-w-sm">
                        <div ref="scrollContainer" :style="{ transform: `translateX(${scrollPosition}px)` }" class="flex duration-500">
                            <button
                                v-for="(coin, index) in getCoins"
                                :key="index"
                                type="button"
                                class="group relative h-16 w-16 flex-none xl:h-24 xl:w-24"
                                @click.prevent="handleClick(coin.ref)"
                            >
                                <img
                                    :ref="(el) => (refs[coin.ref] = el)"
                                    class="w-full transition-all duration-500 ease-in-out hover:cursor-pointer group-hover:scale-125"
                                    :src="asset(coin.url)"
                                    :alt="coin.name"
                                />
                                <span
                                    class="font-saira absolute inset-0 flex items-center justify-center text-sm duration-500 ease-in-out group-hover:scale-125 xl:text-sm 4xl:text-lg"
                                >
                                    {{ coin.amountText }}</span
                                >
                            </button>
                        </div>
                    </div>
                </div>
                <div
                    class="float-none flex flex-1 items-center justify-center px-3"
                >
                    <button
                        class="flex h-8 w-8 items-center justify-center rounded-full bg-dragon-teal"
                        @click.prevent="scrollRight"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="currentColor"
                            class="h-5 w-5 text-white"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M16.28 11.47a.75.75 0 010 1.06l-7.5 7.5a.75.75 0 01-1.06-1.06L14.69 12 7.72 5.03a.75.75 0 011.06-1.06l7.5 7.5z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </button>
                </div>
            </div>
            <div
                class="flex w-full flex-none flex-col gap-0 rounded-lg border border-dragon-nav p-px xl:px-1 xl:py-2 2xl:gap-2 4xl:px-2"
            >
                <div
                    class="text-center font-medium uppercase lg:text-xs 2xl:text-base 4xl:text-lg"
                >
                    bet limits
                </div>
                <div class="flex divide-x divide-dragon-tie 2xl:gap-1">
                    <div class="flex-1 pr-px">
                        <div
                            class="text-xs font-semibold lg:text-[10px] xl:text-xs 2xl:text-sm 4xl:text-base"
                        >
                            Dragon & Tiger:
                        </div>
                        <div
                            class="text-[10px] xl:text-[11px] 2xl:text-xs 4xl:text-sm"
                        >
                            {{ dragonTiger }}
                        </div>
                    </div>

                    <div class="flex-1 px-1">
                        <div
                            class="text-xs font-semibold lg:text-[10px] xl:text-xs 2xl:text-sm 4xl:text-base"
                        >
                            Tie:
                        </div>
                        <div
                            class="text-[10px] xl:text-[11px] 2xl:text-xs 4xl:text-sm"
                        >
                            {{ tie }}
                        </div>
                    </div>

                    <div class="flex-1 pl-1">
                        <div
                            class="text-xs font-semibold lg:text-[10px] xl:text-xs 2xl:text-sm 4xl:text-base"
                        >
                            Red & Black:
                        </div>
                        <div
                            class="text-[10px] xl:text-[11px] 2xl:text-xs 4xl:text-sm"
                        >
                            {{ redBlack }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<!-- Tailwind CSS Styling -->
<style scoped>
.icon {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}
</style>
