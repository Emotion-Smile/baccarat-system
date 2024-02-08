<script setup>

import AppLayout from '@/Layouts/AppLayout.vue';
import Button from '@/Jetstream/Button.vue';
</script>

<template>
    <app-layout>
        <div class="flex-1 md:flex md:space-x-3 md:space-y-0 md:overflow-hidden">
            <div class="flex flex-col space-y-2 overflow-hidden md:flex-1">
                <div class="relative">
                    <div class="flex">
                        <div
                            class="inline-flex items-center justify-center flex-1 h-10 space-x-2 bg-white cursor-pointer bg-channel-1 text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <span>table 1</span></div>
                        <div
                            class="inline-flex items-center justify-center flex-1 h-10 space-x-2 bg-white cursor-pointer text-black">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <span>table 2</span></div>
                    </div>
                    <div class="aspect-[16/9]">

                        <iframe class="h-full w-full"
                                src="https://www.youtube-nocookie.com/embed/1lhZxsMKfEw?si=tcMvgYRIcQlE6kbG&amp;start=6"
                                title="YouTube video player"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                allowfullscreen></iframe>
                    </div>
                </div>

            </div>
            <div
                class="flex w-full flex-col space-y-2 overflow-hidden md:max-w-sm 2xl:max-w-md 2xl:space-y-3 3xl:max-w-lg 4xl:max-w-screen-sm">
                <div class="space-y-2 py-2 bg-green-500 px-1">
                    <div class="relative flex items-center justify-center bg-black px-2 py-2 font-rodfat text-xl">
                        <div class="text-dragon-primary">close</div>
                        <div class="absolute left-2 flex items-center gap-2 uppercase">
                            <span class="text-gray-400">no:</span>
                            <span class="text-dragon-primary">1/1</span>
                        </div>
                        <div class="absolute right-2 flex gap-3">
                            <img
                                src="/dragon-tiger/images/count-down.svg"
                                alt="count down">
                            <span class="text-dragon-primary w-6 flex items-center justify-center">
                                0
                            </span>
                        </div>
                    </div>
                    <div class="grid grid-rows-2 grid-cols-10 grid-flow-col gap-2 items-stretch">
                        <div class="col-span-4 row-span-1 border border-green-500 rounded bg-green-800">
                            <div class="flex flex-col items-center justify-center py-2">
                                <h1 class="uppercase text-lg text-blue-500 font-bold">Player</h1>
                                <div class="text-white">1:1</div>
                            </div>
                        </div>
                        <div class="col-span-4 row-span-1 border border-green-500 rounded bg-green-800">
                            <div class="flex flex-col items-center justify-center py-2">
                                <h1 class="uppercase text-lg text-blue-500 font-bold">Player Pair</h1>
                                <div class="text-white">1:1</div>
                            </div>
                        </div>

                        <div class="col-span-2 row-span-2 border border-green-500 rounded bg-green-800">
                            <div class="flex flex-col items-center justify-center py-2 h-full">
                                <h1 class="uppercase text-lg text-green-400 font-bold">Tie</h1>
                                <div class="text-white">1:11</div>
                            </div>
                        </div>
                        <div class="border border-green-500 rounded col-span-4 bg-green-800">
                            <div class="flex flex-col items-center justify-center py-2">
                                <h1 class="uppercase text-lg text-red-500 font-bold">Banker</h1>
                                <div class="text-white">1:0.95</div>
                            </div>
                        </div>
                        <div class="border border-green-500 rounded col-span-4 bg-green-800">
                            <div class="flex flex-col items-center justify-center py-2">
                                <h1 class="uppercase text-lg text-red-500 font-bold">Banker Pair</h1>
                                <div class="text-white">1:11</div>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div class="border border-green-500 rounded bg-green-800">
                            <div class="flex flex-col items-center justify-center py-2">
                                <h1 class="uppercase text-lg text-yellow-400 font-bold">Big</h1>
                                <div class="text-white">1:0.54</div>
                            </div>
                        </div>
                        <div class="border border-green-500 rounded bg-green-800">
                            <div class="flex flex-col items-center justify-center py-2">
                                <h1 class="uppercase text-lg text-yellow-400 font-bold">Small</h1>
                                <div class="text-white">1:1.5</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-5 rounded-md bg-white p-1 lg:p-1">
                    <div class="flex h-9 items-center justify-between gap-1 xl:gap-3">
                        <div class="flex flex-none items-center justify-center gap-1 xl:gap-3">
                            <input
                                id="auto" type="checkbox" name="auto"
                                class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                            <label
                                for="auto"
                                class="text-sm xl:text-base">auto_confirm</label>
                        </div>
                        <div class="h-7 w-0.5 rounded bg-black xl:h-6"></div>
                        <button
                            class="h-9 w-20 rounded-lg border-2 border-black bg-dragon-clear text-xs font-bold uppercase text-black sm:text-sm">
                            Reset
                        </button>
                        <div class="h-7 w-0.5 rounded bg-black xl:h-6"></div>
                        <div class="flex-1">
                            <div class="shadow-shadow-lg flex h-9 rounded-md">
                                <div class="relative flex flex-grow items-stretch focus-within:z-10">
                                    <input type="text"
                                           class="font-saira block w-full h-full rounded-none rounded-l-lg border-2 border-black bg-dragon-teal font-rodfat text-white shadow-lg focus:border-win focus:ring-win sm:text-sm">
                                </div>
                                <button
                                    class="text-md relative h-full flex w-24 items-center justify-center space-x-2 rounded-r-lg border-2 border-l-0 border-black bg-dragon-clear text-center text-xs font-black uppercase text-black hover:bg-green-600 focus:border-win focus:outline-none focus:ring-1 focus:ring-win sm:w-40 lg:w-24 xl:w-44 sm:text-sm">
                                    Clear
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 flex flex-col items-stretch gap-1">
                        <div class="flex flex-1 justify-center overflow-hidden">
                            <div class="float-none flex flex-1 items-center justify-center px-3">
                                <button class="flex h-8 w-8 items-center justify-center rounded-full bg-dragon-teal">
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
                            <div class="relative flex h-24 items-center overflow-auto xl:h-28">
                                <div class="min-w-0 max-w-sm">
                                    <div class="flex duration-500">
                                        <button class="group relative h-16 w-16 flex-none xl:h-24 xl:w-24">
                                            <img
                                                class="w-full transition-all duration-500 ease-in-out hover:cursor-pointer group-hover:scale-125"
                                                src="/dragon-tiger/images/chip-1.png" alt="bet-0">
                                            <span
                                                class="font-saira absolute inset-0 flex items-center justify-center text-sm duration-500 ease-in-out group-hover:scale-125 xl:text-sm 4xl:text-lg">$ 1</span>
                                        </button>
                                        <button class="group relative h-16 w-16 flex-none xl:h-24 xl:w-24">
                                            <img
                                                class="w-full transition-all duration-500 ease-in-out hover:cursor-pointer group-hover:scale-125"
                                                src="/dragon-tiger/images/chip-2.png" alt="bet-0">
                                            <span
                                                class="font-saira absolute inset-0 flex items-center justify-center text-sm duration-500 ease-in-out group-hover:scale-125 xl:text-sm 4xl:text-lg">$ 1</span>
                                        </button>
                                        <button class="group relative h-16 w-16 flex-none xl:h-24 xl:w-24">
                                            <img
                                                class="w-full transition-all duration-500 ease-in-out hover:cursor-pointer group-hover:scale-125"
                                                src="/dragon-tiger/images/chip-3.png" alt="bet-0">
                                            <span
                                                class="font-saira absolute inset-0 flex items-center justify-center text-sm duration-500 ease-in-out group-hover:scale-125 xl:text-sm 4xl:text-lg">$ 1</span>
                                        </button>
                                        <button class="group relative h-16 w-16 flex-none xl:h-24 xl:w-24">
                                            <img
                                                class="w-full transition-all duration-500 ease-in-out hover:cursor-pointer group-hover:scale-125"
                                                src="/dragon-tiger/images/chip-4.png" alt="bet-0">
                                            <span
                                                class="font-saira absolute inset-0 flex items-center justify-center text-sm duration-500 ease-in-out group-hover:scale-125 xl:text-sm 4xl:text-lg">$ 1</span>
                                        </button>
                                        <button class="group relative h-16 w-16 flex-none xl:h-24 xl:w-24">
                                            <img
                                                class="w-full transition-all duration-500 ease-in-out hover:cursor-pointer group-hover:scale-125"
                                                src="/dragon-tiger/images/chip-5.png" alt="bet-0">
                                            <span
                                                class="font-saira absolute inset-0 flex items-center justify-center text-sm duration-500 ease-in-out group-hover:scale-125 xl:text-sm 4xl:text-lg">$ 1</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="float-none flex flex-1 items-center justify-center px-3"
                            >
                                <button
                                    class="flex h-8 w-8 items-center justify-center rounded-full bg-dragon-teal"
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
                    </div>
                    <div
                        class="flex w-full flex-none flex-col gap-0 rounded-lg border border-dragon-nav p-px xl:px-1 xl:py-2 2xl:gap-2 4xl:px-2">
                        <div class="text-center font-medium uppercase lg:text-xs 2xl:text-base 4xl:text-lg"> bet limits</div>
                        <div class="flex divide-x divide-dragon-tie 2xl:gap-1">
                            <div class="flex-1 pr-px">
                                <div
                                    class="text-xs font-semibold lg:text-[10px] xl:text-xs 2xl:text-sm 4xl:text-base"> Dragon
                                    Tiger:
                                </div>
                                <div class="text-[10px] xl:text-[11px] 2xl:text-xs 4xl:text-sm">តិចបំផុត: $1,
                                    ច្រើនបំផុត: $300
                                </div>
                            </div>
                            <div class="flex-1 px-1">
                                <div
                                    class="text-xs font-semibold lg:text-[10px] xl:text-xs 2xl:text-sm 4xl:text-base"> Tie:
                                </div>
                                <div class="text-[10px] xl:text-[11px] 2xl:text-xs 4xl:text-sm">តិចបំផុត: $1,
                                    ច្រើនបំផុត: $37
                                </div>
                            </div>
                            <div class="flex-1 pl-1">
                                <div
                                    class="text-xs font-semibold lg:text-[10px] xl:text-xs 2xl:text-sm 4xl:text-base"> Red Black:
                                </div>
                                <div class="text-[10px] xl:text-[11px] 2xl:text-xs 4xl:text-sm">តិចបំផុត: $2,
                                    ច្រើនបំផុត: $400
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>
