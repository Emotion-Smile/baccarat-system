import { defineStore } from 'pinia';
import {useUserStore} from '@/Stores/DragonTigers/UserStore';
import {useGroup} from '@/Composables/GameSlot';
import {useGameRepository} from '@/Repositories/GameRepository';
import Swal from 'sweetalert2';
import { useSweetAlert } from '@/Composables/SweetAlert';
import { useCurrency } from '@/Composables/Currency';

const Currency = useCurrency();
const Group = useGroup();
export const useGameStore = defineStore('gameStore', {
    state: () => ({
        gameSlots: null,
        betAmount: 0,
        interval: null,
        game: {},
        gameResultMatrix: [],
        isBetSucceeded: false,
        table:{},
        isAutoConfirm: true,
        isStartBetting: false,
        streamTables: [],
    }),
    getters: {
        dragon:  (state)=> {
            return  state.gameSlots?.find((slot)=>slot.group === Group.DRAGON);
        },
        dragonColors: (state)=>{
            return state.gameSlots?.filter(slot=>slot.group === Group.DRAGON_COLOR);
        },
        tiger: (state)=> {
            return state.gameSlots?.find(slot=>slot.group === Group.TIGER);
        },
        tigerColors: (state)=>{
            return state.gameSlots?.filter(slot=>slot.group === Group.TIGER_COLOR);
        },
        tie: (state)=> {
            return state.gameSlots?.find(slot=>slot.group === Group.TIE);
        },
        notAllowedBet:(state)=>state.betAmount < 1 || state.countdown <= 0,
        getGameResultMatrix:(state)=>state.gameResultMatrix,
        checkMainResult: state => type => state.game.mainResult.includes(type),
        checkSubResult: state => type => state.game.subResult.includes(type),
        countdown: state=>state.game.bettingInterval,
        betStatus: state => state.game.betStatus,
        isResultSubmitted: state => state.game.mainResult !== '' && state.game.subResult !== '',
        dragonResultCard: state => `dragon-tiger/card/${state.game.dragonResult}_of_${state.game.dragonType}s.svg`,
        tigerResultCard: state => `dragon-tiger/card/${state.game.tigerResult}_of_${state.game.tigerType}s.svg`,
        liveStreamLink: state => state.table.stream_url !== '#'? state.table.stream_url: '',
        getChip(){
            return function(amount){
                let amountUSD = Math.floor(amount / this.currencyRate);
                if (amountUSD <=1){
                    return 'chip-1';
                }
                else if (amountUSD <=5){
                    return 'chip-2';
                }
                else if (amountUSD <=10){
                    return 'chip-3';
                }
                else if (amountUSD <=20){
                    return 'chip-4';
                }
                else if (amountUSD <=50){
                    return 'chip-5';
                }
                else if (amountUSD <=100){
                    return 'chip-6';
                }
                else if (amountUSD <=200){
                    return 'chip-7';
                }
                else return 'chip-8';
            };
        },
        currencyRate() {
            switch (useUserStore().currency.toLowerCase()){
            case Currency.KHR: return 4000;
            case Currency.VND: return 24325;
            case Currency.THB: return 35;
            default: return 1;
            }
        },
    },
    actions: {
        loadStreamTable(){
            this.streamTables = useGameRepository().fetchStreamingTable();
        },
        loadGameSlots(){
            const gameRepository = useGameRepository();
            this.gameSlots =  gameRepository.fetchGameSlots();
        },

        async onSettleBet(betType){
            const userStore = useUserStore();
            const gameSlot = this.gameSlots.find(slot=>slot.type === betType);
            if (this.isAutoConfirm){
                this.executeSettleBet(betType, gameSlot)
                    .catch(error=>{
                        Swal.fire({
                            icon: 'error',
                            title: error.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    });
                return;
            }

            const numberFormat = new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: userStore.currency,
            });
            const amountText = numberFormat.format(this.betAmount);
            const combineStrings = (i, j) => i === j ? i : i + ' ' +j;
            await Swal.fire({
                title: `Do you want to bet:  \n ${combineStrings(gameSlot.betOn?.toUpperCase(), gameSlot.betType?.toUpperCase())} ${amountText} ? `,
                showCancelButton: true,
                cancelButtonText: 'No',
                confirmButtonText: 'Yes',
                showLoaderOnConfirm: true,
                preConfirm: async ()=>{
                    return await this.executeSettleBet(betType, gameSlot)
                        .catch(err=>Swal.showValidationMessage(err.message));
                },
                allowOutsideClick: () => !Swal.isLoading(),
            });
        },

        async gameRebetting(){
            if (this.isAutoConfirm)
            {
                this.reexecuteGameBetting()
                    .catch(async ({message})=>{
                        await Swal.fire({
                            icon: 'error',
                            title: message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    });
                return;
            }

            await Swal.fire({
                title: 'Do you want to rebet?',
                showCancelButton: true,
                cancelButtonText: 'No',
                confirmButtonText: 'Yes',
                showLoaderOnConfirm: true,
                preConfirm: async ()=>{
                    return await this.reexecuteGameBetting()
                        .catch(err=>Swal.showValidationMessage(err.message));
                },
                allowOutsideClick: () => !Swal.isLoading(),
            });
        },

        async reexecuteGameBetting(){
            return await useGameRepository()
                .gameRebetting()
                .then(async ({message})=> {
                    useUserStore().$patch({ balance: message });
                    this.isBetSucceeded = true;
                    await useSweetAlert().Toast.fire({
                        icon: 'success',
                        title: 'Rebet in successfully'
                    });
                });

        },

        async executeSettleBet(betType, gameSlot){
            const userStore = useUserStore();
            return await useGameRepository().settleBet({
                betOn: gameSlot.betOn,
                betType: gameSlot.betType,
                amount: this.betAmount
            }).then(data=>{
                gameSlot.totalBetAmount += this.betAmount;
                userStore.$patch({balance: data.message});
                const coin = userStore.getCurrentBetCoin(gameSlot.totalBetAmount);
                gameSlot.coin = coin?.url;
                this.betAmount = 0;
                return data;
            }).then(async (data)=>{
                this.isBetSucceeded = true;
                await useSweetAlert().Toast.fire({
                    icon: 'success',
                    title: 'Betting in successfully'
                });
                return data;
            });
        },

        destroyInterval() {
            clearInterval(this.interval);
            this.interval = null;
        },

        loadGameResultMatrix(){
            const gameRepository = useGameRepository();
            this.gameResultMatrix = gameRepository.fetchResultMatrix();
        },

        gameCountdownEvent(){
            this.game = useGameRepository().fetchCurrentGame();
            this.destroyInterval();
            if (this.game.bettingInterval <= 0 ||  this.game.betStatus === 'close') {
                this.game.bettingInterval = 0;
                return;
            }

            this.interval = setInterval(() => {
                --this.game.bettingInterval;
                if (this.game.bettingInterval <= 0) {
                    this.game.betStatus = 'close';
                    this.destroyInterval();
                }

            }, 1000);
        },

        loadTable(){
            this.table = useGameRepository().fetchTable();
        }
    },
});
