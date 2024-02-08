<script setup>
import { ref } from 'vue';
import {
    Dialog,
    DialogPanel,
    TransitionChild,
    TransitionRoot,
} from '@headlessui/vue';
import {useUserStore} from '@/Stores/DragonTigers/UserStore';

const sidebarOpen = ref(false);
const userStore =useUserStore();
</script>

<template>
    <div
        class="h-full min-h-screen overflow-auto bg-dragon-bg subpixel-antialiased"
    >
        <TransitionRoot as="template" :show="sidebarOpen">
            <Dialog as="div" class="relative z-50" @close="sidebarOpen = false">
                <TransitionChild
                    as="template"
                    enter="transition-opacity ease-linear duration-300"
                    enter-from="opacity-0"
                    enter-to="opacity-100"
                    leave="transition-opacity ease-linear duration-300"
                    leave-from="opacity-100"
                    leave-to="opacity-0"
                >
                    <div class="fixed inset-0 bg-gray-900/80" />
                </TransitionChild>

                <div class="fixed inset-0 flex">
                    <TransitionChild
                        as="template"
                        enter="transition ease-in-out duration-300 transform"
                        enter-from="-translate-x-full"
                        enter-to="translate-x-0"
                        leave="transition ease-in-out duration-300 transform"
                        leave-from="translate-x-0"
                        leave-to="-translate-x-full"
                    >
                        <DialogPanel
                            class="relative mr-16 flex w-full max-w-xs flex-1"
                        >
                            <TransitionChild
                                as="template"
                                enter="ease-in-out duration-300"
                                enter-from="opacity-0"
                                enter-to="opacity-100"
                                leave="ease-in-out duration-300"
                                leave-from="opacity-100"
                                leave-to="opacity-0"
                            >
                                <div
                                    class="absolute left-full top-0 flex w-16 justify-center pt-5"
                                >
                                    <button
                                        type="button"
                                        class="-m-2.5 p-2.5"
                                        @click="sidebarOpen = false"
                                    >
                                        <span class="sr-only">Close sidebar</span
                                        >
                                    </button>
                                </div>
                            </TransitionChild>
                            <!-- Sidebar component, swap this element with another sidebar if you like -->
                            <div
                                class="flex grow flex-col gap-y-5 overflow-y-auto bg-gray-900 px-6 ring-1 ring-white/10"
                            >
                                <div class="flex h-16 shrink-0 items-center">
                                    <img
                                        class="h-8 w-auto"
                                        src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=500"
                                        alt="Your Company"
                                    />
                                </div>
                                <nav class="flex flex-1 flex-col">hi</nav>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </Dialog>
        </TransitionRoot>
        <div class="sticky top-0 z-50">
            <div
                class="sticky top-0 z-40 flex h-12 shrink-0 items-center gap-x-6 border-b border-white/5 bg-dragon-nav px-4 shadow-sm sm:px-6 lg:px-8"
            >
                <button
                    type="button"
                    class="-m-2.5 p-2.5 text-white"
                    @click="sidebarOpen = true"
                >
                    <span class="sr-only">Open sidebar</span>
                    <img :src="asset('dragon-tiger/images/bar.svg')" />
                </button>
                <div
                    class="flex flex-1 items-center justify-between gap-x-4 self-stretch lg:gap-x-6"
                >
                    <div>
                        <img
                            :src="asset('dragon-tiger/images/kv88.svg')"
                            alt="logo"
                        />
                    </div>
                    <div
                        class="relative flex h-10 items-center gap-3 border-2 border-dragon-balance-border bg-dragon-balance py-1 pr-1"
                    >
                        <div class="absolute -left-5 scale-125">
                            <img
                                :src="asset('dragon-tiger/images/coin.svg')"
                                alt="coin"
                            />
                        </div>
                        <div class="font-saira pl-6 text-white">
                            {{userStore.getBalance}}
                        </div>
                        <button
                            class="flex h-8 w-8 items-center justify-center rounded border-2 border-dragon-balance-button-border bg-dragon-balance-button"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="h-6 w-6 text-[#333333]"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"
                                />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <main class="overflow-hidden p-1 pb-16">
            <slot />
        </main>
        <footer class="fixed bottom-0 h-14 w-full bg-dragon-nav">
            <div class="flex h-full w-full items-stretch gap-3">
                <div class="">1</div>
                <div class="">2</div>
            </div>
        </footer>
    </div>
</template>

<style scoped></style>
