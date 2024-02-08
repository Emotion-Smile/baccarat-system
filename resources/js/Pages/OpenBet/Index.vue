<template>
    <app-layout title="Open Bet">
        <div class="flex-1 space-y-3 md:flex md:space-x-3 md:space-y-0 lg:overflow-hidden 2xl:overflow-auto">
            <div class="flex flex-col space-y-4 overflow-hidden md:flex-1">
                <div
                    id="capture"
                    class="w-full relative"
                >
                    <VideoStreaming
                        :user="user"
                        :fight-number="matchData.fightNumber"
                        :streaming-name="group.streaming_name"
                        :streaming-logo="group.streaming_logo"
                    />
                </div>

                <div class="hidden w-full space-x-3 md:flex">
                    <MatchResultSymbolTrader :symbols="tmpResultSymbol"/>
                    <MatchResultCountTrader :result-count="tmpResultCount"/>
                </div>


                <SubmitResult
                    :is-live="matchData.isLive"
                    :end-the-match="endTheMatch"
                />
            </div>
            <div
                class="flex flex-col w-full space-y-2 overflow-hidden md:max-w-[320px] lg:max-w-md 2xl:space-y-3 2xl:max-w-lg 3xl:max-w-screen-sm 4xl:max-w-screen-sm"
            >
                <div
                    class="overflow-hidden border-4 rounded-md bg-gradient-to-br from-red-900 to-red-700 border-border md:min-h-[28rem] lg:min-h-[21rem] 2xl:min-h-[28rem]"
                >
                    <div class="flex items-center justify-between h-10 px-3 font-bold bg-white md:px-1 lg:px-3">
                        <div>
                            <small>FIGHT#</small> <span>  {{ matchData.fightNumber }}</span>
                        </div>


                        <div class="inline-flex items-center space-x-3">
                            <Switch
                                v-model="betOpened"
                                :disabled="!matchData.isLive"
                                :class="[matchData.betStatus === 'open' ? 'bg-win' : 'bg-lose', 'relative inline-flex shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none']"
                            >
                <span
                    aria-hidden="true"
                    :class="[matchData.betStatus === 'open' ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200']"
                />
                            </Switch>

                            <span
                                :class="[matchData.betStatus === 'open' ? 'text-win' : 'text-lose' ]"
                                class="text-xs uppercase lg:text-sm"
                            >
                [ {{ group.name }} ]  bet status: {{ matchData.betStatus }}
              </span>
                        </div>

                        <div class="inline-flex items-center text-xs lg:text-sm">
                            <ClockIcon
                                class="w-5 h-5 mr-1 -ml-1"
                                aria-hidden="true"
                            />
                            <span>{{ matchDuration }} </span>
                        </div>
                    </div>

                    <div class="flex p-1 space-x-2 md:p-2 2xl:px-3 2xl:pt-3 2xl:space-x-3">
                        <div class="flex-1">
                            <div
                                class="py-2 font-black text-center text-white uppercase bg-meron"
                            >
                                Meron
                            </div>
                            <div
                                class="flex flex-col items-center p-1 text-center bg-white justify-evenly lg:p-2 2xl:h-40"
                            >
                                <div class="text-xl font-black">
                                    {{ matchData.meronTotalBet }}
                                </div>
                                <div
                                    class="inline-flex flex-col justify-center w-full py-1 border border-dashed rounded-full 2xl:w-32 text-meron border-meron"
                                >
                                    <span class="text-xl font-black">{{ matchData.meronPayout }}</span>
                                    <span class="text-xs font-black uppercase">Payout</span>
                                </div>
                                <button
                                    v-if="matchData.isLive && betOpened"
                                    :disabled="meronButtonState"
                                    class="bg-meron hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 focus:ring-offset-slate-50 text-white font-semibold h-10 px-6 rounded-lg w-full flex items-center justify-center sm:w-auto"
                                    :class="{'opacity-50': meronButtonState}"
                                    @click="requestDisableBet(1)"
                                >
                                    {{ isMeronBetEnable ? 'Enable Bet' : 'Disable Bet' }}
                                </button>
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="py-2 font-black text-center text-white uppercase bg-wala">
                                Wala
                            </div>
                            <div
                                class="flex flex-col items-center p-1 text-center bg-white justify-evenly lg:p-2 2xl:h-40"
                            >
                                <div class="text-xl font-black">
                                    {{ matchData.walaTotalBet }}
                                </div>
                                <div
                                    class="inline-flex flex-col justify-center w-full py-1 border border-dashed rounded-full 2xl:w-32 text-wala border-wala"
                                >
                                    <span class="text-xl font-black">{{ matchData.walaPayout }}</span>
                                    <span class="text-xs font-black uppercase">Payout</span>
                                </div>

                                <button
                                    v-if="matchData.isLive && betOpened"
                                    :disabled="walaButtonState"
                                    class="bg-wala hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 focus:ring-offset-slate-50 text-white font-semibold h-10 px-6 rounded-lg w-full flex items-center justify-center sm:w-auto"
                                    :class="{'opacity-50': walaButtonState}"
                                    @click="requestDisableBet(2)"
                                >
                                    {{ isWalaBetEnable ? 'Enable Bet' : 'Disable Bet' }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <CreateNewMatchAndAdjustPayout
                        v-model:total-payout.number="totalPayout"
                        v-model:payout.number="payout"
                        :errors="errors"
                        :payout-increase-and-decrease-value="payoutIncreaseAndDecreaseValue"
                        :match-is-live="matchData.isLive"
                        :submit-new-match="submitNewMatch"
                    />
                </div>

                <!--                <WinLossToday :today-win-loss="todayWinLoss"/>-->

                <div class="flex gap-3">
                    <div v-for="type in types" class="w-full bg-white pb-3">
                        <div class="py-2 text-center text-white uppercase bg-black">
                            {{ type.name }} ({{ type.status }})
                        </div>
                        <div
                            class="py-2 text-center text-white uppercase divide-x font-bold text-lg flex w-full items-center justify-center ">
                            <span class="text-meron flex-1">{{ type.meron }}</span>
                            <span class="text-wala flex-1">{{ type.wala }}</span>
                        </div>

                        <div v-if="matchData.isLive && betOpened" class="px-3">
                            <button @click="toggleBetMemberType(type, true)" type="button"
                                    class="bg-orange-500 text-white  py-1 px-6 rounded-lg w-full flex items-center justify-center">
                                Enable
                            </button>

                            <button @click="toggleBetMemberType(type, false)" type="button"
                                    class="bg-gray-500 mt-2 text-white  py-1 px-6 rounded-lg w-full flex items-center justify-center">
                                Disable
                            </button>
                        </div>
                    </div>
                </div>
                <MatchBenefit :benefit="estimateBenefit"/>
            </div>
            <div class="space-y-4 md:hidden">
                <MatchResultSymbolTrader :symbols="tmpResultSymbol"/>
                <MatchResultCountTrader :result-count="tmpResultCount"/>
            </div>
        </div>
    </app-layout>
</template>

<script>
import {defineComponent, onMounted, reactive, ref, watch} from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import {Dialog, DialogOverlay, DialogTitle, Switch, TransitionChild, TransitionRoot,} from '@headlessui/vue';
import {ClockIcon, ExclamationCircleIcon} from '@heroicons/vue/solid';
import {Inertia} from '@inertiajs/inertia';
import MatchResultCountTrader from '@/Shared/MatchResultCountTrader';
import MatchResultSymbolTrader from '@/Shared/MatchResultSymbolTrader';
import MatchBenefit from '@/Shared/MatchBenefit';
import WinLossToday from '@/Shared/WinLossToday';
import VideoStreaming from '@/Shared/VideoStreaming';
import MatchResultCountWeb from '@/Shared/MatchResultCountWeb';
import {startNewMatch} from '@/Functions/useTraderAction';
import {confirmDialog, createNewMatchConfirmDialog} from '@/Functions/useHelper';
import {toMatchInfo} from '@/Functions/useDto';
import CreateNewMatchAndAdjustPayout from '@/Shared/CreateNewMatchAndAdjustPayout';
import SubmitResult from '@/Shared/SubmitResult';
import Swal from 'sweetalert2';

const components = {
    MatchResultCountWeb,
    VideoStreaming,
    WinLossToday,
    MatchBenefit,
    MatchResultSymbolTrader,
    MatchResultCountTrader,
    AppLayout,
    Switch,
    ClockIcon,
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogOverlay,
    DialogTitle,
    ExclamationCircleIcon,
    CreateNewMatchAndAdjustPayout,
    SubmitResult
};

export default defineComponent({
    name: 'Index',
    components: components,
    props: {
        user: Object,
        group: Object,
        payoutIncreaseAndDecreaseValue: Array,
        resultCount: Object,
        matchInfo: Object,
        errors: Object,
        benefit: Object,
        todayWinLoss: String || Number,
        resultSymbols: Array,
        isWalaBetEnable: Boolean,
        isMeronBetEnable: Boolean,
        memberTypes: Object,
        memberTypeMapIdToName: Object
    },

    setup(props) {

        const matchData = reactive(toMatchInfo(props.matchInfo));
        const types = ref(props.memberTypes);
        const betOpened = ref(matchData.betOpened);

        let requestOpenBetFailed = false;
        let totalPayout = ref(184);
        let payout = ref(92);
        let betDuration = ref(40);
        let betDurationInterval = null;
        let manualFightNumber = '';

        let estimateBenefit = reactive(props.benefit);
        let showConfirmDialogOpenBet = true;

        let tmpResultCount = ref(props.resultCount);
        let tmpResultSymbol = ref(props.resultSymbols);

        let cancelRequestTimer = undefined;

        const submitNewMatch = async () => {

            let confirmResult = {
                value: '',
                isConfirmed: true
            };

            if (!matchData.isLive) {
                confirmResult = await createNewMatchConfirmDialog();
                manualFightNumber = confirmResult.value;
            }

            startNewMatch(
                totalPayout.value,
                payout.value,
                manualFightNumber,
                confirmResult.isConfirmed
            );
        };

        watch(totalPayout, (current, old) => {
            payout.value = current / 2;
        });


        watch(betOpened, async (current, old) => {

            if (!requestOpenBetFailed) {

                let text = current ? 'Do you want to open betting?' : 'Do you want to close betting?';

                if (!showConfirmDialogOpenBet) {
                    return;
                }

                const result = await confirmDialog(text);

                if (!result.isConfirmed) {
                    betOpened.value = old;
                    requestOpenBetFailed = true;
                }

                Inertia.post(route('match.toggle-bet'), {
                    betStatus: current
                }, {
                    only: ['flash'],
                    preserveScroll: true,
                    preserveState: true,
                    onBefore: () => {
                        return result.isConfirmed;
                    },
                    onError: (errors) => {
                        betOpened.value = old;
                        requestOpenBetFailed = true;
                    },
                    onSuccess: (page) => {
                        if (page.props.flash.type === 'error') {
                            betOpened.value = old;
                            requestOpenBetFailed = true;
                        }
                    }
                });

            } else {
                requestOpenBetFailed = false;
            }

        });

        const startBetDuration = () => {
            clearInterval(betDurationInterval);
            betDurationInterval = setInterval(() => {
                betDuration.value -= 1;
            }, 1000);
        };

        const stopBetDuration = () => {
            clearInterval(betDurationInterval);
            betDuration.value = 40;
        };

        async function endTheMatch(text, result) {

            let message = `Do you want to end the match with ${text} result?`;

            const confirmation = await confirmDialog(message, '');
            let reqCancelToken = undefined;

            Inertia.put(route('match.end'), {
                result: result
            }, {
                only: ['matchInfo', 'flash'],
                preserveScroll: true,
                preserveState: true,
                onBefore: () => {
                    return confirmation.isConfirmed;
                },
                onSuccess: (page) => {
                    clearTimeout(cancelRequestTimer);
                    endMatchReload(page);
                },
                onCancelToken: (cancelToken) => (reqCancelToken = cancelToken),
                onCancel: () => {
                    Swal.fire({
                        toast: true,
                        title: 'ការសងប្រាក់ជូនអតិថិជន កំពុងដំណើរការនៅផ្នែកខាងក្រោយ',
                        timer: 5000,
                        timerProgressBar: true,
                        icon: 'info',
                        position: 'top-end',
                        showConfirmButton: false,
                    });

                    //@todo refresh match state
                    Object.assign(matchData, toMatchInfo(undefined));
                    stopBetDuration();
                },
            });

            cancelRequestTimer = setTimeout(function () {
                reqCancelToken.cancel();
            }, 28000);
        }


        function endMatchReload(page) {
            if (page.props.flash.type !== 'error') {
                refreshMatchInfo(page.props.matchInfo, true);
                stopBetDuration();
                // reloadData(['todayWinLoss']);
            }
        }

        let isMeronBetEnable = ref(props.isMeronBetEnable);
        let isWalaBetEnable = ref(props.isWalaBetEnable);
        let meronButtonState = ref(false);
        let walaButtonState = ref(false);

        async function requestDisableBet(type) {
            // eslint-disable-next-line no-return-await

            if (type === 1) {
                meronButtonState.value = true;
            }

            if (type === 2) {
                walaButtonState.value = true;
            }

            let response = await axios.post(route('match.disable-bet'), {
                type: parseInt(type, 10),
                value: type === 1 ? !isMeronBetEnable.value : !isWalaBetEnable.value,
            });

            if (response.status === 200) {
                if (type === 1) {
                    meronButtonState.value = false;
                    isMeronBetEnable.value = !isMeronBetEnable.value;
                }

                if (type === 2) {
                    walaButtonState.value = false;
                    isWalaBetEnable.value = !isWalaBetEnable.value;
                }
            }
            console.log(response.data);
        }

        function resetMemberTypeValue(statusOnly = false) {
            let tmpTypes = types.value;
            _.forEach(tmpTypes, (value, key) => {
                tmpTypes[key].status = 'close'
                if (!statusOnly) {
                    tmpTypes[key].meron = 0;
                    tmpTypes[key].wala = 0;
                }
            })
            types.value = tmpTypes;
        }

        onMounted(() => {

            Echo.channel(`match.trader.${props.user.environment_id}.${props.user.group_id}`)
                .listen('.summary', payload => {
                    const summary = payload.match;
                    matchData.meronTotalBet = summary.meron_total_bet;
                    matchData.walaTotalBet = summary.wala_total_bet;
                    estimateBenefit.meron_benefit = summary.meron_benefit;
                    estimateBenefit.wala_benefit = summary.wala_benefit;

                    let tmpTypes = types.value;

                    _.forEach(summary.totalBetByMemberType, (value, key) => {

                        const typeName = props.memberTypeMapIdToName[key];

                        if (tmpTypes[typeName] !== undefined) {
                            tmpTypes[typeName].meron = value.meron;
                            tmpTypes[typeName].wala = value.wala;
                        }

                    });

                    types.value = tmpTypes;

                });

            Echo.channel(`match.${props.user.environment_id}.${props.user.group_id}`)
                .listen('.created', payload => {
                    showConfirmDialogOpenBet = true;
                    isWalaBetEnable.value = true;
                    isMeronBetEnable.value = true;
                    refreshMatchInfo(payload.match);
                })
                .listen('.updated', payload => {
                    refreshMatchInfo(payload.match);
                })
                .listen('.payout.updated', payload => {
                    const {meron_payout, wala_payout} = payload.match;
                    matchData.meronPayout = meron_payout;
                    matchData.walaPayout = wala_payout;
                })
                .listen('.bet.opened', payload => {
                    if (payload.match.memberType === undefined) {
                        matchData.betOpened = true;
                        matchData.betStatus = payload.match.bet_status;
                        startBetDuration();
                    }

                })
                .listen('.bet.closed', payload => {
                    if (payload.match.memberType === undefined) {
                        matchData.betStatus = payload.match.bet_status;
                        stopBetDuration();
                        resetMemberTypeValue(true);
                    }
                })
                .listen('.ended', payload => {
                    showConfirmDialogOpenBet = false;
                    betOpened.value = false;
                    estimateBenefit.meron_benefit = 0;
                    estimateBenefit.wala_benefit = 0;
                    resetMemberTypeValue();
                })
                .listen('.deposit.payout.completed', payload => {
                    clearTimeout(cancelRequestTimer);
                    let match = payload.match;
                    Swal.fire({
                        toast: true,
                        title: `ការសងប្រាក់ជូនអតិថិជនបានដោយជោគជ័យ លេខភា្នល់: ${match.fightNumber}, លទិ្ធផល: ${match.result}, សំប្រុត: ${match.totalPayoutTickets}, អថិជន: ${match.totalUser}, រយះពេល: ${match.duration}`,
                        timer: 5000,
                        timerProgressBar: true,
                        icon: 'success',
                        position: 'top-end',
                        showConfirmButton: false,
                    });
                })
                .listen('.endedResultSummary', payload => {
                    tmpResultCount.value = payload.match.count;
                    tmpResultSymbol.value = payload.match.symbol;
                });

        });

        const refreshMatchInfo = (matchInfo, endMatch = false) => {

            if (!matchInfo.payout_adjusted && !endMatch) {
                matchInfo.wala_payout = (totalPayout.value - payout.value);
                matchInfo.meron_payout = payout.value;
            }

            Object.assign(matchData, toMatchInfo(matchInfo));

        };

        function toggleBetMemberType(type, status) {

            let matchId = matchData.id;

            if (matchId === 0) {
                return;
            }

            let typeClone = _.clone(type);
            typeClone.status = status ? `open` : `close`;
            types.value[type.name] = typeClone;

            Inertia.post(route('match.toggle-bet-button'), {
                status: status,
                matchId: matchId,
                memberTypeId: type.id
            }, {
                only: ['flash'],
                preserveScroll: true,
                preserveState: true,
                onSuccess: params => {
                }
            })
        }

        return {
            betOpened,
            totalPayout,
            payout,
            matchDuration: betDuration,
            submitNewMatch,
            endTheMatch,
            estimateBenefit,
            matchData,
            tmpResultCount,
            tmpResultSymbol,
            requestDisableBet,
            isWalaBetEnable,
            isMeronBetEnable,
            walaButtonState,
            meronButtonState,
            types,
            toggleBetMemberType
        };
    },
});
</script>

<style scoped>

</style>
