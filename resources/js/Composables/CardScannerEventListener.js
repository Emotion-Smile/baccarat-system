import { onMounted, onBeforeUnmount } from 'vue';
import { useUserStore } from '@/Stores/DragonTigers/UserStore';
import { storeToRefs } from 'pinia';

export const useCardScannerEventListener = callback => {
    let data;
    let channel;

    const { groupId } = storeToRefs(useUserStore());

    const subscribe = () => {
        channel = Echo.channel(`dragon_tiger.table.${groupId.value}`);

        if(! channel.subscription.subscribed) {
            channel.subscribe();
        }

        channel.listen('.card.scanned', payload => {
            if (typeof callback === 'function') {
                callback(payload);
            } else  {
                data = payload;
            }
        });
    };

    onMounted(() =>  subscribe());

    onBeforeUnmount(() => channel && channel.unsubscribe());

    return { data };
};
