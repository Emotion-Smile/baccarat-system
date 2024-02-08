<template>
    <AppLayout :title="`Cockfight ${user.name}`">
        <div class="flex-1 space-y-3 md:flex md:space-x-3 md:space-y-0 lg:overflow-hidden">
            <div class="flex flex-col space-y-2 overflow-hidden md:flex-1">
                <div class="relative w-full">
                    <div class="flex divide-x">
                        <div
                            v-for="group in groups"
                            :key="group.id"
                            :class="[group.id === user.group_id ? activeBgGroup : '']"
                            class="inline-flex items-center justify-center flex-1 h-10 space-x-2 bg-white cursor-pointer"
                            @click="switchToGroup(group.id)"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="w-6 h-6"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                />
                            </svg>
                            <span>{{ group.name }}</span>
                        </div>
                    </div>

                    <div
                        v-if="marqueeSetting && marqueeSetting.status && marqueeSetting.text"
                        class="my-1"
                    >
                        <TextMarquee :speed="marqueeSetting.speed">
                            <span class="text-white">{{ marqueeSetting.text }}</span>
                        </TextMarquee>
                    </div>

                    <VideoStreaming
                        :user="user"
                        :fight-number="match.fight_number"
                        :streaming-name="currentGroup.streaming_name"
                        :streaming-logo="currentGroup.streaming_logo"
                    />
                </div>
                <!-- result symbol -->
                <MatchResultSymbol :symbols="tmpResultSymbol"/>
                <!--result count web-->
                <MatchResultCountWeb :result-count="tmpResultCount"/>
            </div>


            <div
                class="flex flex-col w-full space-y-2 overflow-hidden md:max-w-[280px] lg:max-w-sm 2xl:space-y-3 2xl:max-w-md 3xl:max-w-lg 4xl:max-w-screen-sm"
            >
                <div class="flex flex-col h-auto">
                    <div class="flex items-center justify-between mb-3 space-x-1 shrink-0 2xl:space-x-2">
                        <div
                            class="flex-1 overflow-hidden font-medium text-center text-white border-2 divide-y-2 border-border rounded-t-md divide-border"
                        >
                            <div class="py-1 bg-status-head">
                                {{ __('fight') }}#
                            </div>
                            <div class="flex items-center justify-center bg-status-body h-9">
                                {{ match.fight_number }}
                            </div>
                        </div>
                        <div
                            class="flex-1 overflow-hidden font-medium text-center text-white border-2 divide-y-2 border-border rounded-t-md divide-border"
                        >
                            <div class="py-1 bg-status-head">
                                {{ __('status') }}
                            </div>
                            <div class="flex items-center justify-center py-2 bg-status-body h-9">
                                <span
                                    :class="[match.bet_status === 'open' ? 'bg-win' : 'bg-lose']"
                                    class="inline-flex items-center h-6 px-4 py-2 font-medium rounded-full"
                                >
                                    {{ __(match.bet_status) }}
                                </span>
                            </div>
                        </div>
                        <div
                            class="flex-1 overflow-hidden font-medium text-center text-white border-2 divide-y-2 border-border rounded-t-md divide-border"
                        >
                            <div class="py-1 bg-status-head">
                                {{ __('winner') }}
                            </div>
                            <div class="flex items-center justify-center bg-status-body h-9">
                                {{ __(match.result) }}
                            </div>
                        </div>
                    </div>
                    <div
                        class="flex-1 overflow-hidden border-2 rounded-md border-border bg-channel-1"
                    >
                        <div class="flex divide-x divide-gray-400">
                            <div class="flex-1">
                                <div class="relative py-2 font-black text-center text-white uppercase bg-meron">
                                    <Firework v-if="match.result === 'Meron'"/>

                                    <div class="relative text-center">
                                        <div class="">
                                            {{ currentGroup.meron }}
                                        </div>

                                        <WinnerLabel v-if="match.result === 'Meron'"/>
                                    </div>
                                </div>
                                <div class="flex flex-col items-center px-2 py-3 space-y-3 text-center bg-bet-body">
                                    <!-- <div class="text-xl font-black">8,072.00</div>-->
                                    <div class="text-xl font-black text-bet-text">
                                        {{ match.meron_total_bet }}
                                    </div>
                                    <div
                                        class="inline-flex flex-col justify-center w-full py-0.5 border border-dashed rounded-full 2xl:w-32 text-meron border-meron"
                                    >
                                        <!--                                        <span class="text-xl font-black">0.81</span>-->
                                        <span class="font-black 2xl:text-2xl">{{ match.meron_payout }}</span>
                                        <span class="text-sm font-black uppercase">{{ __('payout') }}</span>
                                    </div>

                                    <div v-if="showYourBet">
                                        <div class="text-xl font-black">
                                            {{ memberTotalBet.meron }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            Your Bet
                                        </div>
                                    </div>

                                    <button
                                        type="button"
                                        :class="{ 'disabled:opacity-50' : match.disable_bet_button}"
                                        :disabled="match.disable_bet_button"
                                        class="uppercase space-x-2 inline-flex w-full font-black items-center justify-center px-3.5 py-2  text-sm leading-4  rounded-full shadow-sm text-white bg-gradient-to-b from-red-600 to-red-900  focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                        @click="betSubmit(1)"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="w-5 h-5 text-white"
                                            viewBox="0 0 20 20"
                                            fill="currentColor"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                        <span>{{ __('bet') }}</span>
                                    </button>
                                </div>
                            </div>

                            <div class="flex-1">
                                <div class="relative py-2 font-black text-center text-white uppercase bg-wala">
                                    <Firework v-if="match.result === 'Wala'"/>
                                    <div class="relative text-center">
                                        <div class="">
                                            {{ currentGroup.wala }}
                                        </div>
                                        <WinnerLabel v-if="match.result === 'Wala'"/>
                                    </div>
                                </div>
                                <div class="flex flex-col items-center px-2 py-3 space-y-3 text-center bg-bet-body">
                                    <div class="text-xl font-black text-bet-text">
                                        {{ match.wala_total_bet }}
                                    </div>
                                    <div
                                        class="inline-flex flex-col justify-center w-full py-0.5 border border-dashed rounded-full 2xl:w-32 text-wala border-wala"
                                    >
                                        <span class="font-black 2xl:text-2xl">{{ match.wala_payout }}</span>
                                        <span class="text-sm font-black uppercase">{{ __('payout') }}</span>
                                    </div>
                                    <div v-if="showYourBet">
                                        <div class="text-xl font-black">
                                            {{ memberTotalBet.wala }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            Your Bet
                                        </div>
                                    </div>
                                    <button
                                        type="button"
                                        :class="{ 'disabled:opacity-50' : match.disable_bet_button}"
                                        :disabled="match.disable_bet_button"
                                        class="uppercase space-x-2 inline-flex w-full font-black shadow-md items-center justify-center px-3.5 py-2 text-sm leading-4 rounded-full text-white bg-gradient-to-b from-blue-600 to-blue-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                        @click="betSubmit(2)"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="w-5 h-5 text-white"
                                            viewBox="0 0 20 20"
                                            fill="currentColor"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                        <span>{{ __('bet') }}</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- bet button-->
                        <!--                        <div class="p-2 space-y-2">-->
                        <!--                            <div class="font-black text-white">-->
                        <!--                                DRAW WINS x8. Max. DRAW bet per-->
                        <!--                            </div>-->
                        <!--                            <div class="flex space-x-3">-->
                        <!--                                <div class="flex-1">-->
                        <!--                                    <button type="button"-->
                        <!--                                            class="flex items-center justify-center w-full px-6 py-2 space-x-2 text-sm font-black text-white uppercase rounded-md bg-win">-->
                        <!--                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white"-->
                        <!--                                             viewBox="0 0 20 20"-->
                        <!--                                             fill="currentColor">-->
                        <!--                                            <path fill-rule="evenodd"-->
                        <!--                                                  d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"-->
                        <!--                                                  clip-rule="evenodd"/>-->
                        <!--                                        </svg>-->
                        <!--                                        <span>BET DRAW</span>-->
                        <!--                                    </button>-->
                        <!--                                </div>-->
                        <!--                                <div class="flex items-center justify-center flex-1 text-xl font-black text-white">0-->
                        <!--                                </div>-->
                        <!--                            </div>-->

                        <!--                        </div>-->

                        <div
                            :class="currentGroup.css_style"
                            class="p-2 space-y-4 font-black text-white uppercase lg:space-y-4 2xl:space-y-3 4xl:space-y-3"
                        >
                            <div class="flex items-center justify-between">
                                <span>{{ __('min') }}={{ user.minimum_bet_per_ticket }} - {{ __('max') }}={{
                                    user.maximum_bet_per_ticket
                                }}</span>
                                <span class="text-xs">{{ currentGroup.name }}</span>
                            </div>

                            <div class="flex mt-1 rounded-md shadow-shadow-lg">
                                <div class="relative flex items-stretch flex-grow focus-within:z-10">
                                    <input
                                        id="amount"
                                        v-model="betAmount"
                                        type="number"
                                        name="amount"
                                        class="block w-full text-black border-gray-300 rounded-none shadow-lg rounded-l-md focus:ring-win focus:border-win sm:text-sm"
                                        placeholder="ENTER AMOUNT"
                                        min="1"
                                        max="100"
                                    >
                                </div>
                                <button
                                    type="button"
                                    class="relative inline-flex items-center px-6 py-2 4xl:py-2.5 -ml-px space-x-2 text-sm font-black text-white uppercase bg-win rounded-r-md text-md hover:bg-green-600 focus:outline-none focus:ring-1 focus:ring-win focus:border-win"
                                    @click="clearBetValue"
                                >
                                    {{ __('clear') }}
                                </button>
                            </div>
                            <div class="grid grid-cols-4 gap-2 md:grid-cols-2 lg:grid-cols-4 2xl:gap-3 4xl:gap-3">
                                <button
                                    v-for="(betValue, index) in betValueRange"
                                    :key="betValue.key"
                                    type="button"
                                    class="btn-price 4xl:py-2.5"
                                    @click="betValueButton(index)"
                                >
                                    {{ betValue.value }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ticket report-->
                <div class="flex items-center py-2 bg-navbar justify-evenly">
                    <Link
                        class="cursor-pointer text-navbar-item-text"
                        href="/member"
                        :only="['betRecords']"
                        preserve-state
                        preserve-scroll
                        :class="{ 'text-[#cd943b]': $page.url === '/member' }"
                    >
                        {{ __('current_bet') }}
                    </Link>
                    <Link
                        class="cursor-pointer text-navbar-item-text"
                        href="/member?todayReport=true"
                        :only="['betRecords']"
                        preserve-state
                        preserve-scroll
                        :class="{ 'text-[#cd943b]': $page.url.startsWith('/member?todayReport') }"
                    >
                        {{ __('today_report') }}
                    </Link>
                </div>

                <LiveTicket :bet-records="tmpBetRecords"/>
                <!-- ticket report -->
            </div>

            <!-- Mobile show result count-->
            <MatchResultSymbolMobile
                :symbols="tmpResultSymbol"
                :result-count="tmpResultCount"
            />
            <!-- end mobile -->
        </div>
    </AppLayout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import LiveTicket from '@/Shared/LiveTicket.vue';
import {computed, onMounted, reactive, ref, watchEffect} from 'vue';
import {Inertia} from '@inertiajs/inertia';
import MatchResultCountWeb from '@/Shared/MatchResultCountWeb';
import MatchResultSymbolMobile from '@/Shared/MatchResultSymbolMobile';
import MatchResultSymbol from '@/Shared/MatchResultSymbol';
import {Link} from '@inertiajs/inertia-vue3';
import Firework from '@/Shared/Firework';
import WinnerLabel from '@/Shared/WinnerLabel';
import VideoStreaming from '@/Shared/VideoStreaming';
import TextMarquee from '@/Shared/TextMarquee';
import {useSweetAlert} from '@/Functions/useSweetAlert';
import {getTotalBetOfCurrentMatch, switchToGroup} from '@/Functions/useMemberAction';

export default {
    name: 'Cockfight',
    components: {
        VideoStreaming,
        WinnerLabel,
        Firework,
        MatchResultSymbol,
        MatchResultSymbolMobile,
        MatchResultCountWeb,
        TextMarquee,
        AppLayout,
        LiveTicket,
        Link
    },
    props: {
        user: Object,
        betValueRange: Array,
        matchInfo: Object,
        resultCount: Object,
        betRecords: Object,
        errors: Object,
        resultSymbols: Array,
        betConfiguration: Object,
        groups: Object,
        marqueeSetting: Object
    },
    setup(props) {
        const match = computed(() => props.matchInfo);

        let tmpResultCount = ref(props.resultCount);
        let tmpResultSymbol = ref(props.resultSymbols);
        let tmpBetRecords = ref(props.betRecords);

        let timeout = null;
        let betAmount = ref(0);
        const SweetAlert = useSweetAlert();

        let memberTotalBet = reactive({
            meron: 0,
            wala: 0
        });

        let showOnlyOwnBet = computed(() => props.betConfiguration.show_only_own_bet);
        let showYourBet = computed(() => props.betConfiguration.show_your_bet);
        const currentGroup = computed(() => props.groups.filter((group) => group.id === props.user.group_id)[0]);

        let activeBgGroup = currentGroup.value.css_style + ' text-white';

        watchEffect(() => {
            if (props.betRecords) {
                tmpBetRecords.value = props.betRecords;
            }
        });

        onMounted(() => {

            Echo.channel(`user.${props.user.environment_id}`)
                .listen(`.totalBet.refresh.${props.user.id}`, payload => {
                    match.value.meron_total_bet = payload.meron;
                    match.value.wala_total_bet = payload.wala;
                    reloadData(['betRecords']);
                });

            Echo.channel(`match.${props.user.environment_id}.${props.user.group_id}`)
                .listen('.created', payload => {
                    memberTotalBet.meron = 0;
                    memberTotalBet.wala = 0;
                    refreshMatchInfo(payload.match);
                })
                .listen('.updated', payload => {
                    refreshMatchInfo(payload.match);
                })
                .listen('.payout.updated', payload => {
                    refreshPayout(payload.match);
                })
                .listen('.bet.opened', payload => {
                    if ((payload.match.memberType === undefined) && props.user.normal_member) {
                        refreshBetStatus(payload.match);
                    } else {
                        if (props.user.current_team_id === payload.match.memberType) {
                            refreshBetStatus(payload.match);
                        }
                    }
                })
                .listen('.bet.closed', payload => {
                    if (props.user.current_team_id === payload.match.memberType) {
                        refreshBetStatus(payload.match);
                    }

                    if (payload.match.memberType === undefined) {
                        refreshBetStatus(payload.match);
                    }

                })
                .listen('.endedResultSummary', payload => {
                    tmpResultCount.value = payload.match.count;
                    tmpResultSymbol.value = payload.match.symbol;
                })
                .listen('.ended', payout => {

                    refreshMatchInfo(payout.match);

                    tmpBetRecords.value = [];

                    clearTimeout(timeout);

                    timeout = setTimeout(() => {
                        memberTotalBet.meron = 0;
                        memberTotalBet.wala = 0;

                        reloadMatchInfo();

                    }, 5000);

                });


            getTotalBetCurrentMatch();

            Echo.connector.pusher.connection.bind('state_change', function (states) {
                // states = {previous: 'oldState', current: 'newState'}
                console.log('states', states);
            });

            Echo.connector.pusher.connection.bind('error', function (error) {
                // states = {previous: 'oldState', current: 'newState'}
                console.log('error', error);
            });

        });


        const reloadData = (data) => {
            Inertia.reload({
                only: data,
                preserveState: true,
                preserveScroll: true
            }
            );
        };
        const refreshPayout = (matchInfo) => {
            match.value.meron_payout = matchInfo.meron_payout;
            match.value.wala_payout = matchInfo.wala_payout;
        };

        const refreshBetStatus = (matchInfo) => {
            match.value.bet_status = matchInfo.bet_status;
            match.value.disable_bet_button = matchInfo.disable_bet_button;
        };

        const refreshMatchInfo = (matchInfo) => {

            if (showOnlyOwnBet.value) {
                matchInfo.meron_total_bet = memberTotalBet.meron;
                matchInfo.wala_total_bet = memberTotalBet.wala;
            }

            match.value.bet_closed = matchInfo.bet_closed;
            match.value.bet_opened = matchInfo.bet_opened;
            match.value.bet_status = matchInfo.bet_status;
            match.value.disable_bet_button = matchInfo.disable_bet_button;
            match.value.environment_id = matchInfo.environment_id;
            match.value.fight_number = matchInfo.fight_number;
            match.value.group_id = matchInfo.group_id;
            match.value.id = matchInfo.id;
            match.value.match_end = matchInfo.match_end;
            match.value.meron_payout = matchInfo.meron_payout;
            match.value.meron_total_bet = matchInfo.meron_total_bet;
            match.value.wala_total_bet = matchInfo.wala_total_bet;
            match.value.payout_adjusted = matchInfo.payout_adjusted;
            match.value.result = matchInfo.result;
            match.value.status = matchInfo.status;
            match.value.total_ticket = matchInfo.total_ticket;
            match.value.wala_payout = matchInfo.wala_payout;

        };

        const reloadMatchInfo = () => {

            let resetMatch = {
                bet_closed: false,
                bet_opened: false,
                bet_status: 'Close',
                disable_bet_button: true,
                environment_id: props.user.environment_id,
                fight_number: '#',
                group_id: undefined,
                id: 0,
                match_end: false,
                meron_payout: '#.##',
                meron_total_bet: 0,
                payout_adjusted: undefined,
                result: 'None',
                status: 'close',
                total_ticket: 0,
                wala_payout: '#.##',
                wala_total_bet: 0,
            };

            refreshMatchInfo(resetMatch);

        };

        async function getTotalBetCurrentMatch() {
            const {meron, wala} = await getTotalBetOfCurrentMatch();
            memberTotalBet.wala = wala;
            memberTotalBet.meron = meron;
        }

        const betValueButton = (index) => {
            let bet = props.betValueRange[index];
            betAmount.value += bet.key;
        };

        const clearBetValue = () => {
            betAmount.value = 0;
        };

        const betSubmit = (betOn) => {
            const bet = betOn === 1 ? 'Meron' : 'Wala';
            SweetAlert.fire({
                title: `Do you want to bet: \n ${bet} ${betAmount.value.toLocaleString('en-US')} ? `,
                showCancelButton: true,
                cancelButtonText: 'No',
                confirmButtonText: 'Yes',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return axios.post(route('member.match.betting'), {
                        betAmount: betAmount.value,
                        betOn: betOn
                    })
                        .then(response => {
                            const {type, message} = response.data;

                            if (type === 'error') {
                                throw new Error(message);
                            }
                        })
                        .catch(error => {
                            const errorMessage = error.toString().replace('Error: ', '');
                            if (errorMessage === 'group_disabled') {
                                window.location.reload();
                                return false;
                            }
                            SweetAlert.showValidationMessage(
                                `${errorMessage}`
                            );
                        });
                },
                allowOutsideClick: () => !SweetAlert.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    clearBetValue();
                    SweetAlert.fire({
                        icon: 'success',
                        title: 'Betting successes',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        };


        return {
            betValueButton,
            clearBetValue,
            betSubmit,
            betAmount,
            match,
            memberTotalBet,
            showYourBet,
            currentGroup,
            switchToGroup,
            activeBgGroup,
            tmpResultCount,
            tmpResultSymbol,
            tmpBetRecords
        };
    }
};
</script>
