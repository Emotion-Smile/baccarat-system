import {useGroup, useResultType, useType} from '@/Composables/GameSlot';
import {usePage} from '@inertiajs/inertia-vue3';


const Type = useType();
const Group = useGroup();
const ResultType = useResultType();


/**
 * Fetch game slots and returns an array of slot objects.
 * @returns {Object[]} - Promise of array of game slot objects.
 */
function fetchGameSlots () {
    const{tie, dragon_black, dragon_red, tiger, tiger_red, tiger_black, dragon} = usePage().props.value.memberBetState;
    return [
        {title: 'DRAGON', group: Group.DRAGON, type: Type.DRAGON, coin: null, betType: 'dragon', betOn: 'dragon', card: null, totalBetAmount: dragon?.amount ?? 0, ...dragon},
        {title: 'RED', group: Group.DRAGON_COLOR,type: Type.DRAGON_RED, coin:null, betType: 'red', betOn: 'dragon', card: null, totalBetAmount: dragon_red?.amount?? 0, ...dragon_red},
        {title: 'BLACK', group: Group.DRAGON_COLOR,type: Type.DRAGON_BLACK, coin: null, betType: 'black', betOn: 'dragon', card: null, totalBetAmount: dragon_black?.amount ?? 0, ...dragon_black},
        {title: 'TIGER', group: Group.TIGER,type: Type.TIGER, coin: null, card: null, betType: 'tiger', betOn: 'tiger', totalBetAmount: tiger?.amount ?? 0, ...tiger},
        {title: 'RED', group: Group.TIGER_COLOR,type: Type.TIGER_RED, coin: null, card: null, betType: 'red', betOn: 'tiger', totalBetAmount: tiger_red?.amount ?? 0, ...tiger_red},
        {title: 'BLACK', group: Group.TIGER_COLOR,type: Type.TIGER_BLACK, coin: null, card: null, betType: 'black', betOn: 'tiger',  totalBetAmount: tiger_black?.amount ?? 0, ...tiger_black},
        {title: 'TIE', group: Group.TIE, coin: null,type: Type.TIE, card: null, betType: 'tie', betOn: 'tie', totalBetAmount: tie?.amount?? 0, ...tie},
    ];
}

async function settleBet(payload){
    const res = await axios.post(route('dragon-tiger.betting'), {...payload});
    if (res.data.type === 'failed'){
        return Promise.reject(res.data);
    }
    return Promise.resolve(res.data);
}


async function gameRebetting(){
    const res = await axios.post(route('dragon-tiger.rebetting'));
    if (res.data.type === 'failed'){
        return Promise.reject(res.data);
    }
    return Promise.resolve(res.data);
}

/**
 * Fetch Result Matrix and returns a two-dimensional array representing the result matrix.
 * @returns {Promise<Object[][]>} - Promise of two-dimensional array of result matrix objects.
 */
function fetchResultMatrix () {
    return usePage().props.value.scoreboard.map(function (scoreBoards) {
        return scoreBoards.map(function (scoreBoard){
            let cssClass =getCssClass(scoreBoard.result?.toUpperCase());

            if (Array.isArray(scoreBoard)){
                return {
                    result: null,
                    cssClass
                };
            }

            return {
                ...scoreBoard,
                result: scoreBoard.result.toUpperCase(),
                cssClass
            };
        });
    });
}

/**
 * get css class and returns a string[] representing the css class.
 * @returns {string[]} - array of the css class.
 */
function getCssClass(result){
    switch (result) {
    case ResultType.TIE:
        return ['border-dragon-green', 'bg-dragon-teal'];
    case ResultType.DRAGON:
        return ['bg-dragon-dragon', 'border-dragon-red'];
    case ResultType.TIGER:
        return ['bg-dragon-tiger', 'border-dragon-blue'];
    case ResultType.CANCELLED:
        return ['border-gray-600', 'bg-gray-500'];
    default:
        return ['bg-transparent', 'border-transparent'];
    }
}

/**
 * Fetch Current Game and returns an object representing the current game.
 * @returns {Object<any>} - Promise of object of the current game.
 */
function fetchCurrentGame (){
    return usePage().props.value.gameState;
}

/**
 * Fetch Table and returns an object representing the table.
 * @returns {Object<any>} - object of the table.
 */
function fetchTable(){
    return usePage().props.value.table;
}

/**
 * Create game and returns an object representing the opening game.
 * @returns {Promise<Object>} - Promise of object of response object.
 */
async function createGame(payload){
    return await axios.post(route('dragon-tiger.create-new-game'), payload);
}

/**
 * Submit game result and returns an object representing the creation game succeeded.
 * @returns {Promise<Object>} - Promise of object of response object.
 */
async function submitResult(payload){
    return await axios.put(route('dragon-tiger.submit-result'), payload);
}

/**
 * Submit game result and returns an object representing the creation game succeeded.
 * @returns {Promise<Object>} - Promise of object of response object.
 */
async function resubmitResult(payload){
    return await axios.put(route('dragon-tiger.resubmit-result'), payload);
}

/**
 * Cancelling the playing game and returns an object representing the cancelling game succeeded.
 * @returns {Promise<Object>} - Promise of object of response object.
 */
async function cancelThePlayingGame(){
    return await axios.put(route('dragon-tiger.submit-cancel-result'));
}

function fetchBetSummary(){
    const betSummary = usePage().props.value.betSummary;
    return {
        dragon_red: betSummary.dragon_red ?? { label: null, value: 0 },
        dragon_black: betSummary.dragon_black ?? { label: null, value: 0 },
        dragon_dragon: betSummary.dragon_dragon ?? { label: null, value: 0 },
        tie_tie: betSummary.tie_tie ?? { label: null, value: 0 },
        tiger_tiger: betSummary.tiger_tiger ?? { label: null, value: 0 },
        tiger_red: betSummary.tiger_red ?? { label: null, value: 0 },
        tiger_black: betSummary.tiger_black ?? { label: null, value: 0 },
    };
}

/**
 * @Fetch the streaming tables from server and returns an Array of object representing the streaming info .
 * @returns {Promise<Object[]>} - Promise of response object array.
 */
function fetchStreamingTable(){
    return usePage().props.value.allTable;
}

export const useGameRepository = function (){
    return {
        fetchGameSlots,
        fetchResultMatrix,
        fetchCurrentGame,
        settleBet,
        fetchTable,
        createGame,
        submitResult,
        resubmitResult,
        cancelThePlayingGame,
        fetchBetSummary,
        gameRebetting,
        fetchStreamingTable
    };
};
