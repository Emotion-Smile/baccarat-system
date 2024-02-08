import {Inertia} from '@inertiajs/inertia';


export function startNewMatch(totalPayout, meronPayout, fightNumber, isConfirmed) {
    // eslint-disable-next-line no-undef
    Inertia.post(route('match.create-new'), {
        totalPayout: totalPayout,
        meronPayout: meronPayout,
        fightNumber: fightNumber
    }, {
        only: ['matchInfo', 'flash', 'errors'],
        preserveState: true,
        preserveScroll: true,
        onBefore: () => {
            return isConfirmed;
        }
    });
}

export async function reloadData(data, preState = true, preScroll = true) {
    Inertia.reload({
            only: data,
            preserveState: preState,
            preserveScroll: preScroll,
        }
    );
}





