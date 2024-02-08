<template>
    <AppLayout :title="__('message')">
        <div class="flex-1 w-full mx-auto rounded bg-gray-50 xl:max-w-7xl">
            <div class="h-full overflow-hidden bg-white divide-y rounded">
                <div class="px-3 py-2 font-semibold text-blue-500 uppercase">
                    {{ __('message') }}
                </div>
                <div v-if="messages.data.length > 0">
                    <a
                        v-for="(message, index) in messages.data"
                        :key="message.id"
                        @click="openPreviewModal(message)"
                    >
                        <div 
                            :class="{'bg-gray-100' : message.unread, 'border-b': index + 1 < messages.data.length}"
                            class="flex px-5 py-3 space-x-3 border-gray-300 cursor-pointer hover:bg-gray-200"
                        >
                            <div class="relative w-10 h-10 shrink-0">
                                <img class="w-full h-full" :src="asset('images/icons/announcement-circle.svg')" alt="announcement">
                                <span v-if="message.unread" class="absolute right-0 w-2 h-2 rounded-full top-1 bg-meron"></span>
                            </div>
                            <div class="flex-1">
                                <div
                                    v-html="message.content" 
                                    class="text-sm line-clamp-3"
                                ></div>
                                <small class="text-gray-500">{{ message.timeHumans }}</small>
                            </div>
                        </div>
                    </a>
                </div>

                <div v-else>
                    <div class="flex px-5 py-3 space-x-3 border-gray-300">
                        <div class="flex-1">
                            <div class="text-sm">{{ __('no_messages_yet') }}</div>
                        </div>
                    </div>
                </div>

                <Pagination
                    :from="messages.from"
                    :to="messages.to"
                    :total="messages.total"
                    :links="messages.links"
                />
            </div>
        </div>

        <MessagePreview 
            v-model="isPreviewOpen" 
            :content="messageContent"
            :time-humans="messageTimeHumans"
        />
    </AppLayout>
</template>

<script setup>
    import { ref } from 'vue';
    import AppLayout from '@/Layouts/AppLayout.vue';
    import Pagination from '@/Shared/Pagination.vue';
    import MessagePreview from '@/components/MessagePreview';

    const props = defineProps({
        messages: Object,
    });

    const isPreviewOpen = ref(false);

    const messageContent = ref('');
    const messageTimeHumans = ref('');

    const brocastReadOnMessage = (id) => {
        EventBus.emit('readOnMessage', id);
    }

    const updateMessageData = (id) => {
        const message = props.messages.data.find(x => x.id === id);
        
        if(!message) return;

        message.unread = false;
    }

    const markMessageAsRead = async (id) => {
        await axios.post(`/message/${id}/mark-as-read`);

        brocastReadOnMessage(id);
    }

    const openPreviewModal = (message) => {
        if(message.unread) {  
            markMessageAsRead(message.id);
        }

        messageContent.value = message.content;
        messageTimeHumans.value = message.timeHumans;
        isPreviewOpen.value = true;
    }

    EventBus.on('readOnMessage', (id) => updateMessageData(id));
</script>