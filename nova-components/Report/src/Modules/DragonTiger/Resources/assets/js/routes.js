export default [
    {
        name: 'report-dragon-tiger-win-lose',
        path: '/report/dragon-tiger/win-lose',
        component: require('@dragon-tiger/Pages/WinLose'),
    },
    {
        name: 'report-dragon-tiger-bet-detail',
        path: '/report/dragon-tiger/bet-detail/:memberId',
        component: require('@dragon-tiger/Pages/BetDetail'),
    },
    {
        name: 'report-dragon-tiger-balance-statement',
        path: '/report/dragon-tiger/balance/statement/:memberId',
        component: require('@dragon-tiger/Pages/BalanceStatement'),
    }
];