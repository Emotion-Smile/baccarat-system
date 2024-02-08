<template>
    <app-layout title="Deposit-Withdraw">
        <div
            class="flex-1 w-full mx-auto p-4 rounded bg-gray-50 xl:max-w-7xl">
            <div class="flex items-center justify-between mb-4 flex-col sm:flex-row">
                <h1 class="text-2xl text-wala">{{ __('deposit_withdraw') }}</h1>

                <div class="flex flex-col space-y-3 lg:flex-row lg:flex-row-reverse sm:items-end">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">{{
                            __('statement')
                            }}</label>
                        <select id="status" v-model="params.status"
                                class="block w-full py-2 pl-3 pr-10 mt-1 text-base border-gray-300 rounded-md sm:w-36 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">{{ __('all') }}</option>
                            <option
                                v-for="(item, key) in transactionTypeFilter"
                                :key="key"
                                :value="key"
                            >{{ __(item) }}
                            </option>
                        </select>
                    </div>

                    <div class="relative z-0 inline-flex shadow-sm rounded-md ml-auto mr-3 self-end h-10">
                        <Link href="/member/deposit?date=today" :only="['transactionRecords']"
                              preserve-state preserve-scroll replace
                              :class="{ 'text-red-500': $page.url.includes('date=today') }"
                              class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-xs lg:text-base text-gray-700"
                        >{{ __('today') }}
                        </Link>

                        <Link href="/member/deposit?date=yesterday" :only="['transactionRecords']"
                              preserve-state preserve-scroll replace
                              :class="{ 'text-red-500': $page.url.includes('date=yesterday') }"
                              class="-ml-px relative inline-flex items-center px-2 py-2 border border-gray-300 bg-white text-xs lg:text-base text-gray-700"
                        >{{ __('yesterday') }}
                        </Link>

                        <Link href="/member/deposit?date=this-week" :only="['transactionRecords']"
                              preserve-state preserve-scroll replace
                              :class="{ 'text-red-500': $page.url.includes('date=this-week') }"
                              class="-ml-px relative inline-flex items-center px-2 py-2 border border-gray-300 bg-white text-xs lg:text-base text-gray-700"
                        >{{ __('this_week') }}
                        </Link>

                        <Link href="/member/deposit?date=last-week" :only="['transactionRecords']"
                              preserve-state preserve-scroll replace
                              :class="{ 'text-red-500': $page.url.includes('date=last-week') }"
                              class="-ml-px relative inline-flex items-center px-2 py-2  border rounded-r-md  border-gray-300 bg-white text-xs lg:text-base text-gray-700"
                        >{{ __('last_week') }}
                        </Link>
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
                                    <th scope="col"
                                        class="w-20 px-6 py-2 text-sm font-medium tracking-wider text-center text-white uppercase">
                                        #
                                    </th>
                                    <th scope="col"
                                        class="w-32 px-6 py-2 text-sm font-medium tracking-wider text-center text-white uppercase">
                                        {{ __('last_week') }}
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-2 text-sm font-medium tracking-wider text-center text-white uppercase w-28">
                                        {{ __('before_balance') }}
                                    </th>

                                    <th scope="col"
                                        class="px-6 py-2 text-sm font-medium tracking-wider text-center text-white uppercase w-28">
                                        {{ __('amount') }}
                                    </th>

                                    <th scope="col"
                                        class="px-6 py-2 text-sm font-medium tracking-wider text-center text-white uppercase w-28">
                                        {{ __('current_balance') }}
                                    </th>
                                    <th scope="col"
                                        class="w-32 px-6 py-2 text-sm font-medium tracking-wider text-center text-white uppercase">
                                        {{ __('statement') }}
                                    </th>
                                    <th scope="col"
                                        class="w-32 px-6 py-2 text-sm font-medium tracking-wider text-center text-white uppercase">
                                        {{ __('note') }}
                                    </th>


                                    <!--                                    <th scope="col"-->
                                    <!--                                        class="w-32 px-6 py-3 text-xs font-medium tracking-wider text-center text-white uppercase">-->
                                    <!--                                        STATUS-->
                                    <!--                                    </th>-->

                                    <!-- <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-white uppercase">
                                        REMARK
                                    </th> -->
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="item in transactionRecords.data" :key="item.id" class="bg-white">
                                    <td class="px-6 py-2 text-sm font-medium text-center text-gray-900 whitespace-nowrap">
                                        <div class="inline-flex items-center justify-center w-6 h-6 border rounded-full"
                                             :class="(item.status === 'deposit' || item.status === 'payout' ) ? ' border-wala rotate-225' : 'border-meron rotate-45'">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
                                                 :class="(item.status === 'deposit' || item.status === 'payout' ) ? 'text-wala' : 'text-meron'"
                                                 viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                      d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z"
                                                      clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                    </td>
                                    <td class="px-6 py-2 text-sm text-center whitespace-nowrap">
                                        {{ item.date }} {{ item.time }}
                                        <template v-if="item.show_all_transaction">
                                            <br/>
                                            <span class="text-gray-600 pt-4">
                                                {{ item.fight_number }} - {{ item.bet_id }}
                                            </span>
                                        </template>
                                    </td>
                                    <td class="px-6 py-2 text-sm text-center whitespace-nowrap text-green-700 font-bold">
                                        {{ item.before_balance }}
                                        <!--                                        <br/>-->
                                        <!--                                        <pre>-->
                                        <!--                                        {{ item.meta }}-->
                                        <!--                                        </pre>-->
                                    </td>

                                    <td class="px-6 py-2 text-sm text-center whitespace-nowrap font-bold"
                                        :class="(item.status === 'deposit' || item.status === 'payout' )? 'text-wala': 'text-meron'">
                                        {{ item.amount }}
                                    </td>

                                    <td class="px-6 py-2 text-sm text-center whitespace-nowrap font-bold">
                                        {{ item.current_balance }}
                                        <template v-if="item.show_all_transaction">
                                            <hr/>
                                            {{ item.current_balance }}
                                        </template>
                                    </td>
                                    <td class="px-6 py-2 text-sm text-center capitalize whitespace-nowrap"
                                        :class="(item.status === 'deposit' || item.status === 'payout' )? 'text-wala': 'text-meron'">
                                        {{ __(item.status) }}
                                    </td>

                                    <td class="px-6 py-2 text-sm text-center capitalize whitespace-nowrap">
                                        {{ item.note }}
                                    </td>
                                    <!--                                    <td class="px-6 py-2 text-sm text-center capitalize whitespace-nowrap"-->
                                    <!--                                        :class="item.status === 'deposit' ? 'text-wala': 'text-meron'">-->

                                    <!--                                        {{ item.meta.type }}-->
                                    <!--                                    </td>-->

                                    <!-- <td class="px-6 py-2 text-sm text-left whitespace-nowrap">
                                        {{ item.remark }}
                                    </td> -->
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <Pagination
                :from="transactionRecords.from ?? 0"
                :to="transactionRecords.to ?? 0"
                :total="transactionRecords.total ?? 0"
                :links="transactionRecords.links"
            />

        </div>
    </app-layout>
</template>

<script>
import {defineComponent} from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Shared/Pagination.vue';
import {Link} from '@inertiajs/inertia-vue3'

export default defineComponent({
    name: "DepositWithdraw",

    props: {
        filters: Object,
        transactionRecords: Object,
        transactionTypeFilter: Object,
        user: Object
    },

    components: {
        AppLayout,
        Pagination,
        Link
    },

    data() {
        let status = (this.filters.status in this.transactionTypeFilter)
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
