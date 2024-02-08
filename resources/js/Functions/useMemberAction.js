import {Inertia} from '@inertiajs/inertia';


export async function getTotalBetOfCurrentMatch() {
    // eslint-disable-next-line no-undef
    const result = await axios.get(route('member.match.total-bet'));
    const {meron, wala} = result.data;
    return {
        meron,
        wala
    };
}

export function switchToGroup(groupId) {
    // eslint-disable-next-line no-undef
    Inertia.put(route('member.switch-group'), {
        groupId: groupId
    }, {
        preserveState: false,
        preserveScroll: true,
    });
}



