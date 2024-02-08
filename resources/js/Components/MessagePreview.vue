<template>
    <TransitionRoot appear :show="modelValue" as="template">
        <Dialog as="div" @close="closeModal" class="relative z-50">
            <TransitionChild
                as="template"
                enter="duration-300 ease-out"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="duration-200 ease-in"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div class="fixed inset-0 bg-black/75" />
            </TransitionChild>

            <div class="fixed inset-0 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center">
                    <TransitionChild
                        as="template"
                        enter="duration-300 ease-out"
                        enter-from="opacity-0 scale-95"
                        enter-to="opacity-100 scale-100"
                        leave="duration-200 ease-in"
                        leave-from="opacity-100 scale-100"
                        leave-to="opacity-0 scale-95"
                    >
                        <DialogPanel
                            class="w-full max-w-md transform overflow-hidden space-y-4 rounded bg-white p-6 text-left align-middle shadow-xl transition-all"
                        >
                            <div class="flex items-center justify-between">
                                <img class="w-10 h-10 mr-3" :src="asset('images/icons/announcement-circle.svg')" alt="announcement">
                                <span class="mr-auto uppercase font-bold">{{ __('message') }}</span>
                                <button
                                    type="button"
                                    class="inline-flex justify-center rounded-md translate-x-3 -translate-y-3 border border-transparent bg-transparent p-2 text-sm font-medium"
                                    @click="closeModal"
                                >
                                    <XIcon class="h-5 w-5" aria-hidden="true" />
                                </button>
                            </div>
                            <div>
                                <p class="text-sm text-black mb-4" v-html="content"></p>
                                <small class="text-gray-500">{{ timeHumans }}</small>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script setup>
import { 
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
    DialogTitle, 
} from '@headlessui/vue';
import { XIcon } from '@heroicons/vue/outline';

const props = defineProps({
    modelValue: Boolean,
    content: String,
    timeHumans: String
});

const emit = defineEmits(['update:modelValue']);

const updateValue = (value) => {
    emit('update:modelValue', value);
}

const closeModal = () => {
    updateValue(false);
}
</script>