<template>
    <div class="relative">
        <div class="flex item-center mb-3">
            <heading
                :level="1"
                v-html="`Top Winners`"
            />
        </div>

        <card class="mb-6">
            <div class="p-3 flex items-center border-b border-50 justify-end">
                <date-picker
                    :value="date"
                    @change="onDateChange"
                />
            </div>
        </card>

        <card>
            <loading-view
                :loading="loading"
                dusk="report-top-winner-index-component"
            >
                <table-report
                    :hasRecord="recordCount"
                >
                    <template v-slot:header>
                        <tr>
                            <th class="text-left">
                                <span>ID</span>
                            </th>
                            <th class="text-left">
                                <span>NAME</span>
                            </th>
                            <th class="text-left">
                                <span>TOTAL BET AMOUNT</span>
                            </th>
                            <th class="text-left">
                                <span>TOTAL TICKET</span>
                            </th>
                            <th class="text-left">
                                <span>WIN COUNT</span>
                            </th>
                            <th class="text-left">
                                <span>AVG AMOUNT PER TICKET</span>
                            </th>
                            <th class="text-left">
                                <span>WIN RATE</span>
                            </th>
                        </tr>
                    </template>

                    <template v-slot:body>
                        <tr
                            v-for="report in reports"
                            :key="report.id"
                            :dusk="`${report.id}-row`"
                        >
                            <td 
                                v-for="(columnKey, index) in columnKeys"
                                :key="`${columnKey}-${index}`"
                            >
                                <div 
                                    class="text-left" 
                                    v-html="report[columnKey]"
                                ></div>
                            </td>
                        </tr>
                    </template>
                </table-report>
            </loading-view>
        </card>
    </div>
</template>

<script>
import HandlesRequest from '@/mixins/HandlesRequest';

export default {

    metaInfo() {
        return {
            title: 'Top Winners',
        };
    },

    mixins: [
        HandlesRequest
    ],

    data() {
        return {
            reports: [],
            recordCount: 0,
            columnKeys: [
                'id',
                'name',
                'totalBetAmount',
                'totalTicket',
                'winCount',
                'avgAmountPerTicket',
                'winRate',
            ]
        };
    },
    methods: {
        watchParamsForRequest() {
            return (
                this.date
            );
        },

        setData(data) {
            this.reports = data.reports;
            this.recordCount = data.reports.length;
        },

        onDateChange(date) {
            this.updateQueryString({
                date
            });
        },
    },

    computed: {

        requestUrl() {
            return `/nova-vendor/report/top-winners`;
        },

        requestQueryString() {
            let param = {
                date: this.date
            }

            return param;
        },

        date() {
            return this.$route.query['date'];
        }, 
    },
}
</script>
