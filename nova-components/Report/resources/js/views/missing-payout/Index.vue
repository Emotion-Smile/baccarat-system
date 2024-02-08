<template>
    <div class="relative">
        <div class="flex item-center mb-3">
            <heading
                :level="1"
                v-html="'Missing Payouts'"
            />
        </div>

        <card class="mb-6">
            <div class="p-3 flex items-center border-b border-50 justify-end">
                <date-picker 
                    :value="date"
                    @change="handleDateChange"
                />
            </div>
        </card>

        <card>
            <loading-view
                :loading="loading"
                dusk="report-missing-payout-index-component"
            >
                <div class="flex pr-6 pt-6 justify-end">
                    <button 
                        class="text-primary hover:text-primary-dark"
                        @click.prevent="refresh"
                    >
                        <icon 
                            type="refresh" 
                            :width="22" 
                            :height="22" 
                            viewBox="0 0 22 22"
                        />
                    </button>
                </div>


                <table-report
                    :hasRecord="missingPayouts.length > 0"
                >
                    <template v-slot:header>
                        <tr>
                            <th class="text-left">
                                <span>NO</span>
                            </th>
                            <th class="text-left">
                                <span>MATCH ID</span>
                            </th>
                            <th class="text-left">
                                <span>Table</span>
                            </th>
                            <th class="text-left">
                                <span>FIGHT NUMBER</span>
                            </th>
                            <th class="text-left">
                                <span>USER NAME</span>
                            </th>
                            <th class="text-left">
                                <span>BET AMOUNT</span>
                            </th>
                            <th class="text-left">
                                <span>PAYOUT</span>
                            </th>
                            <th class="text-left">
                                <span>DEPOSIT BACK</span>
                            </th>
                            <th class="text-left">
                                <span>DEPOSIT BACK VALUE</span>
                            </th>
                            <th class="text-left">
                                <span>MATCH DATE</span>
                            </th>
                            <th class="text-left">
                                <span>MATCH END AT</span>
                            </th>
                        </tr>
                    </template>

                    <template v-slot:body>
                        <tr
                            v-for="(missingPayout, index) in missingPayouts"
                            :key="missingPayout.id"
                            :dusk="`${missingPayout.id}-row`"
                        >
                            <td>
                                <div class="text-left">
                                    <p>{{ index + 1 }}</p>
                                </div>
                            </td>
                            <td>
                                <div class="text-left">
                                    <p>{{ missingPayout.matchId }}</p>
                                </div>
                            </td>
                            <td>
                                <div class="text-left">
                                    <span class="whitespace-no-wrap font-bold text-primary">
                                        {{ missingPayout.table }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="text-left">
                                    <p>{{ missingPayout.fightNumber }}</p>
                                </div>
                            </td>
                            <td>
                                <div class="text-left">
                                    <span class="whitespace-no-wrap font-semibold">
                                        {{ missingPayout.memberName }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="text-left">
                                    <span class="whitespace-no-wrap font-semibold">
                                        {{ missingPayout.betAmount }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="text-left">
                                    <span class="whitespace-no-wrap font-semibold">
                                        {{ missingPayout.payout }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="text-left">
                                    <span class="whitespace-no-wrap font-semibold">
                                        {{ missingPayout.depositPrice }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="text-left">
                                    <span class="whitespace-no-wrap font-bold text-danger">
                                        {{ missingPayout.depositBack }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="text-left">
                                    <span class="whitespace-no-wrap font-semibold">
                                        {{ missingPayout.matchDate }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="text-left">
                                    <span class="whitespace-no-wrap font-semibold">
                                        {{ missingPayout.matchEndAt }}
                                    </span>
                                </div>
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
            title: 'Missing Payouts',
        };
    },

    mixins: [
        HandlesRequest
    ],

    data() {
        return {
            missingPayouts: []
        };
    },

    methods: {
    
        watchParamsForRequest() {
            return (
                this.date
            );
        },

        setData(data) {
            this.missingPayouts = data.missingPayouts;
        },

        handleDateChange(value) {
            this.updateQueryString({
                date: value,
            });
        },

        refresh() {
            this.requestData();
        }
    },

    computed: {

        requestUrl() {
            return `/nova-vendor/report/missing-payouts`;
        },

        requestQueryString() {
            return {
                date: this.date
            };
        },

        date() {
            return this.$route.query['date'];
        },
    },
}
</script>
