export function toMatchInfo(propMatchInfo) {

    const matchId = propMatchInfo?.id ?? 0;
    return {
        id: matchId,
        fightNumber: propMatchInfo?.fight_number ?? '#',
        betStatus: propMatchInfo?.bet_status ?? 'Close',
        betOpened: (propMatchInfo?.bet_status !== 'close'),
        isLive: (matchId !== 0),
        walaTotalBet: propMatchInfo?.wala_total_bet ?? '0',
        walaPayout: propMatchInfo?.wala_payout ?? '#.##',
        meronTotalBet: propMatchInfo?.meron_total_bet ?? '0',
        meronPayout: propMatchInfo?.meron_payout ?? '#.##',
        disableBetButton: propMatchInfo?.disable_bet_button ?? true,
        result: propMatchInfo?.result ?? 'None',
        totalTicket: propMatchInfo?.total_ticket ?? 0
    };
}


