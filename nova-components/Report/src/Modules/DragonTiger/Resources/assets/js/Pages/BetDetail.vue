<script>
import { Paginatable } from 'laravel-nova';
import HandlesRequest from '@/mixins/HandlesRequest';

export default {
    metaInfo() {
        return {
            title: 'D&T Bet Detail',
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
            previousUserId: 0
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

        setData({ items, previousUserId }) {
            this.items = items.data;
            this.perPage = items.per_page;
            this.recordCount = items.total;
            this.previousUserId = previousUserId;
        },

        selectPage(page) {
            this.updateQueryString({
                [this.pageParameter]: page
            });
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
    },

    computed: {
        requestUrl() {
            return `/nova-vendor/report/dragon-tiger/bet-detail/${this.memberId}`;
        },

        requestQueryString() {
            return {
                page: this.currentPage,
                date: this.date,
                from: this.from,
                to: this.to,
            }
        },

        pageParameter() {
            return 'page'
        },

        totalPages() {
            return Math.ceil(this.recordCount / this.perPage)
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

        hasRecord() {
            return this.items.length > 0;
        },

        previousRouteName() {
            return this.$route.query['previousRouteName'] || 'report-dragon-tiger-win-lose';
        }
    },
}
</script>

<template>
    <div class="relative">
        <div class="flex item-center mb-3">
            <previous-button
                :to="{
                    name: previousRouteName,
                    query: {
                        date,
                        from,
                        to,
                        userId: previousUserId  
                    }
                }"
            />

            <heading
                :level="1"
                v-html="`D&T Bet Detail`"
            />
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
            >
                <table-report
                    :hasRecord="hasRecord"
                >
                    <template v-slot:header>
                        <tr>
                            <th class="text-left">
                                <span>Id</span>
                            </th>

                            <th class="text-left">
                                <span>Table</span>
                            </th>

                            <th class="text-left">
                                <span>Game #</span>
                            </th>

                            <th class="text-left">
                                <span>Member</span>
                            </th>

                            <th class="text-left">
                                <span>Date</span>
                            </th>

                            <th class="text-left">
                                <span>Bet Amount</span>
                            </th>

                            <th class="text-left">
                                <span>Bet</span>
                            </th>
                            
                            <th class="text-left">
                                <span>Game Result</span>
                            </th>

                            <th class="text-left">
                                <span>Result</span>
                            </th>

                            <th class="text-left">
                                <span>Win/Lose</span>
                            </th>

                            <th class="text-left">
                                <span>Ip</span>
                            </th>
                        </tr>
                    </template>

                    <template v-slot:body>
                        <tr
                            v-for="(item, key) in items"
                            :key="key"
                        >
                            <td>
                                <span class="whitespace-no-wrap font-semibold">
                                    {{ item.id }}
                                </span>
                            </td>

                            <td>
                                <span class="whitespace-no-wrap font-semibold">
                                    {{ item.table }}
                                </span>
                            </td>

                            <td>
                                <span class="whitespace-no-wrap font-semibold">
                                    {{ item.game }}
                                </span>
                            </td>

                            <td>
                                <span class="whitespace-no-wrap font-semibold">
                                    {{ item.member }}
                                </span>
                            </td>

                            <td>
                                <span class="whitespace-no-wrap font-semibold">
                                    {{ item.date }}
                                </span>
                            </td>

                            <td>
                                <span v-html="item.betAmount"></span>
                            </td>

                            <td>
                                <span v-html="item.bet"></span>
                            </td>

                            <td>
                                <div class="flex my-2 items-center">
                                    <p class="font-bold">Dragon:</p> 
                                    <span v-html="item.gameResult.dragon"></span>
                                </div>

                                <div class="flex my-2 items-center">
                                    <p class="font-bold">Tiger:</p>  
                                    <span v-html="item.gameResult.tiger"></span>
                                </div>
                            </td>

                            <td>
                                <span v-html="item.result"></span>
                            </td>

                            <td>
                                <span v-html="item.winLose"></span>
                            </td>

                            <td>
                                <span v-html="item.ip"></span>
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

