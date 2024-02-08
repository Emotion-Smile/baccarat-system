<script setup>
import { useGameStore } from '@/Stores/DragonTigers/Trader/GameStore';
import { TransitionRoot, TransitionChild, Dialog, DialogPanel } from '@headlessui/vue';
import SelectResult from '@/Pages/DragonTiger/Components/Trader/SelectResult';
import ConfirmResult from '@/Pages/DragonTiger/Components/Trader/ConfirmResult';

const gameStore = useGameStore();

const close = () => {
    gameStore.resetDefaultCard();
    gameStore.setResultPopupDialogClose();
};
</script>

<template>
    <TransitionRoot
        appear
        as="template"
        :show="gameStore.isResultPopupDialogOpen"
    >
        <Dialog
            as="div"
            class="relative z-10"
        >
            <TransitionChild
                as="template"
                enter="duration-700 ease-out"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="duration-200 ease-in"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div class="fixed inset-0 bg-black/70" />
            </TransitionChild>

            <div class="fixed inset-0 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center">
                    <TransitionChild
                        as="template"
                        enter="duration-700 ease-out"
                        enter-from="opacity-0 scale-95"
                        enter-to="opacity-100 scale-100"
                        leave="duration-200 ease-in"
                        leave-from="opacity-100 scale-100"
                        leave-to="opacity-0 scale-95"
                    >
                        <DialogPanel
                            :class="[ gameStore.getOnConfirmResult ? 'max-w-md' : 'max-w-screen-3xl' ]"
                            class="w-full min-w-[20rem] transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all duration-500 ease-in-out"
                        >
                            <confirm-result v-if="gameStore.getOnConfirmResult">
                                <button
                                    type="button"
                                    class="btn-submit-result flex h-14 w-32 flex-col items-center justify-center rounded-md border-2 border-[#05234B] px-6 text-xl font-bold text-[#05234B]"
                                    @click.prevent="gameStore.getResultPopupDialog.action"
                                >
                                    YES
                                </button>

                                <button
                                    type="button"
                                    class="flex h-14 w-32 flex-col items-center justify-center rounded-md border-2 border-[#05234B] bg-[#a9bfbb] px-6 text-xl font-bold text-[#05234B]"
                                    @click.prevent="() => gameStore.setOnConfirmResult(false)"
                                >
                                    NO
                                </button>
                            </confirm-result>
                            <select-result v-else>
                                <button
                                    :disabled="gameStore.isDisabledSubmit"
                                    :class="{'pointer-events-none': gameStore.isDisabledSubmit}"
                                    type="button"
                                    class="btn-submit-result flex h-20 w-36 flex-col justify-center items-center rounded-md border-2 border-[#05234B] px-6 text-xl font-bold text-[#05234B] mb-2"
                                    @click.prevent="() => gameStore.setOnConfirmResult(true)"
                                >
                                    <span>{{ `${gameStore.getResultPopupDialog.titleCase}` }}</span>
                                </button>

                                <button
                                    type="button"
                                    class="bg-gray-400 flex h-20 w-36 flex-col justify-center items-center rounded-md border-2 border-[#05234B] px-6 text-xl font-bold text-[#05234B]"
                                    @click.prevent="close"
                                >
                                    <span>Cancel</span>
                                </button>
                            </select-result>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>
