<template>
  <div class="flex-col flex-1  h-auto bg-white flex  md:max-h-44">
    <div class="flex justify-between w-full px-3 py-2 font-medium uppercase bg-gradient-to-b from-fill to-gray-300">
      <div>result</div>
      <div
        v-if="canBlockBalance"
        class="btn cursor-pointer text-red-900 "
        @click="blockBalance()"
      >
        Block Balance
      </div>
    </div>
    <div class="flex items-center justify-center flex-1 py-6">
      <div
        class="grid w-full grid-cols-3 gap-4 px-3 self-cener sm:grid-cols-5 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5"
      >
        <button
          type="button"
          :class="{ 'disabled:opacity-50' : !isLive }"
          :disabled="!isLive"
          class="btn-meron"
          @click="endTheMatch('meron win',1)"
        >
          meron win
        </button>
        <button
          type="button"
          :class="{ 'disabled:opacity-50' : !isLive }"
          :disabled="!isLive"
          class="btn-wala"
          @click="endTheMatch('wala win', 2)"
        >
          wala
          win
        </button>
        <button
          type="button"
          :class="{ 'disabled:opacity-50' : !isLive }"
          :disabled="!isLive"
          class="btn-draw"
          @click="endTheMatch('draw', 3)"
        >
          draw
        </button>
        <button
          type="button"
          :class="{ 'disabled:opacity-50' : !isLive }"
          :disabled="!isLive"
          class="btn-cancel"
          @click="endTheMatch('cancel', 4)"
        >
          cancel
        </button>
        <button
          type="button"
          :class="{ 'disabled:opacity-50' : !isLive }"
          :disabled="!isLive"
          class="btn-padding"
          @click="endTheMatch('pending', 5)"
        >
          pending
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import {confirmDialog} from '@/Functions/useHelper';
import {Inertia} from '@inertiajs/inertia';


export default {
    name: 'SubmitResult',
    props: {
        endTheMatch: Function,
        isLive: false,
        canBlockBalance: {
            type: Boolean,
            default: true
        }
    },
    setup() {

        async function blockBalance() {
            const result = await confirmDialog('Do you want to block member withdraw balance?');

            if (result.isConfirmed) {
                Inertia.post(route('match.block-member-withdraw-balance'));
            }
        }

        return {
            blockBalance
        };
    }
};
</script>

<style scoped>

</style>
