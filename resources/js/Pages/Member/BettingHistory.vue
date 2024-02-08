<template>
  <app-layout title="Betting History">
    <div
      class="flex-1 w-full mx-auto p-4 rounded bg-gray-50 xl:max-w-7xl"
    >
      <div class="flex items-center justify-between mb-4 flex-col sm:flex-row space-y-3 sm:space-y-0">
        <h1 class="text-2xl text-wala">
          {{ __('betting_history') }}
        </h1>

        <div class="flex flex-col space-y-3 lg:flex-row-reverse sm:items-end">
          <!--                    <div class="">-->
          <!--                        <label for="status" class="block text-sm font-medium text-gray-700">Win/Lose</label>-->
          <!--                        <select-->
          <!--                            id="status"-->
          <!--                            v-model="params.status"-->
          <!--                            class="block w-full py-2 pl-3 pr-10 mt-1 text-base border-gray-300 rounded-md sm:w-36 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"-->
          <!--                        >-->
          <!--                            <option value="">All</option>-->
          <!--                            <option-->
          <!--                                v-for="(item, key) in betTypeFilters"-->
          <!--                                :key="key"-->
          <!--                                :value="key"-->
          <!--                            >{{ item }}-->
          <!--                            </option>-->
          <!--                        </select>-->
          <!--                    </div>-->

          <div class="relative z-0 inline-flex shadow-sm rounded-md ml-auto mr-3 self-end h-10">
            <Link
              href="/member/betting?date=today"
              :only="['betHistoryRecords']"
              preserve-state
              preserve-scroll
              replace
              :class="{ 'text-red-500': $page.url.includes('date=today') }"
              class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-xs lg:text-base text-gray-700"
            >
              {{ __('today') }}
            </Link>

            <Link
              href="/member/betting?date=yesterday"
              :only="['betHistoryRecords']"
              preserve-state
              preserve-scroll
              replace
              :class="{ 'text-red-500': $page.url.includes('date=yesterday') }"
              class="-ml-px relative inline-flex items-center px-2 py-2 border border-gray-300 bg-white text-xs lg:text-base text-gray-700"
            >
              {{ __('yesterday') }}
            </Link>

            <Link
              href="/member/betting?date=this-week"
              :only="['betHistoryRecords']"
              preserve-state
              preserve-scroll
              replace
              :class="{ 'text-red-500': $page.url.includes('date=this-week') }"
              class="-ml-px relative inline-flex items-center px-2 py-2 border border-gray-300 bg-white text-xs lg:text-base text-gray-700"
            >
              {{ __('this_week') }}
            </Link>

            <Link
              href="/member/betting?date=last-week"
              :only="['betHistoryRecords']"
              preserve-state
              preserve-scroll
              replace
              :class="{ 'text-red-500': $page.url.includes('date=last-week') }"
              class="-ml-px relative inline-flex items-center px-2 py-2  border rounded-r-md  border-gray-300 bg-white text-xs lg:text-base text-gray-700"
            >
              {{ __('last_week') }}
            </Link>
            <!--                        <Link href="/member/betting?date=all" :only="['betHistoryRecords']"-->
            <!--                              preserve-state preserve-scroll-->
            <!--                              :class="{ 'text-red-500': $page.url.includes('date=all') }"-->
            <!--                              class="-ml-px relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-xs lg:text-base text-gray-700"-->
            <!--                        >-->
            <!--                            All-->
            <!--                        </Link>-->
          </div>
        </div>
      </div>
      <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
          <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
            <div class="overflow-hidden border-b border-gray-200">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-black">
                  <tr>
                    <th
                      scope="col"
                      class="px-6 py-2 text-sm font-medium tracking-wider text-center text-white uppercase"
                    >
                      {{ __('fight') }}#
                    </th>
                    <th
                      scope="col"
                      class="px-6 py-2 text-sm font-medium tracking-wider text-center text-white uppercase"
                    >
                      {{ __('table') }}
                    </th>
                    <th
                      scope="col"
                      class="px-6 py-2 text-sm font-medium tracking-wider text-center text-white uppercase"
                    >
                      {{ __('bet_date') }}
                    </th>
                    <!--                                    <th scope="col"-->
                    <!--                                        class="px-6 py-2 text-xs font-medium tracking-wider text-center text-white uppercase">-->
                    <!--                                        BEFORE BET BALANCE-->
                    <!--                                    </th>-->
                    <th
                      scope="col"
                      class="px-6 py-2 text-sm font-medium tracking-wider text-center text-white uppercase"
                    >
                      {{ __('bet_amount') }}
                    </th>

                    <!--                                    <th scope="col"-->
                    <!--                                        class="px-6 py-2 text-xs font-medium tracking-wider text-center text-white uppercase">-->
                    <!--                                        AFTER BET BALANCE-->
                    <!--                                    </th>-->
                    <th
                      scope="col"
                      class="px-6 py-2 text-sm font-medium tracking-wider text-center text-white uppercase"
                    >
                      {{ __('bet') }}
                    </th>
                    <th
                      scope="col"
                      class="px-6 py-2 text-sm font-medium tracking-wider text-left text-white uppercase"
                    >
                      {{ __('result') }}
                    </th>
                    <th
                      scope="col"
                      class="px-6 py-2 text-sm font-medium tracking-wider text-left text-white uppercase"
                    >
                      {{ __('win_loss') }}
                    </th>
                    <!--                                    <th scope="col"-->
                    <!--                                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-white uppercase">-->
                    <!--                                        BALANCE-->
                    <!--                                    </th>-->
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <template
                    v-for="item in betHistoryRecords.data"
                    :key="item.id"
                  >
                    <!--                                    <tr v-if="item.header" :class="{ 'bg-gray-100':item.header}">-->
                    <!--                                        <td class="px-6 py-2.5 text-base font-medium text-center text-gray-900 whitespace-nowrap border-b border-gray-300">-->
                    <!--                                            {{ item.fight }}-->
                    <!--                                        </td>-->
                    <!--                                        <td class="px-6 py-2.5 text-base font-medium text-center whitespace-nowrap border-b border-gray-300">-->
                    <!--                                            {{ item.date }}-->
                    <!--                                            &lt;!&ndash;                                            {{ item.time }}&ndash;&gt;-->
                    <!--                                        </td>-->
                    <!--                                        <td class="px-6 py-2.5 text-base text-center whitespace-nowrap font-medium text-green-700 border-b border-gray-300">-->
                    <!--                                            &lt;!&ndash;                                            {{ item.before_balance }}&ndash;&gt;-->
                    <!--                                            {{ item.before_bet_balance }}-->
                    <!--                                        </td>-->

                    <!--                                        <td class="px-6 py-2.5 text-base text-center whitespace-nowrap font-medium border-b border-gray-300">-->
                    <!--                                            &lt;!&ndash;                                            {{ item.amount }}&ndash;&gt;-->
                    <!--                                            {{ item.total_bet_amount }}-->
                    <!--                                        </td>-->

                    <!--                                        <td class="px-6 py-2.5 text-base text-center whitespace-nowrap font-medium text-green-700 border-b border-gray-300">-->
                    <!--                                            &lt;!&ndash;                                            {{ item.before_balance }}&ndash;&gt;-->
                    <!--                                            {{ item.after_bet_balance }}-->
                    <!--                                        </td>-->

                    <!--                                        <td class="px-6 py-2.5 font-medium text-base text-center capitalize whitespace-nowrap border-b border-gray-300"-->
                    <!--                                            :class="['text-' + item.bet]">-->
                    <!--                                            &lt;!&ndash;                                            {{ item.bet }}&ndash;&gt;-->
                    <!--                                        </td>-->
                    <!--                                        <td class="px-6 py-2.5 text-base capitalize whitespace-nowrap font-medium border-b border-gray-300"-->
                    <!--                                            :class="['text-ticket-' + item.result]">-->
                    <!--                                            &lt;!&ndash;                                            {{ item.result }}&ndash;&gt;-->
                    <!--                                        </td>-->
                    <!--                                        <td class="px-6 py-2.5 text-base italic capitalize font-medium whitespace-nowrap border-b border-gray-300"-->
                    <!--                                        >-->
                    <!--                                            &lt;!&ndash;                                            {{ item.status }}&ndash;&gt;-->
                    <!--                                            {{ item.total_win_and_loss_amount }}-->
                    <!--                                        </td>-->

                    <!--                                        <td class="px-6 py-2.5 text-base italic capitalize whitespace-nowrap font-medium border-b border-gray-300">-->
                    <!--                                            {{ item.current_balance }}-->
                    <!--                                        </td>-->
                    <!--                                        &lt;!&ndash; <td class="px-6 py-3 text-sm font-medium text-center whitespace-nowrap">-->
                    <!--                                            {{ item.winAmount }}-->
                    <!--                                        </td> &ndash;&gt;-->
                    <!--                                    </tr>-->

                    <tr>
                      <td class="px-6 py-3 text-sm text-center text-gray-500 whitespace-nowrap">
                        <!--                                            {{ item.fight }}-->
                        {{ item.fight }}-{{ item.id }}
                      </td>
                      <td class="px-6 py-3 text-sm text-center whitespace-nowrap">
                        {{ item.table }}
                      </td>
                      <td class="px-6 py-3 text-sm text-center whitespace-nowrap">
                        {{ item.date }}
                        {{ item.time }}
                      </td>
                      <!--                                        <td class="px-6 py-3 text-sm text-center whitespace-nowrap text-green-700">-->
                      <!--                                            &lt;!&ndash;                                            {{ item.before_balance }}&ndash;&gt;-->
                      <!--                                        </td>-->

                      <td class="px-6 py-3 text-sm text-center whitespace-nowrap">
                        {{ item.amount }}
                      </td>
                      <!--                                        <td class="px-6 py-3 text-sm text-center whitespace-nowrap text-green-700">-->
                      <!--                                            &lt;!&ndash;                                            {{ item.before_balance }}&ndash;&gt;-->

                      <!--                                        </td>-->
                      <td
                        class="px-6 py-3  text-sm text-center capitalize whitespace-nowrap"
                        :class="['text-' + item.betOn.value]"
                      >
                        {{ item.betOn.label }}
                      </td>
                      <td
                        class="px-6 py-3 text-sm capitalize whitespace-nowrap"
                        :class="['text-ticket-' + item.result]"
                      >
                        {{ __(item.result) }}
                      </td>
                      <td
                        class="px-6 py-3 text-sm italic capitalize whitespace-nowrap"
                        :class="['text-ticket-' + item.result]"
                      >
                        {{ item.status }}
                      </td>

                      <!--                                        <td class="px-6 py-3 text-sm italic capitalize whitespace-nowrap"-->
                      <!--                                            :class="['text-ticket-' + item.result]">-->
                      <!--                                            &lt;!&ndash;                                            {{ item.current_balance }}&ndash;&gt;-->
                      <!--                                            {{ item.win_and_loss_amount_display }}-->
                      <!--                                        </td>-->
                      <!-- <td class="px-6 py-3 text-sm font-medium text-center whitespace-nowrap">
                                            {{ item.winAmount }}
                                        </td> -->
                    </tr>
                  </template>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>


      <!--            <div class="flex items-center justify-center pt-3">-->
      <!--                <button type="button" aria-haspopup="true" aria-expanded="false"-->
      <!--                        class="inline-flex justify-center w-40 px-4 py-2 space-x-2 text-sm font-medium text-white bg-black border rounded-full shadow-sm border-border focus:outline-none">-->
      <!--                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24"-->
      <!--                         stroke="currentColor">-->
      <!--                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"-->
      <!--                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>-->
      <!--                    </svg>-->
      <!--                    <span>Load more</span>-->
      <!--                </button>-->
      <!--            </div>-->

      <!-- <Pagination
                :from="betHistoryRecords.from"
                :to="betHistoryRecords.to"
                :total="betHistoryRecords.total"
                :links="betHistoryRecords.links"
            /> -->
    </div>
  </app-layout>
</template>

<script>
import {defineComponent} from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Shared/Pagination.vue';
import {Link} from '@inertiajs/inertia-vue3';

export default defineComponent({
    name: 'BettingHistoty',

    components: {
        AppLayout,
        Pagination,
        Link,
    },

    props: {
        filters: Object,
        betTypeFilters: Object,
        betHistoryRecords: Object
    },

    data() {
        const status = (this.filters.status in this.betTypeFilters)
            ? this.filters.status
            : '';

        return {
            params: {
                status
            },
        };
    },

    watch: {
        params: {
            handler() {
                this.$inertia.get(
                    this.$page.url,
                    this.params,
                    {replace: true, preserveState: true}
                );
            },
            deep: true
        }
    },
    mounted: function () {
        this.$inertia.on('finish', (event) => {
            if (!this.$page.url.includes('status=')) {
                this.params.status = '';
            }

        });
    }

});
</script>

<style scoped>
.text-ticket-loss {
    /*font-weight: bold;*/
    color: darkred;
}

.text-ticket-win {
    /*font-weight: bold;*/
    color: darkblue;
}

.text-ticket-pending {
    /*font-weight: bold;*/
    color: darkgray;
}

.text-ticket-cancel {
    color: darkorange;
}

.text-ticket-draw {
    /*font-weight: bold;*/
    color: darkgreen;
}

</style>
