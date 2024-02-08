<template>
    <app-layout title="Tickets">
        <div class="flex-1 space-y-3 lg:flex lg:space-x-3 lg:space-y-0 lg:overflow-hidden">
            <div class="flex flex-col space-y-2 overflow-hidden lg:flex-1 bg-white">
                <div class="flex justify-between px-3 py-3 bg-white">
                    <div class="text-xl font-medium text-wala">
                        Ticket Request
                    </div>
                    <div class="text-black uppercase">
                        Total ticket: {{ totalTickets ?? 0 }}
                    </div>
                </div>
                <div class="w-full px-2 sm:px-0">
                    <TabGroup>
                        <TabList class="flex divide-x divide-gray-400 bg-fill">
                            <Tab
                                v-for="ticket in Object.keys(tickets)"
                                :key="ticket"
                                v-slot="{ selected }"
                                as="template"
                            >
                                <button
                                    :class="[
                    'w-auto uppercase py-2.5 px-5 text-sm leading-5 font-medium',
                    'focus:outline-none focus:ring-0 ring-offset-2 ring-white ring-opacity-60',
                    selected
                      ? 'bg-black text-white shadow'
                      : 'text-gray-500 hover:bg-white/[0.12] hover:text-white',
                  ]"
                                >
                                    {{ ticket }} <span
                                    class="inline-flex items-center justify-center w-6 h-6 text-xs text-white rounded-full bg-meron"
                                >{{
                                        tickets[ticket].length
                                    }}</span>

                                    <div>({{ totalBetByMemberType[ticket].total }})</div>
                                </button>
                            </Tab>
                        </TabList>

                        <TabPanels class="">
                            <TabPanel
                                v-for="(ticket, idx) in Object.values(tickets)"
                                :key="idx"
                                :class="[
                  'bg-white rounded-xl',
                  'focus:outline-none focus:ring-2 ring-offset-2 ring-offset-blue-400 ring-white ring-opacity-60',
                ]"
                            >
                                <div class="flex flex-col">
                                    <div class="overflow-x-auto">
                                        <div class="inline-block min-w-full align-middle">
                                            <div class="overflow-hidden">
                                                <table class="min-w-full">
                                                    <thead class="flex bg-black">
                                                    <tr class="flex items-center w-full">
                                                        <th
                                                            scope="col"
                                                            class="px-3 w-28 py-1.5 text-xs font-medium tracking-wider text-center text-white uppercase"
                                                        >
                                                            Fight#
                                                        </th>
                                                        <th
                                                            scope="col"
                                                            class="px-3 py-1.5 w-28 text-center text-xs font-medium tracking-wider text-white uppercase"
                                                        >
                                                            Time
                                                        </th>
                                                        <th
                                                            scope="col"
                                                            class="px-3 w-28 py-1.5 text-xs font-medium tracking-wider text-center text-white uppercase"
                                                        >
                                                            IP Address
                                                        </th>
                                                        <th
                                                            scope="col"
                                                            class="px-3 py-1.5 w-28 text-xs font-medium tracking-wider text-center text-white uppercase"
                                                        >
                                                            Member
                                                        </th>
                                                        <th
                                                            scope="col"
                                                            class="px-3 py-1.5 w-20 text-xs font-medium tracking-wider text-center text-white uppercase"
                                                        >
                                                            Bet
                                                        </th>
                                                        <th
                                                            scope="col"
                                                            class="px-3 py-1.5 w-36 text-xs font-medium tracking-wider text-center text-white uppercase"
                                                        >
                                                            Amount
                                                        </th>
                                                        <th class=""/>
                                                    </tr>
                                                    </thead>
                                                    <tbody
                                                        class="flex flex-col items-center w-full h-[50vh] lg:h-[74vh] xl:h-[80vh] overflow-y-scroll"
                                                    >
                                                    <tr
                                                        v-for="(ticketType, ticketId) in ticket"
                                                        :key="ticketType.id"
                                                        class="flex items-center w-full border-b-2"
                                                    >
                                                        <td class="px-3 py-2 w-28 text-sm text-center text-gray-900">
                                                            {{ ticketType.fight_number }}-{{ ticketType.id }}
                                                        </td>
                                                        <td class=" px-3 py-2 w-28 text-sm text-center text-gray-500 whitespace-nowrap">
                                                            {{ ticketType.bet_time }}
                                                        </td>
                                                        <td class="px-3 py-2 text-sm text-center text-gray-500 w-28 whitespace-nowrap">
                                                            {{ ticketType.country_code }} - {{ ticketType.ip }}
                                                        </td>
                                                        <td class="w-28 px-3 py-2 text-sm text-center text-gray-500 whitespace-nowrap">
                                                            {{ ticketType.member }}
                                                        </td>
                                                        <td
                                                            class="w-20 px-3 py-2 text-sm text-center capitalize whitespace-nowrap"
                                                            :class="['text-' + ticketType.bet_on]"
                                                        >
                                                            {{ ticketType.bet_on }}
                                                        </td>
                                                        <td class="px-3 py-2 text-sm text-gray-500 w-36 whitespace-nowrap">
                                                            {{ ticketType.amount }}
                                                        </td>
                                                        <!--                              <td-->
                                                        <!--                                class="flex items-center px-3 py-2 space-x-3 text-sm font-medium text-right whitespace-nowrap"-->
                                                        <!--                              >-->
                                                        <!--                                <template v-if="ticketType.type==='AUTO_ACCEPT'">-->
                                                        <!--                                  <a-->
                                                        <!--                                    href="#"-->
                                                        <!--                                    class="block px-5 py-1 text-white rounded-full bg-yellow-500"-->
                                                        <!--                                    @click.prevent="updateTicketType(ticketType.id, 2)"-->
                                                        <!--                                  >Check</a>-->

                                                        <!--                                  <a-->
                                                        <!--                                    href="#"-->
                                                        <!--                                    class="block px-5 py-1 text-white rounded-full bg-lose"-->
                                                        <!--                                    @click.prevent="updateTicketType(ticketType.id, 3)"-->
                                                        <!--                                  >Suspect</a>-->
                                                        <!--                                </template>-->

                                                        <!--                                <template v-else-if="ticketType.type==='CHECK'">-->
                                                        <!--                                  <a-->
                                                        <!--                                    href="#"-->
                                                        <!--                                    class="block px-5 py-1 text-white rounded-full bg-blue-600"-->
                                                        <!--                                    @click.prevent="updateTicketType(ticketType.id, 1)"-->
                                                        <!--                                  >-->
                                                        <!--                                    Accept-->
                                                        <!--                                  </a>-->

                                                        <!--                                  <a-->
                                                        <!--                                    href="#"-->
                                                        <!--                                    class="block px-5 py-1 text-white rounded-full bg-lose"-->
                                                        <!--                                    @click.prevent="updateTicketType(ticketType.id, 3)"-->
                                                        <!--                                  >Suspect</a>-->
                                                        <!--                                </template>-->

                                                        <!--                                <template v-else-if="ticketType.type==='SUSPECT'">-->
                                                        <!--                                  <a-->
                                                        <!--                                    href="#"-->
                                                        <!--                                    class="block px-5 py-1 text-white rounded-full bg-blue-600"-->
                                                        <!--                                    @click.prevent="updateTicketType(ticketType.id, 1)"-->
                                                        <!--                                  >-->
                                                        <!--                                    Accept-->
                                                        <!--                                  </a>-->
                                                        <!--                                </template>-->
                                                        <!--                              </td>-->
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </TabPanel>
                        </TabPanels>
                    </TabGroup>
                </div>
            </div>
            <div
                class="flex flex-col w-full space-y-2 overflow-hidden lg:max-w-sm 2xl:space-y-3 2xl:max-w-md 3xl:max-w-lg 4xl:max-w-screen-sm"
            >
                <div class="overflow-hidden border-2 rounded-lg border-border backdrop-filter backdrop-blur-xl">
                    <div class="flex items-center justify-between px-3 py-3 bg-white">
                        <div class="font-bold uppercase">
                            fight# {{ matchData.fightNumber }}
                        </div>
                        <div class="space-x-3 font-bold uppercase text-win">
              <span
                  :class="[matchData.betStatus === 'open' ? 'text-win' : 'text-lose' ]"
                  class="text-xs uppercase lg:text-sm"
              >[ {{
                      group.name
                  }} ] bet status: {{ matchData.betStatus }}</span>
                        </div>
                        <div class="inline-flex space-x-2">
                            <svg
                                aria-hidden="true"
                                focusable="false"
                                data-prefix="fas"
                                data-icon="stopwatch"
                                class="w-5 h-5"
                                role="img"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 448 512"
                            >
                                <path
                                    fill="currentColor"
                                    d="M432 304c0 114.9-93.1 208-208 208S16 418.9 16 304c0-104 76.3-190.2 176-205.5V64h-28c-6.6 0-12-5.4-12-12V12c0-6.6 5.4-12 12-12h120c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12h-28v34.5c37.5 5.8 71.7 21.6 99.7 44.6l27.5-27.5c4.7-4.7 12.3-4.7 17 0l28.3 28.3c4.7 4.7 4.7 12.3 0 17l-29.4 29.4-.6.6C419.7 223.3 432 262.2 432 304zm-176 36V188.5c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12V340c0 6.6 5.4 12 12 12h40c6.6 0 12-5.4 12-12z"
                                />
                            </svg>
                            <span>{{ betDuration }}</span>
                        </div>
                    </div>
                    <div class="p-2">
                        <div class="flex space-x-2">
                            <div class="flex-1 border border-meron">
                                <div class="py-2 font-black text-center text-white uppercase bg-meron">
                                    Meron
                                </div>
                                <div
                                    class="flex flex-col items-center justify-center px-3 py-3 space-y-3 text-center bg-white 2xl:h-48"
                                >
                                    <div class="text-xl font-black">
                                        {{ matchData.meronTotalBet }}
                                    </div>
                                    <div
                                        class="inline-flex flex-col justify-center w-32 py-1 border border-dashed rounded-full text-meron border-meron"
                                    >
                                        <span class="text-xl font-black">{{ matchData.meronPayout }}</span>
                                        <span class="text-xs font-black uppercase">Payout</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-1 border border-wala">
                                <div class="py-2 font-black text-center text-white uppercase bg-wala">
                                    Wala
                                </div>
                                <div
                                    class="flex flex-col items-center justify-center px-3 py-3 space-y-3 text-center bg-white 2xl:h-48"
                                >
                                    <div class="text-xl font-black">
                                        {{ matchData.walaTotalBet }}
                                    </div>
                                    <div
                                        class="inline-flex flex-col justify-center w-32 py-1 border border-dashed rounded-full text-wala border-wala"
                                    >
                                        <span class="text-xl font-black">{{ matchData.meronPayout }}</span>
                                        <span class="text-xs font-black uppercase">Payout</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="h-full max-h-24 bg-white">
                    <div class="py-2 text-center text-white uppercase bg-black">
                        Win/Loss Today
                    </div>
                    <div class="py-3 text-2xl font-medium items-center text-center uppercase">
                        {{ todayWinLoss }}
                    </div>
                </div>
                <MatchBenefit :benefit="estimateBenefit"/>
            </div>
        </div>
    </app-layout>
</template>

<script>
import {onMounted, onUpdated, reactive, ref, watch} from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import MatchBenefit from '@/Shared/MatchBenefit';
import WinLossToday from '@/Shared/WinLossToday';
import {Switch, Tab, TabGroup, TabList, TabPanel, TabPanels} from '@headlessui/vue';
import {Inertia} from '@inertiajs/inertia';
import {reloadData} from '@/Functions/useTraderAction';
import {toMatchInfo} from '@/Functions/useDto';

export default {
    name: 'Index',
    components: {
        AppLayout,
        TabGroup,
        TabList,
        Tab,
        TabPanels,
        TabPanel,
        MatchBenefit,
        WinLossToday,
        Switch
    },
    props: {
        tickets: Object,
        user: Object,
        matchInfo: Object,
        benefit: Object,
        todayWinLoss: String | Number,
        errors: Object,
        group: Object,
        ticketHeader: Object
    },
    setup(props) {


        const matchData = reactive(toMatchInfo(props.matchInfo));
        let tickets = reactive(props.tickets);

        let totalTickets = ref(matchData.totalTicket);
        let estimateBenefit = reactive(props.benefit);

        let betDurationInterval = undefined;
        let betDuration = ref(40);

        const ticketHeader = props.ticketHeader;

        const totalBetByMemberType = ref({});
        makeDefaultTotalBetByMemberBetType();

        onMounted(() => {
            Echo.channel(`match.trader.${props.user.environment_id}.${props.user.group_id}`)
                .listen('.bet.created', payload => {

                    totalTickets.value += 1;

                    let memberTicketType = payload.data.member_type_id;

                    if (memberTicketType == null) {
                        memberTicketType = 'general';
                    }

                    const type = ticketHeader[memberTicketType];

                    tickets[type].unshift(payload.data);

                })
                .listen('.summary', payload => {
                    const summary = payload.match;
                    matchData.meronTotalBet = summary.meron_total_bet;
                    matchData.walaTotalBet = summary.wala_total_bet;

                    estimateBenefit.meron_benefit = summary.meron_benefit;
                    estimateBenefit.wala_benefit = summary.wala_benefit;

                    let totalBetByType = 0;

                    if (summary.totalBetByMemberType[summary.ticketMemberType] !== undefined) {
                        totalBetByType = summary.totalBetByMemberType[summary.ticketMemberType];
                    }

                    totalBetByMemberType.value[ticketHeader[summary.ticketMemberType]] = totalBetByType;
                });

            Echo.channel(`match.${props.user.environment_id}.${props.user.group_id}`)
                .listen('.created', payload => {
                    clearTicket();
                    refreshMatchInfo(payload.match);
                })
                .listen('.bet.opened', payload => {
                    startBetDuration();
                    matchData.betOpened = true;
                    matchData.betStatus = payload.match.bet_status;
                })
                .listen('.bet.closed', payload => {
                    stopBetDuration();
                    matchData.betStatus = payload.match.bet_status;
                })
                .listen('.payout.updated', payload => {
                    matchData.meronPayout = payload.match.meron_payout;
                    matchData.walaPayout = payload.match.wala_payout;
                })
                .listen('.ended', payout => {
                    estimateBenefit.meron_benefit = 0;
                    estimateBenefit.wala_benefit = 0;
                    clearTicket();
                    stopBetDuration();
                    reloadData(['matchInfo'], true);
                });
        });

        onUpdated(() => {

            Object.keys(props.tickets).forEach(function (key) {
                tickets[key] = props.tickets[key];
            });

        });

        function refreshMatchInfo(matchInfo) {
            Object.assign(matchData, toMatchInfo(matchInfo));
        }

        watch(() => props.matchInfo, (old, current) => {
            refreshMatchInfo(current);
        });

        function makeDefaultTotalBetByMemberBetType() {
            Object.values(ticketHeader).forEach(function (item) {
                totalBetByMemberType.value[item] = 0;
            })
        }

        const clearTicket = () => {

            // tickets.AUTO_ACCEPT = [];
            // tickets.CHECK = [];
            // tickets.SUSPECT = [];

            totalTickets.value = 0;

            makeDefaultTotalBetByMemberBetType();
            Object.keys(tickets).forEach(function (key) {
                tickets[key] = [];
            });

        };

        const stopBetDuration = () => {
            clearInterval(betDurationInterval);
            betDuration.value = 40;
        };

        const startBetDuration = () => {
            clearInterval(betDurationInterval);
            betDurationInterval = setInterval(() => {
                betDuration.value -= 1;
            }, 1000);
        };

        const updateTicketType = (id, betType) => {
            Inertia.put(route('ticket.update-type'), {
                    id: id,
                    betType: betType
                }, {
                    preserveScroll: true,
                    preserveState: true,
                }
            );
        };
        return {
            tickets,
            estimateBenefit,
            betDuration,
            totalTickets,
            updateTicketType,
            matchData,
            totalBetByMemberType
        };
    },
};
</script>

<style>

</style>
