<template>
    <div class="relative">
        <div class="flex item-center mb-3">
            <previous-button
                :to="{
                    name: previousRouteName,
                    query: {
                        userId: preventUserId,
                        date,
                        from,
                        to
                    }
                }"
            />

            <heading
                :level="1"
                class="mb-3"
                v-html="'CF Bet Detail'"
            />
        </div>

        <card class="mb-6">
            <div class="py-3 px-3 flex items-center border-b border-50 justify-between">
                
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
                                <span>TABLE</span>
                            </th>

                            <th class="text-left">
                                <span>FIGHT NO</span>
                            </th>

                            <th class="text-left">
                                <span>USER</span>
                            </th>

                            <th class="text-left">
                                <span>DATE</span>
                            </th>

                            <th class="text-left">
                                <span>BET AMOUNT</span>
                            </th>

                            <th class="text-left">
                                <span>BET</span>
                            </th>

                            <th class="text-left">
                                <span>RESULT</span>
                            </th>

                            <th class="text-left">
                                <span>WIN/LOSE</span>
                            </th>
                        </tr>
                    </template>

                    <template v-slot:body>
                        <tr
                            v-for="item in reports.data"
                            :key="item.id"
                        >
                            <td>
                                <span class="whitespace-no-wrap">
                                    {{ item.group }}
                                </span>
                            </td>
                            <td>
                                <span class="whitespace-no-wrap">
                                    {{ item.fight }} - {{ item.id }}
                                </span>
                            </td>
                            <td>
                                <span class="whitespace-no-wrap">
                                    {{ item.username }}
                                </span>
                            </td>
                            <td>
                                <span class="whitespace-no-wrap font-semibold">
                                    {{ item.date }} {{ item.time }}
                                </span>
                            </td>
                            <td>
                                <span class="whitespace-no-wrap font-semibold">
                                    {{ item.amount }}
                                </span>
                            </td>
                            <td>
                                <span
                                    class="whitespace-no-wrap font-semibold capitalize"
                                    :class="{
                                        'text-danger': item.bet == 'meron',
                                        'text-primary': item.bet == 'wala',
                                    }"
                                >
                                    {{ item.bet }}
                                </span>
                            </td>
                            <td>
                                <span
                                    class="whitespace-no-wrap font-semibold capitalize"
                                    :class="['text-ticket-' + item.result]"
                                >
                                    {{ item.result }}
                                </span>
                            </td>
                            <td>
                                <span
                                    class="whitespace-no-wrap font-semibold italic"
                                    :class="['text-ticket-' + item.result]"
                                >
                                    {{ item.status }}
                                </span>
                            </td>
                        </tr>
                    </template>
                </table-report>
            </loading-view>
        </card>
    </div>
</template>

<script>
import HandlesRequest from '../../mixins/HandlesRequest';

export default {

    metaInfo() {
        return {
            title: 'Win/Loss Report Detail',
        };
    },

    mixins: [
        HandlesRequest
    ],

    data() {
        return {
            reports: {},
            recordCount: 0,
            preventUserId: 0,
        };
    },

    methods: {

        watchParamsForRequest() {
            return (
                this.date +
                this.from +
                this.to
            );
        },

        setData(data) {
            this.reports = data.reports;
            this.recordCount = data.reports.total;
            this.preventUserId = data.preventUserId;
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
            return `/nova-vendor/report/bet-detail/${this.memberId}`;
        },

        requestQueryString() {
            return {
                date: this.date,
                from: this.from,
                to: this.to,
            }
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

        previousRouteName() {
            return this.$route.query['previousRouteName'] || 'report-win-lose';
        }
    }
}
</script>
