<template>
  <div class="flex flex-col h-full overflow-hidden rounded bg-table-body xl:flex-1">
    <div class="flex p-2 text-xs font-black text-white uppercase bg-black shrink-0">
      <div class="w-1/12">
        {{ __('fight') }}#
      </div>

      <div class="w-2/12 text-center">
        {{ __('bet') }}
      </div>
      <div class="w-3/12">
        {{ __('win_loss') }}
      </div>
      <div class="w-3/12 text-center">
        {{ __('time') }}
      </div>
      <div class="w-2/12 text-center">
        {{ __('status') }}
      </div>
      <div class="w-1/12">
        {{ __('ticket') }}
      </div>
    </div>
    <div class="flex-1 overflow-hidden">
      <div class="h-full px-2 overflow-y-scroll max-h-32 md:max-h-full">
        <div
          v-for="bet in betRecords.data"
          :key="bet.id"
          class="flex items-center p-2 text-xs font-black border-b border-gray-400 text-td 2xl:text-sm"
        >
          <div class="w-1/12">
            {{ bet.fight_number }}<br>
          </div>

          <div
            class="w-2/12 text-center capitalize"
            :class="['text-' + bet.bet_on]"
          >
            {{ bet.bet_on }}
          </div>
          <div
            class="w-3/12"
            :class="['text-ticket-'+bet.user_result]"
          >
            {{ bet.win_and_loss }}
          </div>
          <div class="w-3/12 text-center">
            {{ bet.time }}
          </div>
          <div
            :class="[bet.status === 'Pending' ? 'text-red-500' : '']"
            class="w-2/12 text-center"
          >
            {{ bet.status }}
          </div>
          <div class="w-1/12 cursor-pointer">
            <a
              :href="route('member.print', {id: bet.id})"
              target="_blank"
            > {{ __('print') }}</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>

import {onMounted} from 'vue';

const props = defineProps({betRecords: Object});

onMounted(() => {

});

function print() {
    window.print();
}

</script>

<style scoped>
.text-ticket-loss {
    color: darkred;
}

.text-ticket-win {
    color: darkblue;
}

.text-ticket-pending {
    color: darkgray;
}

.text-ticket-cancel {
    color: darkorange;
}

.text-ticket-draw {
    color: darkgreen;
}
</style>
