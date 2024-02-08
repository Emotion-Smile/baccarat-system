<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import {Inertia} from '@inertiajs/inertia';
import {reactive, ref} from "vue";

const props = defineProps({
    errors: Object
});

const message = ref('');

let form = reactive({
    telegram: null,
    issue: null
});

function submitFeedback() {
    Inertia.post(route('member.feedback.store'), {
        telegram: form.telegram,
        issue: form.issue
    }, {
        onSuccess: () => {
            form.telegram = null;
            form.issue = null;
            message.value = 'show';
        }
    });
}
</script>

<template>
    <app-layout title="Feedback">
        <template #header>
            <h2
                class="text-xl font-semibold capitalize leading-tight text-label"
            >
                {{ __("Feedback") }}
            </h2>
        </template>
        <div class="mx-auto max-w-7xl py-10 sm:px-6 md:w-110 lg:px-8">

            <form @submit.prevent="submitFeedback">

                <div
                    class="space-y-2 rounded-tl-md rounded-tr-md bg-fill px-4 py-5 shadow sm:p-6"
                >

                    <div>
                        <label
                            class="block text-sm font-medium text-label"
                            for="tel"
                        >
                            លេខទូរស័ព្ទតេឡេក្រាម / Telegram phone number <span class="text-red-500"> * </span>
                        </label>
                        <input
                            v-model="form.telegram"
                            type="text"
                            class="mt-1 block w-full rounded-md border-gray-300 bg-transparent text-label/50 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        />
                        <span class="text-sm text-red-500">{{ errors.telegram }}</span>
                    </div>
                    <div>
                        <label
                            class="block text-sm font-medium text-label"
                            for="problem"
                        >
                            បញ្ហា / Issue <span class="text-red-500"> * </span>
                        </label>
                        <textarea
                            v-model="form.issue"
                            type="text"
                            rows="5"
                            class="mt-1 block w-full rounded-md border-gray-300 bg-transparent text-label/50 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        />
                        <span class="text-sm text-red-500">{{ errors.issue }}</span>
                    </div>

                    <div v-if="message !== ''">
                        <span class="text-blue-900">
                        សូមអរគុណចំពោះមតិកែលម្អរបស់អ្នក។ <br/> Thank you for your feedback.
                            </span>
                        <hr class="mt-3"/>
                    </div>
                </div>
                <div
                    class="flex items-center justify-end rounded-br-md rounded-bl-md bg-fill/90 px-4 py-3 text-right shadow sm:px-6"
                >
                    <button
                        class="inline-flex items-center rounded-md border border-transparent bg-gray-800 px-4 py-2 text-xs font-semibold  tracking-widest text-white transition hover:bg-gray-700 focus:border-gray-900 focus:outline-none focus:ring focus:ring-gray-300 active:bg-gray-900 disabled:opacity-25"
                        type="submit"
                    >
                        បញ្ជូន / Submit
                    </button>


                </div>
            </form>
        </div>
    </app-layout>
</template>

<style scoped></style>
