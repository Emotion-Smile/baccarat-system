<template>
    <div class="relative">

        <heading
            :level="1"
            v-html="'CF Outstanding Tickets'"
            class="mb-6"
        />

        <card>
            <loading-view
                :loading="loading"
                :dusk="'payments-report-index-component'"
            >
                <table-report
                    :hasRecord="recordCount"
                >
                    <template v-slot:header>
                        <tr>
                            <th class="text-left">
                                <span>FIGHT #</span>
                            </th>
                            <th class="text-left">
                                <span>TABLE</span>
                            </th>
                            <th class="text-left">
                                <span>TIME</span>
                            </th>
                            <th class="text-left">
                                <span>IP ADDRESS</span>
                            </th>
                            <th class="text-left">
                                <span>MEMBER</span>
                            </th>
                            <th class="text-left">
                                <span>BET</span>
                            </th>
                            <th class="text-left">
                                <span>AMOUNT</span>
                            </th>
                        </tr>
                    </template>

                    <template v-slot:body>
                        <tr
                            v-for="outstandingTicket in outstandingTickets.data"
                            :key="outstandingTicket.id"
                            :dusk="`${outstandingTicket.id}-row`"
                        >
                            <td>
                                <div class="text-left">
                                    <span class="whitespace-no-wrap font-medium">
                                        {{ outstandingTicket.fightNumber }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="text-left">
                                    <span class="whitespace-no-wrap font-medium">
                                        {{ outstandingTicket.table }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="text-left">
                                    <span class="whitespace-no-wrap font-medium">
                                        {{ outstandingTicket.betTime }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="text-left">
                                    <span class="whitespace-no-wrap font-bold text-success-dark">
                                        {{ outstandingTicket.ip }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="text-left">
                                    <span class="whitespace-no-wrap font-semibold">
                                        {{ outstandingTicket.member }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="text-left">
                                    <span
                                        class="whitespace-no-wrap font-bold"
                                        :class="{
                                            'text-danger': outstandingTicket.betOn == 'Meron',
                                            'text-primary': outstandingTicket.betOn == 'Wala',
                                        }"
                                    >
                                        {{ outstandingTicket.betOn }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="text-left">
                                    <span class="whitespace-no-wrap font-semibold">
                                        {{ outstandingTicket.amount }}
                                    </span>
                                </div>
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

<script>
import { Paginatable } from 'laravel-nova';
import HandlesRequest from '@/mixins/HandlesRequest';

export default {

    metaInfo() {
        return {
            title: 'CF Outstanding Tickets',
        };
    },

    mixins: [
        Paginatable,
        HandlesRequest
    ],

    data() {
        return {
            perPage: 0,
            recordCount: 0,
            outstandingTickets: {},
        };
    },

    methods: {

        watchParamsForRequest() {
            return (
                this.currentPage
            );
        },

        setData(data) {
            this.perPage = data.outstandingTickets.per_page;
            this.recordCount = data.outstandingTickets.total;
            this.outstandingTickets = data.outstandingTickets;
        },

        selectPage(page) {
            this.updateQueryString({[this.pageParameter]: page});
        },

    },

    computed: {

        requestUrl() {
            return `/nova-vendor/report/outstanding-ticket-reports`;
        },

        requestQueryString() {
            return {
                page: this.currentPage,
            }
        },

        pageParameter() {
            return 'page';
        },

        totalPages() {
            return Math.ceil(this.recordCount / this.perPage);
        },
    },
}
</script>
