<script>
import { Paginatable } from 'laravel-nova';
import HandlesRequest from '@/mixins/HandlesRequest';

export default {
    metaInfo() {
        return {
            title: 'Balance Statement Report',
        };
    },

    mixins: [
        Paginatable,
        HandlesRequest
    ],

    data() {
        return {
            items: [],
            perPage: 0,
            recordCount: 0,
            previousUserId: 0,
            currentBalance: 0,
        };
    },

    methods: {
        watchParamsForRequest() {
            return (
                this.currentPage +
                this.date + 
                this.from + 
                this.to
            );
        },

        setData({ previousUserId, balanceStatement, currentBalance }) {
            this.items = balanceStatement.data;
            this.perPage = balanceStatement.per_page;
            this.recordCount = balanceStatement.total;

            this.previousUserId = previousUserId;
            this.currentBalance = currentBalance;
        },

        selectPage(page) {
            this.updateQueryString({[this.pageParameter]: page});
        },

        onPeriodDateChange(value) {
            this.updateQueryString({
                date: value,
                from: null,
                to: null,
            });
        },

        onDateRangeChange(value) {
            this.updateQueryString({
                ...value,
                date: null,
            });
        },

        statementClass(item) {
            return (
                item.status === 'deposit' 
                || item.status === 'payout' 
                || item.status === 'refund'
            )
                ? 'text-primary' 
                : 'text-danger';
        },

        exceptWithdrawAndDeposit(item) {
            return item.status !== 'withdraw' && item.status !== 'deposit';
        },

        isWithdraw(item) {
            return item.status !== 'deposit' 
                && item.status !== 'payout' 
                && item.status !== 'refund'; 
        }
    },

    computed: {
        requestUrl() {
            return `/nova-vendor/report/dragon-tiger/balance/statement/${this.memberId}`;
        },

        requestQueryString() {
            return {
                page: this.currentPage,
                date: this.date,
                from: this.from,
                to: this.to,
            };
        },

        pageParameter() {
            return 'page';
        },

        totalPages() {
            return Math.ceil(this.recordCount / this.perPage);
        },

        date() {
            return this.$route.query['date'];
        },

        from() {
            return this.$route.query['from'];
        },

        to() {
            return this.$route.query['to'];
        },

        memberId() {
            return this.$route.params.memberId;
        },

        isToday() {
            return this.date === 'today';
        }
    },
}
</script>

<template>
    <div class="relative">
        <div class="flex item-center mb-3">
            <previous-button
                :to="{
                    name: 'report-dragon-tiger-win-lose',
                    query: {
                        date,
                        userId: previousUserId,
                    }
                }"
            />

            <heading
                :level="1"
                v-html="'Balance Statement Report'"
            />

            <div
                v-if="isToday"
                class="flex items-center ml-3"
            >
                <span class="font-semibold mr-3">
                    ( Current Balance: <span class="font-bold">{{ currentBalance }}</span> )
                </span>
            </div>

        </div>

        <card class="mb-6">
            <div class="p-3 flex items-center border-b border-50 justify-between">
                <period-date-picker
                    :selected="date"
                    @change="onPeriodDateChange"
                />

                <date-range-picker
                    :to="to"
                    :from="from"
                    @change="onDateRangeChange"
                />
            </div>
        </card>

        <card>
            <loading-view
                :loading="loading"
                dusk="report-win-lose-index-component"
            >
                <table-report
                    :hasRecord="recordCount"
                >
                    <template v-slot:header>
                        <tr>
                            <th class="text-left">
                                <span>&nbsp;</span>
                            </th>

                            <th class="text-left">
                                <span>Game</span>
                            </th>

                            <th class="text-left">
                                <span>Table</span>
                            </th>

                            <th class="text-left">
                                <span>Username</span>
                            </th>

                            <th class="text-left">
                                <span>Date</span>
                            </th>

                            <th class="text-left">
                                <span>Fight No</span>
                            </th>

                            <th class="text-left">
                                <span>Before Balance</span>
                            </th>

                            <th class="text-left">
                                <span>Amount</span>
                            </th>

                            <th class="text-left">
                                <span>Current Balance</span>
                            </th>

                            <th class="text-left">
                                <span>Statement</span>
                            </th>

                            <th class="text-left">
                                <span>Note</span>
                            </th>
                        </tr>
                    </template>

                    <template v-slot:body>
                        <tr
                            v-for="(item, key) in items"
                            :key="key"
                        >
                            <td>
                                <span class="whitespace-no-wrap">
                                    <icon-transaction :isWithdraw="isWithdraw(item)" />
                                </span>
                            </td>

                            <td>
                                <span class="whitespace-no-wrap font-semibold">
                                    {{ item.game }}
                                </span>
                            </td>

                            <td>
                                <span class="whitespace-no-wrap font-semibold">
                                    {{ item.group }}
                                </span>
                            </td>

                            <td>
                                <span class="whitespace-no-wrap font-semibold">
                                    {{ item.username }}
                                </span>
                            </td>

                            <td>
                                <span class="whitespace-no-wrap font-semibold">
                                    {{ item.date }} {{ item.time }}
                                </span>
                            </td>

                            <td>
                                <span 
                                    v-if="exceptWithdrawAndDeposit(item)" 
                                    class="whitespace-no-wrap"
                                >
                                    <template v-if="item.game.toLowerCase() === 'sport'">
                                        {{ item.fightNumber }}
                                    </template>

                                    <template v-else>
                                        {{ item.fightNumber }} - {{ item.betId }}
                                    </template>
                                </span>
                            </td>

                            <td>
                                <span class="whitespace-no-wrap font-semibold">
                                    {{ item.beforeBalance.text }}
                                </span>
                            </td>

                            <td>
                                <span
                                    class="whitespace-no-wrap font-semibold capitalize"
                                    :class="statementClass(item)"
                                >
                                    {{ item.amount.text }}
                                </span>
                            </td>

                            <td>
                                <span class="whitespace-no-wrap font-semibold capitalize">
                                    {{ item.currentBalance.text }}
                                </span>
                            </td>

                            <td>
                                <span
                                    class="whitespace-no-wrap font-semibold capitalize"
                                    :class="statementClass(item)"
                                >
                                    {{ item.status }}
                                </span>
                            </td>

                            <td>
                                <span class="whitespace-no-wrap font-semibold capitalize">
                                    {{ item.note }}
                                </span>
                            </td>
                        </tr>
                    </template>

                    <template v-slot:pagination>
                        <pagination-links
                            :page="currentPage"
                            :pages="totalPages"
                            :per-page="perPage"
                            @page="selectPage"
                        />
                    </template>
                </table-report>
            </loading-view>
        </card>
    </div>
</template>


