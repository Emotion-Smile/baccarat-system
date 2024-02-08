<template>
  <Menu
      as="div"
      class="relative hidden text-left md:flex md:items-center mr-2"
  >
    <MenuButton
        @click="openModal"
        class="flex relative h-8 w-8 items-center justify-center rounded-full border border-border bg-navbar-item focus:outline-none"
    >
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
           class="w-4 h-4 text-white">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0M3.124 7.5A8.969 8.969 0 0 1 5.292 3m13.416 0a8.969 8.969 0 0 1 2.168 4.5"/>
      </svg>
      <span
          v-show="data.totalUnreadMessage > 0"
          class="absolute flex items-center justify-center w-5 h-5 text-xs text-white border border-white rounded-full -top-1.5 -right-2 bg-meron">
      {{ data.totalUnreadMessage }}
      </span>
    </MenuButton>

    <transition
        enter-active-class="transition duration-100 ease-out"
        enter-from-class="transform scale-95 opacity-0"
        enter-to-class="transform scale-100 opacity-100"
        leave-active-class="transition duration-75 ease-in"
        leave-from-class="transform scale-100 opacity-100"
        leave-to-class="transform scale-95 opacity-0"
    >
      <MenuItems
          v-show="isOpen"
          class="absolute right-0 top-10 mt-2 w-56 xl:w-[40rem] origin-top-right divide-y divide-gray-100 rounded-md bg-dropdown overflow-hidden shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
      >
        <div class="px-3 py-2 font-semibold uppercase text-dropdown-msg-text">
          {{ __('message') }}
        </div>

        <div v-if="data.messages.length > 0">
          <a
              v-for="(message, index) in data.messages"
              :key="message.id"
              @click="openPreviewModal(message)"
          >
            <div
                :class="{'bg-gray-100' : message.unread, 'border-b': index + 1 < data.messages.length}"
                class="flex px-5 py-3 space-x-3 border-gray-300 cursor-pointer hover:bg-gray-200"
            >
              <div class="relative w-10 h-10 shrink-0">
                <img
                    class="w-full h-full"
                    :src="asset('images/icons/announcement-circle.svg')"
                    alt="announcement"
                />
                <span v-if="message.unread" class="absolute right-0 w-2 h-2 rounded-full top-1 bg-meron"></span>
              </div>
              <div class="flex-1">
                <div class="text-sm line-clamp-3" v-html="message.content"></div>
                <small class="text-gray-500">{{ message.timeHumans }}</small>
              </div>
            </div>
          </a>
        </div>

        <div v-else>
          <div class="flex px-5 py-3 space-x-3 border-gray-300">
            <div class="flex-1">
              <div class="text-sm text-dropdown-msg-text">{{ __('no_messages_yet') }}</div>
            </div>
          </div>
        </div>

        <div v-if="data.messages.length > 0" class="px-3 py-2 text-center uppercase">
          <Link
              :href="route('member.messages')"
              class="text-sm text-center text-blue-500"
          >
            {{ __('view_all') }}
          </Link>
        </div>
      </MenuItems>
    </transition>
  </Menu>

  <Menu as="div" class="relative flex items-center w-8 mr-2 md:hidden">
    <Link
        :href="route('member.messages')"
        class="flex relative h-8 w-8 items-center justify-center rounded-full border border-border bg-navbar-item focus:outline-none"
    >
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
           class="w-4 h-4 text-white">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0M3.124 7.5A8.969 8.969 0 0 1 5.292 3m13.416 0a8.969 8.969 0 0 1 2.168 4.5"/>
      </svg>
      <span v-show="data.totalUnreadMessage > 0"
            class="absolute flex items-center justify-center w-5 h-5 text-xs text-white border border-white rounded-full -top-2 -right-2 bg-meron">
        {{ data.totalUnreadMessage}}
      </span>
    </Link>
  </Menu>

  <MessagePreview
      v-model="isPreviewOpen"
      :content="messageContent"
      :time-humans="messageTimeHumans"
  />

</template>

<script>
import {ref} from 'vue';
import {
  Menu,
  MenuButton,
  MenuItems,
  MenuItem,
} from '@headlessui/vue';
import {Link} from '@inertiajs/inertia-vue3';
import MessagePreview from '@/components/MessagePreview';

export default {
  props: {
    data: Object,
  },

  components: {
    Link,
    Menu,
    MenuButton,
    MenuItems,
    MenuItem,
    MessagePreview,
  },

  setup(props) {
    const isOpen = ref(false);
    const isPreviewOpen = ref(false);

    const messageContent = ref('');
    const messageTimeHumans = ref('');

    const closeModal = () => {
      isOpen.value = false;
    };

    const openModal = () => {
      isOpen.value = true;
    };

    const updateMessageData = (id) => {
      props.data.totalUnreadMessage = props.data.totalUnreadMessage - 1;

      const message = props.data.messages.find(x => x.id === id);

      if (!message) return;

      message.unread = false;
    };

    const brocastReadOnMessage = (id) => {
      EventBus.emit('readOnMessage', id);
    };

    const markMessageAsRead = async (id) => {
      await axios.post(`/message/${id}/mark-as-read`);

      brocastReadOnMessage(id);
    };

    const openPreviewModal = (message) => {
      if (message.unread) {
        markMessageAsRead(message.id);
      }

      messageContent.value = message.content;
      messageTimeHumans.value = message.timeHumans;
      isPreviewOpen.value = true;

      closeModal();
    };

    EventBus.on('readOnMessage', (id) => updateMessageData(id));

    return {
      openPreviewModal,
      openModal,
      messageContent,
      messageTimeHumans,
      isPreviewOpen,
      isOpen
    };
  }
};
</script>