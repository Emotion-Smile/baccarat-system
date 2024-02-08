<script setup>
import { ref } from "vue";
import {
    Listbox,
    ListboxLabel,
    ListboxButton,
    ListboxOptions,
    ListboxOption,
} from "@headlessui/vue";
import AppLayout from "@/Pages/DragonTiger/AppLayout.vue";

const filters = [
    {
        name: "all",
    },
    {
        name: "deposit",
    },
    {
        name: "withdraw",
    },
];
const selectFilter = ref(filters[0]);
</script>

<template>
    <app-layout>
        <div
            class="mx-auto flex h-[calc(100vh_-_7.5rem)] w-full flex-col overflow-hidden rounded bg-white 2xl:max-w-7xl"
        >
            <div class="flex flex-none items-center justify-between px-3 py-2">
                <div class="font-rodfat text-dragon-nav">
                    Deposit / Withdraw
                </div>
                <div>
                    <Listbox v-model="selectFilter">
                        <div class="relative mt-1">
                            <ListboxButton
                                class="relative w-32 cursor-pointer rounded-lg border border-dragon-primary bg-white py-1 pl-3 pr-10 text-left text-sm focus:outline-none focus-visible:border-indigo-500 focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75 focus-visible:ring-offset-2 focus-visible:ring-offset-orange-300"
                            >
                                <span class="block truncate capitalize">{{
                                    selectFilter.name
                                }}</span>
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
                                        v-slot="{ active, selected }"
                                        v-for="item in filters"
                                        :key="item.name"
                                        :value="item"
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
                                                    'block truncate capitalize',
                                                ]"
                                                >{{ item.name }}</span
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
            </div>
            <div class="relative overflow-auto">
                <div
                    class="flex flex-none flex-nowrap items-stretch text-xs uppercase text-white lg:flex-wrap"
                >
                    <div
                        class="w-32 flex-none bg-black py-2 text-center lg:w-[15%]"
                    >
                        Statement
                    </div>
                    <div
                        class="w-32 flex-none bg-black py-2 text-center lg:w-[10%]"
                    >
                        Time
                    </div>
                    <div
                        class="w-2/5 flex-none bg-black py-2 text-center lg:w-[20%]"
                    >
                        Before Balance
                    </div>
                    <div
                        class="w-32 flex-none bg-black py-2 text-center lg:w-[10%]"
                    >
                        Amount
                    </div>
                    <div
                        class="w-32 flex-none bg-black py-2 text-center lg:w-[10%]"
                    >
                        Current Balance
                    </div>
                    <div
                        class="w-1/2 flex-none bg-black py-2 text-center lg:w-full lg:flex-1"
                    >
                        Note
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<style scoped></style>
