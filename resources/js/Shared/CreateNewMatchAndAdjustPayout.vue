<template>
  <div
    class="p-1 space-y-4 font-black uppercase md:p-2 lg:pt-0 2xl:p-3 lg:space-y-2 xl:space-y-3 2xl:space-y-6"
  >
    <div class="grid grid-cols-3 gap-1 md:grid-cols-2 md:gap-3 lg:grid-cols-3">
      <div class="">
        <select
          id="total-payout"
          class="flex-1 block w-full py-2 pl-3 pr-10 text-base text-black border-gray-300 rounded-md focus:outline-none"
          @change="event => $emit('update:total-payout',event.target.value)"
        >
          <option value="200">
            200
          </option>
          <option value="194">
            194
          </option>
          <option value="192">
            192
          </option>
          <option value="190">
            190
          </option>
          <option value="188">
            188
          </option>
          <option value="186">
            186
          </option>
          <option
            selected
            value="184"
          >
            184
          </option>
          <option value="182">
            182
          </option>
          <option
            value="180"
          >
            180
          </option>
          <option value="170">
            170
          </option>
          <option value="160">
            160
          </option>
        </select>
      </div>
      <div>
        <input
          type="number"
          name="payout"
          :value="payout"
          required
          class="flex-1 block w-full py-2 border-gray-300 rounded-md shadow-lg focus:ring-0"
          placeholder="Enter meron payout"
          @change="event => $emit('update:payout',event.target.value)"
        >
      </div>

      <div class="flex-1 md:col-span-2 lg:col-span-1">
        <button
          type="button"
          class=" hover:backdrop-blur inline-flex items-center justify-center w-full px-4 py-2 space-x-3 text-sm font-medium text-white rounded-full shadow-lg bg-gradient-to-b from-win to-green-600"
          @click="submitNewMatch"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="w-5 h-5 text-white"
            viewBox="0 0 20 20"
            fill="currentColor"
          >
            <path
              fill-rule="evenodd"
              d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"
              clip-rule="evenodd"
            />
          </svg>
          <span> {{ !matchIsLive ? 'Start New Match' : 'Adjust Payout' }}</span>
        </button>
      </div>
    </div>
    <span
      v-if="errors.meronPayout"
      class="text-white text-xs lowercase"
    >{{
      errors.meronPayout
    }}</span>
    <div class="grid grid-cols-4 gap-4 md:gap-3 lg:gap-1 lg:grid-cols-6 lg:gap-y-2 xl:gap-4">
      <button
        v-for="(payoutValue,index) in payoutIncreaseAndDecreaseValue"
        :key="index"
        type="button"
        class="btn-odds"
        @click="adjustPayout(index)"
      >
        {{ payoutValue.value }}
      </button>
    </div>
  </div>
</template>

<script>

export default {
    name: 'CreateNewMatchAndAdjustPayout',
    props: {
        errors: Object,
        matchIsLive: Boolean,
        payoutIncreaseAndDecreaseValue: Array,
        submitNewMatch: Function,
        totalPayout: Number,
        payout: Number
    },
    setup(props, {emit}) {

        const adjustPayout = (index) => {

            let adjust = props.payoutIncreaseAndDecreaseValue[index];
            let payout = props.payout;

            if (adjust.action === 'plus') {
                payout = parseInt(payout) + parseInt(adjust.key);
            } else {
                payout = parseInt(payout) - parseInt(adjust.key);
            }

            emit('update:payout', payout);
        };

        return {
            adjustPayout,
        };
    }
};
</script>

<style scoped>

</style>
