<!-- This example requires Tailwind CSS v2.0+ -->
<template>
  <!-- Global notification live region, render this permanently at the end of the document -->
  <div
    aria-live="assertive"
    class="z-50 fixed inset-0 flex items-start px-4 py-6 pointer-events-none sm:p-6 sm:items-start"
  >
    <div class="w-full flex flex-col items-start space-y-4 sm:items-start">
      <!-- Notification panel, dynamically insert this into the live region when it needs to be displayed -->
      <transition
        enter-active-class="transform ease-out duration-300 transition"
        enter-from-class="opacity-0 translate-y-0 -translate-x-2"
        enter-to-class="opacity-100 -translate-x-0"
        leave-active-class="transition ease-in duration-100"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <div
          v-if="show"
          class="max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden"
        >
          <div class="p-4">
            <div class="flex items-start">
              <div class="shrink-0">
                <ExclamationCircleIcon
                  v-if="data.type === 'error'"
                  class="h-6 w-6 text-red-500"
                  aria-hidden="true"
                />
                <CheckCircleIcon
                  v-if="data.type === 'success'"
                  class="h-6 w-6 text-green-400"
                  aria-hidden="true"
                />
              </div>
              <div class="ml-3 w-0 flex-1 pt-0.5">
                <p class="text-sm font-medium text-gray-900">
                  {{ data.title }}
                </p>
                <p class="mt-1 text-sm text-gray-500">
                  {{ data.message }}
                </p>
              </div>
              <div class="ml-4 shrink-0 flex">
                <button
                  class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                  @click="show = false"
                >
                  <span class="sr-only">Close</span>
                  <XIcon
                    class="h-5 w-5"
                    aria-hidden="true"
                  />
                </button>
              </div>
            </div>
          </div>
        </div>
      </transition>
    </div>
  </div>
</template>

<script>

import {ref, watch} from 'vue';
import {CheckCircleIcon, ExclamationCircleIcon} from '@heroicons/vue/outline';
import {XIcon} from '@heroicons/vue/solid';

export default {
    // eslint-disable-next-line vue/multi-word-component-names
    name: 'Toast',
    components: {
        CheckCircleIcon,
        ExclamationCircleIcon,
        XIcon,
    },
    props: {
        data: Object
    },
    setup(props) {

        let show = ref(false);
        let timeout = null;

        watch(() => props.data, (current) => {
            if (current.toast) {
                show.value = true;

                if (timeout) {
                    clearTimeout(timeout);
                }

                timeout = setTimeout(() => show.value = false, 2000);
            }
        }, {deep: true});

        return {
            show,
        };
    },
};
</script>
