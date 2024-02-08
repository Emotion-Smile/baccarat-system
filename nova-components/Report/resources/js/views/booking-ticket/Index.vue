<template>
    <div class="relative">

        <heading
            :level="1"
            v-html="'Booking Tickets Report'"
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
                            v-for="bookingTicket in bookingTickets.data"
                            :key="bookingTicket.id"
                            :dusk="`${bookingTicket.id}-row`"
                        >
                            <td>
                                <div class="text-left">
                                    <span class="whitespace-no-wrap font-medium">
                                        {{ bookingTicket.fightNumber }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="text-left">
                                    <span class="whitespace-no-wrap font-medium">
                                        {{ bookingTicket.table }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="text-left">
                                    <span class="whitespace-no-wrap font-medium">
                                        {{ bookingTicket.betTime }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="text-left">
                                    <span class="whitespace-no-wrap font-bold text-success-dark">
                                        {{ bookingTicket.ip }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="text-left">
                                    <span class="whitespace-no-wrap font-semibold">
                                        {{ bookingTicket.member }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="text-left">
                                    <span
                                        class="whitespace-no-wrap font-bold"
                                        :class="{
                                            'text-danger': bookingTicket.betOn.value == 'meron',
                                            'text-primary': bookingTicket.betOn.value == 'wala',
                                        }"
                                    >
                                        {{ bookingTicket.betOn.label }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="text-left">
                                    <span class="whitespace-no-wrap font-semibold">
                                        {{ bookingTicket.amount }}
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
            title: 'Booking Tickets Report',
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
            bookingTickets: {},
        };
    },

    methods: {

        watchParamsForRequest() {
            return (
                this.currentPage
            );
        },

        setData(data) {
            this.perPage = data.bookingTickets.per_page;
            this.recordCount = data.bookingTickets.total;
            this.bookingTickets = data.bookingTickets;
        },

        selectPage(page) {
            this.updateQueryString({[this.pageParameter]: page});
        },

    },

    computed: {

        requestUrl() {
            return `/nova-vendor/report/booking-ticket-reports`;
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

    }

}
</script>
