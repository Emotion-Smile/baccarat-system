<script>
import { Paginatable } from 'laravel-nova';
import HandlesRequest from '@/mixins/HandlesRequest';

export default {
    metaInfo() {
        return {
            title: 'Yuki Outstanding Tickets',
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
            outstandingTickets: [],
        };
    },

    methods: {
        watchParamsForRequest() {
            return (
                this.currentPage
            );
        },
        
        setData({ outstandingTicket }) {
            this.perPage = outstandingTicket.meta.per_page;
            this.recordCount = outstandingTicket.meta.total;
            this.outstandingTickets = outstandingTicket.data;
        },

        selectPage(page) {
            this.updateQueryString({[this.pageParameter]: page});
        },
    },

    computed: {
        requestUrl() {
            return `/nova-vendor/report/t88/outstanding/tickets`;
        },

        requestQueryString() {
            return {
                page: this.currentPage,
            }
        },

        pageParameter() {
            return 'page'
        },

        totalPages() {
            return Math.ceil(this.recordCount / this.perPage)
        },
    },
}
</script>

<template>
    <div class="relative">

        <heading
            :level="1"
            v-html="'Yuki Outstanding Tickets'"
            class="mb-6"
        />

        <card>
            <loading-view
                :loading="loading"
            >
                <table-report
                    :hasRecord="recordCount"
                >
                    <template v-slot:header>
                        <tr>
                            <th class="text-left">
                                <span>#</span>
                            </th>

                            <th class="text-left">
                                <span>GAME NUMBER</span>
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
                            v-for="(outstandingTicket, key) in outstandingTickets"
                            :key="outstandingTicket.id"
                            :dusk="`${outstandingTicket.id}-row`"
                        >
                            <td>
                                <div class="text-left">
                                    <span class="whitespace-no-wrap font-medium">
                                        {{ key + 1 }}
                                    </span>
                                </div>
                            </td>

                            <td>
                                <div class="text-left">
                                    <span class="whitespace-no-wrap font-medium">
                                        {{ outstandingTicket.game_code }}
                                    </span>
                                </div>
                            </td>

                            <td>
                                <div class="text-left">
                                    <span class="whitespace-no-wrap font-medium">
                                        {{ outstandingTicket.date_time }}
                                    </span>
                                </div>
                            </td>

                            <td>
                                <div class="text-left">
                                    <span class="whitespace-no-wrap font-bold text-success-dark">
                                        {{ outstandingTicket.ip_address }}
                                    </span>
                                </div>
                            </td>

                            <td>
                                <div class="text-left">
                                    <span class="whitespace-no-wrap font-semibold">
                                        {{ outstandingTicket.name }}
                                    </span>
                                </div>
                            </td>

                            <td>
                                <div class="text-left">
                                    <span class="whitespace-no-wrap font-bold">
                                        {{ outstandingTicket.bet }}
                                    </span>
                                </div>
                            </td>

                            <td>
                                <div class="text-left">
                                    <span class="whitespace-no-wrap font-semibold">
                                        {{ `${outstandingTicket.amount_text} x ${outstandingTicket.rate} = ${outstandingTicket.total_amount_text}` }}
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


