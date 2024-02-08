import { defineStore } from 'pinia';
import { useGameStore as useBaseGameStore } from '@/Stores/DragonTigers/GameStore';
import { useGameRepository } from '@/Repositories/GameRepository';
import { Inertia } from '@inertiajs/inertia';
import { useUserStore } from '@/Stores/DragonTigers/UserStore';
import Swal from 'sweetalert2';
import { useStorage } from '@vueuse/core';
import { isEmpty } from 'lodash';
import { useAutoCreateNewGamePopup } from '@/Composables/AutoCreateNewGamePopup';

export const useGameStore = defineStore('traderGameStore', {
    state: () => ({
        betSummary: {},
        resultSubmitted: false,
        betIntervalInSecond: useStorage('betIntervalInSecond', 60),
        autoCreateNewGameIntervalInSecond: useStorage('autoCreateNewGameIntervalInSecond', 5),
        gameId: 0,
        isNextRound: false,
        onConfirmResult: false,
        resultPopupDialog: {
            isOpen: false,
            gameNumber: '',
            titleCase: '',
            action: () => {}
        },
        dragonCard: {},
        tigerCard: {},
        cardSymbols: [
            'club',
            'spade',
            'heart',
            'diamond'
        ],
        cards: [
            { value: 1, text: 'A' },
            { value: 2, text: '2' },
            { value: 3, text: '3' },
            { value: 4, text: '4' },
            { value: 5, text: '5' },
            { value: 6, text: '6' },
            { value: 7, text: '7' },
            { value: 8, text: '8' },
            { value: 9, text: '9' },
            { value: 10, text: '10' },
            { value: 11, text: 'J' },
            { value: 12, text: 'Q' },
            { value: 13, text: 'K' },
        ],

        resultHistories: [
            { type: 'total', title: 'total', grandTotal: '168', totalRed: '100', totalBlack: '68' },
            { type: 'dragon', title: 'dragon', grandTotal: '45', totalRed: '20', totalBlack: '25' },
            { type: 'tiger', title: 'tiger', grandTotal: '55', totalRed: '35', totalBlack: '20' },
            { type: 'tie', title: 'tie', grandTotal: '68', totalRed: '60', totalBlack: '08' },
        ],
    }),

    getters: {
        getCards: state => state.cards,
        getCardSymbols: state => state.cardSymbols,
        getResultHistories: state => state.resultHistories,
        getResultPopupDialog: state => state.resultPopupDialog,
        getOnConfirmResult: state => state.onConfirmResult,
        getGameNumber: () => useBaseGameStore().game.gameNumber,
        getCountDown: () => useBaseGameStore().game.bettingInterval,
        getTableNumber: () => useBaseGameStore().table.label,
        getGame: () => useBaseGameStore().game,
        liveStreamLink:()=> useBaseGameStore().liveStreamLink,
        getGameResultMatrix: () => useBaseGameStore().getGameResultMatrix,
        isDisabledSubmit: state=> Object.keys(state.dragonCard).length <= 0 || Object.keys(state.tigerCard).length <= 0,
        getBetStatus:()=>useBaseGameStore().game.betStatus,
        getResultCards () {
            const dragonCard = this.cards.find(({ value }) => this.dragonCard.number === value);
            const tigerCard = this.cards.find(({ value }) => this.tigerCard.number === value);
            return [
                { name: dragonCard?.text, type: this.dragonCard.symbol, side: 'dragon' },
                { name: tigerCard?.text, type: this.tigerCard.symbol, side: 'tiger' },
            ];
        },
        isResultSubmitted() {return (this.getGame.dragonResult > 0  && this.getGame.tigerResult > 0 || this.getGame.gameNumber === '#' ) || this.resultSubmitted; },
        isResultPopupDialogOpen: state => state.resultPopupDialog.isOpen,

        isOpeningBet() {
            return this.getCountDown > 0;
        },

        getResultCardImage: state => {
            return side => {
                const card = side === 'dragon' ? state.dragonCard : state.tigerCard;

                return isEmpty(card)
                    ? 'dragon-tiger/card/back.svg'
                    : `dragon-tiger/card/${card.number}_of_${card.symbol}s.svg`;
            }
        },
    },

    actions: {
        startNewGame() {
            useGameRepository()
                .createGame({
                    roundMode: this.isNextRound? 'nextRound': 'lastRound',
                    betIntervalInSecond: this.betIntervalInSecond
                })
                .then(async ({data: {type, message}})=>{
                    await Swal.fire({
                        icon: type === 'ok'?'success': 'error',
                        title: message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    useBaseGameStore().gameCountdownEvent();
                    this.setResultSubmitted(false);
                    this.isNextRound = false;
                });

        },

        loadGameState(){
            useBaseGameStore().gameCountdownEvent();
        },

        setGameState(game){
            useBaseGameStore().$patch({game});
        },

        loadTable(){
            useBaseGameStore().loadTable();
        },

        loadBetSummary(){
            this.betSummary = useGameRepository().fetchBetSummary();
        },

        loadGameResultMatrix(){
            useBaseGameStore().loadGameResultMatrix();
        },

        destroyInterval(){
            useBaseGameStore().destroyInterval();
        },

        submitResult(callback = () => {}) {
            useGameRepository()
                .submitResult( {
                    'dragonResult': this.dragonCard.number,
                    'dragonType': this.dragonCard.symbol,
                    'tigerResult': this.tigerCard.number,
                    'tigerType': this.tigerCard.symbol
                })
                .then(async ({data:{type, message}})=>{
                    await Swal.fire({
                        icon: type === 'ok'?'success': 'error',
                        title: message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    this.setResultSubmitted(type === 'ok');
                    this.setResultPopupDialogClose();
                    setTimeout(() => {
                        this.resetDefaultCard();
                        this.setOnConfirmResult(false);
                    }, 500);

                    callback();
                });
        },

        resubmitResult() {
            useGameRepository()
                .resubmitResult( {
                    'gameId': this.gameId,
                    'dragonResult': this.dragonCard.number,
                    'dragonType': this.dragonCard.symbol,
                    'tigerResult': this.tigerCard.number,
                    'tigerType': this.tigerCard.symbol
                }).then(async ({data:{type, message}})=>{
                    await Swal.fire({
                        icon: type === 'ok'?'success': 'error',
                        title: message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    Inertia.reload({only: ['scoreboard', 'scoreboardCount'],
                        onSuccess: function(){
                            useBaseGameStore().loadGameResultMatrix();
                            useUserStore().loadScoreboardCount();
                        }
                    });
                    this.setResultPopupDialogClose();
                    setTimeout(() => {
                        this.resetDefaultCard();
                        this.setOnConfirmResult(false);
                    }, 500);
                });
        },

        cancelGame() {
            useGameRepository()
                .cancelThePlayingGame()
                .then(async ({data: {type, message}})=>{
                    await Swal.fire({
                        icon: type === 'ok'?'success': 'error',
                        title: message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    this.setResultSubmitted(true);
                    setTimeout(() => {
                        this.resetDefaultCard();
                    }, 500);
                });
        },

        setResultSubmitted(value) {
            this.resultSubmitted = value;
        },

        setOnConfirmResult(value) {
            this.onConfirmResult = value;
        },

        setResultCard({ side, value }) {
            if(this.isOpeningBet || this.isResultSubmitted) {
                return;
            }

            side === 'dragon' ? this.setDragonCard(value) : this.setTigerCard(value);
        },

        setDragonCard(dragonCard) {
            this.dragonCard = dragonCard;
        },

        setTigerCard(tigerCard) {
            this.tigerCard = tigerCard;
        },

        resetDefaultCard() {
            this.setDragonCard({});
            this.setTigerCard({});
        },

        setResultPopupDialogForSubmitResult() {
            this.resultPopupDialog.gameNumber = this.getGameNumber;
            this.resultPopupDialog.titleCase = 'Submit';
            this.resultPopupDialog.action = () => this.submitResult();
            this.resultPopupDialog.isOpen = true;
        },

        setResultPopupDialogForResubmitResult({ gameNumber}) {
            this.resultPopupDialog.gameNumber = gameNumber;
            this.resultPopupDialog.titleCase = 'Resubmit';
            this.resultPopupDialog.action = () => this.resubmitResult();
            this.resultPopupDialog.isOpen = true;
        },

        setResultPopupDialogClose() {
            this.resultPopupDialog.isOpen = false;
        },

        submitResultAndCreateNewGame() {
            this.submitResult(() => {
                useAutoCreateNewGamePopup(() => {
                    this.startNewGame();
                }, this.autoCreateNewGameIntervalInSecond);
            });
        }
    },
});
