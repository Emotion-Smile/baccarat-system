import {usePage} from '@inertiajs/inertia-vue3';

/**
 * Fetch Current Game and returns an object representing the user balance.
 * @returns {Promise<String>} - Promise of Number of the current user.
 */
async function fetchBalance(){
    const {data} = await axios.get(route('balance.refresh'));
    return data.balance;
}

/**
 * Fetch Current User Profile and returns an object representing the profile of current user.
 * @returns {Object<any>}
 */
function fetchProfile(){
    return usePage().props.value.user;
}

/**
 * Fetch Current User Coins and returns an object representing the coins of current user.
 * @returns {Object<any>[]} - Promise of Object of the user coins.
 */
function fetchUserCoins(){
    const chip = usePage().props.value.chips;
    const chips = [];
    Object.keys(chip).forEach(function (key){
        chips.push({
            name:`bet-${key}`,
            amount: chip[key]['key'],
            amountText:chip[key]['value'],
            ref: `bet${key}`,
            url: `dragon-tiger/images/${chip[key]['coin']}.png`
        });
    });
    return chips;
}

/**
 * Fetch the current bets or bet histories and returns an object representing the current bets or bet histories of current user.
 * @returns {Object<any>[]} - Promise of Object of the current bets or bet histories.
 */
function fetchOutstandingTickets(){
    return usePage().props.value.outstandingTickets.map(function (item){
        return{
            fight: item.gameNumber,
            bet: item.betOn,
            winLoss: item.winLose,
            time: item.betTime,
            status: item.status,
        };
    });
}

/**
 * Fetch the today's member ticket histories and returns an object representing the today's tickets of current user.
 * @returns {Object<any>[]} - Object of the today's ticket histories.
 */
function fetchTodayTickets(){
    return  usePage().props.value.todayTickets;
}

/**
 * Fetch the scoreboard count and returns an object representing the game scoreboard count.
 * @returns {Object<any>} - Object of the game scoreboard count.
 */
function fetchScoreboardCount(){
    return usePage().props.value.scoreboardCount;
}

/**
 * Fetch the bet limit and returns an object representing the bet limit of the user.
 * @returns {Object<any>} - Object of the bet limit of the user.
 */
function fetchBetLimit(){
    return usePage().props.value.betLimit;
}

/**
 * Fetch the bet histories and returns a List of object representing the bet histories.
 * @returns {Object<any>[]} - List of Object of the bet histories.
 */
function fetchBetHistories(){
    return usePage().props.value.tickets;
}

export const useUserRepository = function (){
    return{
        fetchBalance,
        fetchUserCoins,
        fetchOutstandingTickets,
        fetchProfile,
        fetchTodayTickets,
        fetchScoreboardCount,
        fetchBetLimit,
        fetchBetHistories,
    };
};
