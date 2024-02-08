import {defineStore} from 'pinia';
import {useUserRepository} from '@/Repositories/UserRepository';

export const useUserStore = defineStore('userStore', {
    state: () => ({
        coins:[],
        balance: null,
        userBetRecords: [],
        todayTickets:[],
        user: {},
        scoreboardCount:{},
        betLimit: {},
        betHistories: [],
        isMusicPlay: true,
        isSoundPlay: true,
        music: null,
        musicId: 0
    }),
    getters: {
        getCurrentBetCoin:(state)=>(betAmount)=> state.coins.find((coin)=> coin.amount === betAmount),
        getBalance: (state) => state.balance,
        getUserBetRecords: (state)=> tab => {
            if (tab === 'current-bet'){
                return state.userBetRecords;
            }
            return state.todayTickets?.map(function (todayTicket){
                return {
                    fight: todayTicket.gameNumber,
                    bet: todayTicket.betOn,
                    winLoss: todayTicket.winLose,
                    time: todayTicket.time,
                    ticketResult: todayTicket.ticketResult,
                    status: todayTicket.status ?? 'accepted',
                };
            });
        },
        condition: state => state.user.condition,
        currency: state => state.user.currency,
        getId:state => state.user.id,
        groupId: state => state.user.group_id,

    },
    actions: {
        async refreshBalance() {
            const userRepository = useUserRepository();
            this.balance = await userRepository.fetchBalance();
        },

        fetchUserProfile(){
            const userRepository = useUserRepository();
            this.user =   userRepository.fetchProfile();
        },

        loadUserCoins(){
            const userRepository = useUserRepository();
            this.coins =  userRepository.fetchUserCoins();
        },

        loadUserBets(){
            const userRepository = useUserRepository();
            this.userBetRecords = userRepository.fetchOutstandingTickets();
        },
        loadTodayTickets(){
            const userRepository = useUserRepository();
            this.todayTickets =  userRepository.fetchTodayTickets();
        },
        loadScoreboardCount(){
            const userRepository = useUserRepository();
            this.scoreboardCount = userRepository.fetchScoreboardCount();
        },
        loadBetLimit(){
            const userRepository = useUserRepository();
            this.betLimit = userRepository.fetchBetLimit();
        },
        loadBetHistories(){
            const userRepository = useUserRepository();
            this.betHistories = userRepository.fetchBetHistories();
        }
    },
});
