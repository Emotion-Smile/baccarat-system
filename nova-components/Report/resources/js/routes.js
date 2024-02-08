export default [
    {
      name: 'report-win-lose',
      path: '/report-win-lose',
      component: require('@/views/win-loss/BetSammary'),
    },
    {
      name: 'report-win-lose-v2',
      path: '/v2/report-win-lose',
      component: require('@/views/win-loss/BetSummaryV2'),
    },
    {
      name: 'report-win-lose-detail',
      path: '/report-win-lose/detail/:memberId',
      component: require('@/views/win-loss/BetDetail'),
    },
    {
      name: 'win-loss-report-statement-balance',
      path: '/report/win-lose/statement-balance/:memberId',
      component: require('@/views/win-loss/BalanceStatement'),
    },
    {
      name: 'report-payments',
      path: '/report/payments',
      component: require('@/views/payment/Index'),
      children: [
        {
          name: 'sub-user-payments-report',
          path: ':userId',
          component: require('@/views/payment/Index')
        }
      ],
      props: true
    },
    {
      name: 'report-outstanding-tickets',
      path: '/report/outstanding-tickets',
      component: require('@/views/outstanding-ticket/Index'),
    },
    {
      name: 'report-booking-tickets',
      path: '/report/booking-tickets',
      component: require('@/views/booking-ticket/Index'),
    },
    {
      name: 'report-missing-payouts',
      path: '/report/missing-payouts',
      component: require('@/views/missing-payout/Index'),
    },
    {
      name: 'report-top-winners',
      path: '/report/top-winners',
      component: require('@/views/top-winner/Index'),
    },
    {
      name: 'report-t88-win-lose',
      path: '/report/t88/win-lose',
      component: require('@/views/t88-win-lose/Index'),
    },
    {
      name: 'report-t88-win-lose-balance-statement',
      path: '/report/t88/win-lose/balance-statement/:name',
      component: require('@/views/t88-win-lose/BalanceStatement'),
    },
    {
      name: 'report-t88-win-lose-bet-detail',
      path: '/report/t88/win-lose/bet-detail/:name',
      component: require('@/views/t88-win-lose/T88BetDetail'),
    },
    {
      name: 'report-t88-outstanding-tickets',
      path: '/report/t88/outstanding/tickets',
      component: require('@/views/t88-outstanding-tickets/Index'),
    },
    {
      name: 'report-af88-win-lose',
      path: '/report/af88/win-lose',
      component: require('@/views/af88-win-lose/Index'),
    },
    {
      name: 'report-af88-win-lose-single-bet-detail',
      path: '/report/af88/win-lose/single-bet/detail/:name',
      component: require('@/views/af88-win-lose/SingleBetDetail'),
    },
    {
      name: 'report-af88-win-lose-mix-parlay-bet-detail',
      path: '/report/af88/win-lose/mix-parlay-bet/detail/:name',
      component: require('@/views/af88-win-lose/MixParlayBetDetail'),
    },
    {
      name: 'report-af88-win-lose-balance-statement',
      path: '/report/af88/win-lose/balance-statement/:name',
      component: require('@/views/af88-win-lose/BalanceStatement'),
    },
    {
      name: 'report-af88-member-outstanding',
      path: '/report/af88/member/outstanding',
      component: require('@/views/af88-member-outstanding/Index'),
    },
    {
      name: 'report-af88-member-outstanding-detail',
      path: '/report/af88/member/outstanding/detail/:accountId',
      component: require('@/views/af88-member-outstanding/Detail'),
    },
];