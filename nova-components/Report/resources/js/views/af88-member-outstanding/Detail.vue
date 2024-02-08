<script>
import HandlesRequest from '@/mixins/HandlesRequest';

export default {
    metaInfo() {
        return {
            title: 'AF88 Member Outstanding Detail',
        };
    },

    mixins: [
        HandlesRequest
    ],

    data() {
        return {
            currency: '',
            totalAmount: 0,
            outstandingTickets: [],
        };
    },

    methods: {
        setData({ report }) {
            this.currency = report.currency; 
            this.totalAmount = report.total_amount;
            this.outstandingTickets = report.outstanding_tickets;
        },
    },

    computed: {
        requestUrl() {
            return `/nova-vendor/report/af88/member/outstanding/detail/${this.accountId}`;
        },
        
        accountId() {
            return this.$route.params.accountId;
        },
    },
}
</script>

<template>
    <div class="relative">
        <div class="flex item-center">
            <previous-button
                :to="{ name: 'report-member-outstanding' }"
            />

            <heading
                :level="1"
                v-html="'AF88 Member Outstanding Detail'"
                class="mb-6"
            />
        </div>

        <card>
            <loading-view
                :loading="loading"
            >
                <table-report
                    :hasRecord="outstandingTickets.length > 0"
                >
                    <template v-slot:header>
                        <tr>
                            <th class="text-left border-r">
                                <span>&nbsp;</span>
                            </th>

                            <th class="text-left border-r">
                                <span>Account</span>
                            </th>

                            <th class="text-left border-r">
                                <span>Currency</span>
                            </th>

                            <th class="text-left border-r">
                                <span>Date</span>
                            </th>

                            <th class="text-center border-r">
                                <span>Event</span>
                            </th>

                            <th class="text-left border-r">
                                <span>Detail</span>
                            </th>

                            <th class="text-right border-r">
                                <span>Amount</span>
                            </th>

                            <th class="text-right border-r">
                                <span>{{ currency }}</span>
                            </th>
                        </tr>
                    </template>

                    <template v-slot:body>
                        <template v-for="(outstandingTicket, key) in outstandingTickets">
                            <tr 
                                v-if="outstandingTicket.ticket_type === 'single_bet'"
                                :key="key"
                            >
                                <td class="text-left border-r">
                                    <span class="whitespace-no-wrap font-medium">
                                        {{ key + 1 }}
                                    </span>
                                </td>

                                <td class="text-left border-r">
                                    <span class="whitespace-no-wrap font-medium">
                                        {{ outstandingTicket.account }}
                                    </span>
                                </td>

                                <td class="text-left border-r">
                                    <span class="whitespace-no-wrap font-medium">
                                        {{ outstandingTicket.currency }}
                                    </span>
                                </td>

                                <td class="text-left border-r">
                                    <span class="whitespace-no-wrap">
                                        <h5 class="font-bold">{{ outstandingTicket.serial_number }}</h5>
                                        <h5 class="font-medium">{{ outstandingTicket.datetime }}</h5>
                                    </span>
                                </td>

                                <td class="text-left border-r">
                                    <span class="whitespace-no-wrap">
                                        <h5 class="font-medium">{{ outstandingTicket.event_name }}</h5>
                                        <h5 class="font-medium">{{ outstandingTicket.match.home }} vs {{ outstandingTicket.match.away }} <template v-if="outstandingTicket.is_half_time">(First Half)</template></h5>
                                        <h5 class="font-bold">{{ outstandingTicket.match.started_at }}</h5>
                                    </span>
                                </td>

                                <td class="text-left border-r">
                                    <span class="whitespace-no-wrap">
                                        <h5 
                                            v-if="outstandingTicket.is_hdp_bet"  
                                            class="font-medium"
                                            :class="{ 
                                                'text-danger': outstandingTicket.match.give_goal_by === outstandingTicket.bet_on_type 
                                            }"
                                        >
                                            {{ outstandingTicket.bet_on_type === 1 ? outstandingTicket.match.home : outstandingTicket.match.away }}
                                        </h5>

                                        <h5 
                                            v-else 
                                            class="font-medium uppercase"
                                            :class="{ 
                                                'text-danger': outstandingTicket.bet_on_type === 3, 
                                                'text-primary': outstandingTicket.bet_on_type === 4
                                            }"
                                        >
                                            {{ outstandingTicket.bet_on_type === 3 ? 'Over' : 'Under' }}
                                        </h5>

                                        <h5 class="font-medium">{{ outstandingTicket.hdp }} @ <span class="font-bold">{{ outstandingTicket.odds }} (HK)</span></h5>
                                    </span>
                                </td>

                                <td class="text-right border-r">
                                    <span class="whitespace-no-wrap font-medium">
                                        {{ outstandingTicket.amount }}
                                    </span>
                                </td>

                                <td class="text-right border-r">
                                    <span class="whitespace-no-wrap font-bold">
                                        {{ outstandingTicket.up_line_amount }}
                                    </span>
                                </td>
                            </tr>

                            <tr 
                                :key="key"
                                v-else
                            >
                                <td class="text-left border-r">
                                    <span class="whitespace-no-wrap font-medium">
                                        {{ key + 1 }}
                                    </span>
                                </td>

                                <td class="text-left border-r">
                                    <span class="whitespace-no-wrap font-medium">
                                        {{ outstandingTicket.account }}
                                    </span>
                                </td>

                                <td class="text-left border-r">
                                    <span class="whitespace-no-wrap font-medium">
                                        {{ outstandingTicket.currency }}
                                    </span>
                                </td>

                                <td class="text-left border-r">
                                    <span class="whitespace-no-wrap">
                                        <h5 class="font-bold">{{ outstandingTicket.serial_number }}</h5>
                                        <h5 class="font-medium">{{ outstandingTicket.datetime }}</h5>
                                    </span>
                                </td>

                                <td class="text-left border-r">
                                    <span 
                                        v-if="outstandingTicket.event_name" 
                                        class="whitespace-no-wrap"
                                    >
                                        <h5 class="font-medium">{{ outstandingTicket.event_name }}</h5>
                                        <h5 class="font-medium">{{ outstandingTicket.match.home }} vs {{ outstandingTicket.match.away }} <template v-if="outstandingTicket.is_half_time">(First Half)</template></h5>
                                        <h5 class="font-bold">{{ outstandingTicket.match.started_at }}</h5>
                                    </span>
                                </td>

                                <td class="text-left border-r">
                                    <span class="whitespace-no-wrap">
                                        <h5>
                                            <a
                                                :href="`/admin/report/win-lose/mix-parlay/detail/${outstandingTicket.id}`"
                                                class="text-success"
                                                target="_blank"
                                            >
                                                MIX PARLAY
                                            </a>

                                            <span
                                                class="font-bold" 
                                                :class="{ 'text-danger': outstandingTicket.is_special_type }"
                                            >
                                                ({{ outstandingTicket.type }})
                                            </span>
                                        </h5>

                                        <h5 class="font-medium">{{ outstandingTicket.hdp }} @ <span class="font-bold">{{ outstandingTicket.odds }} (HK)</span></h5>
                                    </span>
                                </td>

                                <td class="text-right border-r">
                                    <span class="whitespace-no-wrap font-medium">
                                        {{ outstandingTicket.amount }}
                                    </span>
                                </td>

                                <td class="text-right border-r">
                                    <span class="whitespace-no-wrap font-bold">
                                        {{ outstandingTicket.up_line_amount }}
                                    </span>
                                </td>
                            </tr>
                        </template>
                        
                        <tr>
                            <td 
                                v-for="i in 7" 
                                :key="i"
                                class="border-r"
                            ></td>

                            <td class="text-right border-r">
                                <span class="whitespace-no-wrap font-bold">
                                    {{ totalAmount }}
                                </span>
                            </td>
                        </tr>
                    </template>
                </table-report>
            </loading-view>
        </card>
    </div>
</template>

