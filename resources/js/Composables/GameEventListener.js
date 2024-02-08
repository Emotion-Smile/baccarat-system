import { onBeforeUnmount, onMounted, watchEffect } from 'vue';
import {useUserStore} from '@/Stores/DragonTigers/UserStore';
import { storeToRefs } from 'pinia';

export const useDragonTigerEventListener = function (event, callback) {
    const {groupId} = storeToRefs(useUserStore());
    let data;
    let channel;
    const subscribe = () => {
        channel = Echo.channel(`dragon_tiger.table.${groupId.value}`);
        if(!channel.subscription.subscribed){
            channel.subscribe();
        }
        channel.listen(`.game.${event}`, payload => {
            if (typeof callback === 'function') {
                callback(payload);
            } else {
                data = payload;
            }
        });
    };

    const changeGroup = (newGroupId) => {
        if (channel) {
            channel.unsubscribe();
        }

        groupId.value = newGroupId;
        subscribe();
    };

    onMounted(function () {
        subscribe();
    });

    onBeforeUnmount(() => {
        if (channel) {
            channel.unsubscribe();
        }
    });

    watchEffect(() => {
        changeGroup(groupId.value);
    });

    return { data, changeGroup };
};
