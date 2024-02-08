<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {ref, computed, watch} from 'vue';
import {
    Listbox,
    ListboxButton,
    ListboxOptions,
    ListboxOption,
} from '@headlessui/vue';
import {useUserStore} from '@/Stores/DragonTigers/UserStore';
import {Inertia} from '@inertiajs/inertia';

const filter = [
    { name: 'Today', value: 'today'},
    { name: 'Yesterday', value:  'yesterday'},
    { name: 'This Week', value: 'thisWeek'},
    { name: 'Last Week', value:  'lastWeek'},
];

const queryString = route().params.date??'today';
const userStore = useUserStore();
const getDefault = ()=> filter.find(item=>item.value === queryString );
const selectedFilter = ref(getDefault());

const getResultTextColorClass = (betValue) => {
    switch (betValue) {
    case 'win':
        return 'text-[#4099de]';
    case 'cancel':
        return 'text-gray-500';
    default:
        return 'text-[#e74444]';
    }
};

watch(selectedFilter, function (selectedValue){
    Inertia.visit(route(route().current(), {_query: {date: selectedValue.value}}), {only: ['tickets']});
});

userStore.fetchUserProfile();
userStore.loadBetHistories();

const betHistories = computed(()=> userStore.betHistories);
</script>

<template>
    <app-layout>
        <div
            class="mx-auto flex h-[calc(100vh_-_7.5rem)] w-full flex-col overflow-hidden rounded bg-white 2xl:max-w-7xl"
        >
            <div class="flex flex-none items-center justify-between px-3 py-2">
                <div class="font-rodfat text-dragon-nav">Betting History</div>
                <Listbox v-model="selectedFilter">
                    <div class="relative mt-1">
                        <ListboxButton
                            class="relative w-32 cursor-pointer rounded-lg border border-dragon-primary bg-white py-1 pl-3 pr-10 text-left text-sm focus:outline-none focus-visible:border-indigo-500 focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75 focus-visible:ring-offset-2 focus-visible:ring-offset-orange-300"
                        >
                            <span class="block truncate">{{ selectedFilter?.name }}</span>
                            <span
                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                    class="h-5 w-5 text-gray-400"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9"
                                    />
                                </svg>
                            </span>
                        </ListboxButton>

                        <transition
                            leave-active-class="transition duration-100 ease-in"
                            leave-from-class="opacity-100"
                            leave-to-class="opacity-0"
                        >
                            <ListboxOptions
                                class="absolute z-50 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
                            >
                                <ListboxOption
                                    v-for="person in filter"
                                    v-slot="{ active, selected }"
                                    :key="person.name"
                                    :value="person"
                                    as="template"
                                >
                                    <li
                                        :class="[
                                            active
                                                ? 'bg-dragon-nav text-white'
                                                : 'text-gray-900',
                                            'relative cursor-pointer select-none py-2 pl-10 pr-4 text-sm',
                                        ]"
                                    >
                                        <span
                                            :class="[
                                                selected
                                                    ? 'font-medium'
                                                    : 'font-normal',
                                                'block truncate',
                                            ]"
                                        >{{ person.name }}</span
                                        >
                                        <span
                                            v-if="selected"
                                            class="absolute inset-y-0 left-0 flex items-center pl-3 text-amber-600"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke-width="1.5"
                                                stroke="currentColor"
                                                class="h-5 w-5"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M4.5 12.75l6 6 9-13.5"
                                                />
                                            </svg>
                                        </span>
                                    </li>
                                </ListboxOption>
                            </ListboxOptions>
                        </transition>
                    </div>
                </Listbox>
            </div>
            <div class="relative overflow-auto">
                <div
                    class="flex flex-none flex-nowrap items-stretch text-sm uppercase text-white lg:flex-wrap"
                >
                    <div
                        class="w-32 flex-none bg-black py-2 text-center lg:w-[15%]"
                    >
                        Fight#
                    </div>
                    <div
                        class="w-32 flex-none bg-black py-2 text-center lg:w-[10%]"
                    >
                        Table
                    </div>
                    <div
                        class="w-2/5 flex-none bg-black py-2 text-center lg:w-[20%]"
                    >
                        Date
                    </div>
                    <div
                        class="w-32 flex-none bg-black py-2 text-center lg:w-[10%]"
                    >
                        Bet Amount
                    </div>
                    <div
                        class="w-32 flex-none bg-black py-2 text-center lg:w-[10%]"
                    >
                        Bet
                    </div>
                    <div
                        class="w-32 flex-none bg-black py-2 text-center lg:w-[15%]"
                    >
                        Result
                    </div>
                    <div
                        class="w-2/4 flex-none bg-black py-2 text-left lg:w-[20%]"
                    >
                        Win/Lose
                    </div>
                </div>
                <div
                    v-for="result in betHistories"
                    :key="result.id"
                    class="flex flex-none flex-nowrap items-stretch text-sm uppercase lg:flex-wrap"
                >
                    <div
                        class="w-32 flex-none border-b border-gray-200 py-2 text-center font-bold text-gray-400 lg:w-[15%]"
                    >
                        {{ result.gameNumber }}
                    </div>
                    <div
                        class="w-32 flex-none border-b border-gray-200 py-2 text-center lg:w-[10%]"
                    >
                        {{ result.table }}
                    </div>
                    <div
                        class="w-2/5 flex-none border-b border-gray-200 py-2 text-center lg:w-[20%]"
                    >
                        {{ result.dateTime }}
                    </div>
                    <div
                        class="w-32 flex-none border-b border-gray-200 py-2 text-center lg:w-[10%]"
                    >
                        {{ result.betAmount }}
                    </div>
                    <div
                        class="w-32 flex-none border-b border-gray-200 py-2 text-center font-bold lg:w-[10%]"
                    >
                        {{ result.betOn }}
                    </div>
                    <div
                        class="w-32 flex-none border-b border-gray-200 py-2 text-center font-bold lg:w-[15%]"
                        :class="getResultTextColorClass(result.ticketResult)"
                    >
                        {{ result.ticketResult }}
                    </div>
                    <div
                        class="w-2/4 flex-none border-b border-gray-200 py-2 text-left font-bold lg:w-[20%]"
                        :class="getResultTextColorClass(result.ticketResult)"
                    >
                        {{ result.winLose }}
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<style scoped></style>
