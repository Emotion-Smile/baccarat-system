<template>
    <!-- <div
      v-if="showFightNumber"
      :class="[user.type === 'trader' ? 'top-1' : 'top-12']"
      class="absolute right-1 h-8 xl:right-[0.70rem] w-20 py-1 lg:w-28 lg:py-1.5 xl:py-[0.40rem] xl:text-base xl:h-12 -translate-y-2 xl:-translate-y-1 xl:w-36 font-bold rounded text-xs z-20 bg-black text-white inline-flex items-center justify-center"
    >
      FIGHT #: {{ fightNumber }}
    </div> -->

    <div class="relative">
        <div
            v-if="showFightNumber"
            class="absolute top-0 z-10 w-full"
        >
            <div class="w-full h-2 bg-black xl:h-3"/>
            <div class="flex items-start justify-between">
                <div
                    class="w-20 md:w-24 lg:w-32 xl:w-56 flex items-center justify-center h-0 border-t-[1.5rem] border-r-[0.5rem] md:border-t-[2rem] lg:border-t-[2.5rem] lg:border-r-[0.8rem] xl:border-t-[4.5rem] xl:border-r-[2rem] border-black border-r-transparent transition-all duration-150 ease-in"
                >
                    <div
                        class="w-full -translate-y-4 xl:px-2 lg:px-1 md:-translate-y-5 lg:-translate-y-6 xl:-translate-y-10">
                        <div
                            v-if="streamingLogo"
                            class="w-full h-4 md:h-6 lg:h-7 xl:h-12"
                        >
                            <img
                                :src="streamingLogo"
                                alt="streaming-logo"
                            >
                        </div>
                        <div
                            class="text-[8px] text-center text-yellow-300 lg:text-[9px] font-bold pt-px lg:pt-1 xl:pt-2 xl:pb-2 xl:text-sm">
                            {{ currentDateTime }}
                        </div>
                    </div>
                </div>

                <div
                    v-if="streamingName"
                    class="border-t-[1rem] flex items-center justify-center border-l-[0.5rem] border-r-[0.5rem] w-24 md:w-28 h-0 lg:w-36 lg:border-l-[0.8rem] lg:border-t-[1.5rem] lg:border-r-[0.8rem] xl:w-56 xl:border-l-[1rem] xl:border-t-[2rem] xl:border-r-[1rem] border-black border-l-transparent border-r-transparent transition-all duration-150 ease-in"
                >
                    <div
                        class="text-[10px] lg:text-sm xl:text-lg text-white font-bold -translate-y-3 lg:-translate-y-4 xl:-translate-y-6">
                        {{ streamingName }}
                    </div>
                </div>

                <div
                    class="w-20 border-t-[1.5rem] flex justify-center items-center border-l-[0.5rem] md:w-28 lg:w-32 lg:border-t-[2.5rem] lg:border-l-[0.8rem] xl:w-48 h-0 xl:border-t-[4.5rem] xl:border-l-[2rem] border-black border-l-transparent transition-all duration-150 ease-in"
                >
                    <div
                        class="w-16 h-5 font-bold uppercase flex items-center justify-center text-[10px] text-black -translate-y-4 bg-yellow-300 rounded-sm lg:w-16 lg:-translate-y-6 xl:w-24 xl:h-7 xl:text-sm xl:-translate-y-10">
                        Fight # {{ fightNumber }}
                    </div>
                </div>
            </div>
        </div>

        <transition
            enter-active-class="transition duration-100 ease-out"
            enter-from-class="transform scale-95 opacity-0"
            enter-to-class="transform scale-100 opacity-100"
            leave-active-class="transition duration-75 ease-in"
            leave-from-class="transform scale-100 opacity-100"
            leave-to-class="transform scale-95 opacity-0"
        >
            <div
                v-show="isVideoControlsShow"
                class="absolute bottom-0 z-10 flex items-center justify-between w-full h-8 xl:h-10 bg-black/80"
            >
                <div class="pl-4 space-x-4">
                    <video-play-control
                        v-if="isPaused != null"
                        :default-value="isPaused"
                        @play-change="handlePlayChange"
                    />
                </div>

                <div class="pr-4 space-x-4">
                    <video-audio-control
                        v-if="isMuted != null"
                        :default-value="isMuted"
                        @audio-change="handleAudioChange"
                    />

                    <video-streaming-link-control
                        v-if="streamingUrl1 && streamingUrl2"
                        :current-link="streamingUrl"
                        :streaming-link-1="streamingUrl1"
                        :streaming-link-2="streamingUrl2"
                        @link-change="handleLinkChange"
                    />
                </div>
            </div>
        </transition>

        <h1
            v-if="showText"
            class="pt-6 text-4xl text-center text-white"
        >
            {{ message }}
        </h1>

        <p
            v-if="showMemberId"
            class="show-member-id"
        >
            {{ user.id }}
        </p>

        <div
            class="z-0 bg-transparent aspect-w-16 aspect-h-9"
        >
            <iframe
                id="myiframe"
                :src="streamingUrl"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope;"
            />

            <div class="bg-transparent"
                 @click.prevent="handleShowVideoControls"
            ></div>

        </div>
    </div>
</template>

<script>
import {onBeforeUnmount, onMounted, ref} from 'vue';
import {usePage} from '@inertiajs/inertia-vue3';
import VideoAudioControl from '@/Components/VideoAudioControl.vue';
import VideoPlayControl from '@/Components/VideoPlayControl.vue';
import VideoStreamingLinkControl from '@/Components/VideoStreamingLinkControl.vue';

export default {
    name: 'VideoStreaming',
    components: {
        VideoAudioControl,
        VideoPlayControl,
        VideoStreamingLinkControl
    },
    props: {
        user: Object,
        fightNumber: String | Number,
        streamingName: String,
        streamingLogo: String
    },
    setup(props) {
        let streamingUrl = ref('');

        let streamingUrl1 = ref('');
        let streamingUrl2 = ref('');

        let showText = ref(false);
        let message = ref('');

        let showMemberId = ref(false);
        let showFightNumber = ref(false);

        const isVideoControlsShow = ref(true);
        const isMuted = ref(null);
        const isPaused = ref(null);
        const intervalTakeIframeControlOptionsValue = ref(null);

        let currentDateTime = ref('');
        let interval = ref(null);

        let date = new Date(usePage().props.value.currentDateTime);


        const showDateTime = () => {
            date.setSeconds(date.getSeconds() + 1);

            const day = date.getDate().toString().padStart(2, '0');
            const month = (date.getMonth() + 1).toString().padStart(2, '0');
            const year = date.getFullYear().toString().slice(-2);
            const min = date.getMinutes().toString().padStart(2, '0');

            let hour = date.getHours();

            let shift = 'AM';

            if (hour > 12) {
                hour -= 12;
                shift = 'PM';
            }

            if (hour == 0) {
                hour = 12;
                shift = 'AM';
            }

            hour = hour.toString().padStart(2, '0');

            currentDateTime.value = `${day}/${month}/${year} ${hour}:${min}${shift}`;
        };

        const handleLinkChange = (link) => {
            streamingUrl.value = link;

            isMuted.value = null;
            isPaused.value = null;

            intervalTakeIframeControlOptionsValue.value = setInterval(takeIframeControlOptionsValue, 1000);
        };

        const autoHideVideoControls = (t = 5000) => {
            setTimeout(() => {
                isVideoControlsShow.value = false;
            }, t);
        };

        const handleShowVideoControls = () => {
            isVideoControlsShow.value = !isVideoControlsShow.value;
        }

        const takeIframeControlOptionsValue = () => {
            const iframe = document.getElementById('myiframe');
            iframe.contentWindow.postMessage('takeControlOptionsValue', '*');

            if (intervalTakeIframeControlOptionsValue.value && isMuted.value != null && isPaused.value != null) {
                clearInterval(intervalTakeIframeControlOptionsValue.value);
            }
        }

        const handleAudioChange = () => {
            const iframe = document.getElementById('myiframe');
            iframe.contentWindow.postMessage('audioChange', '*');
        }

        const handlePlayChange = () => {
            const iframe = document.getElementById('myiframe');
            iframe.contentWindow.postMessage('playChange', '*');
        }

        onMounted(async () => {
            const response = await axios.get(route('env.group'));

            streamingUrl1.value = response.data.streamingLink1;
            streamingUrl2.value = response.data.streamingLink2;
            showFightNumber.value = response.data.showFightNumber;

            streamingUrl.value = streamingUrl1.value;

            if (streamingUrl1.value && streamingUrl2.value) {
                streamingUrl.value = response.data.defaultStreamingLink;
            }

            if (response.data.streamingLink1 === '') {
                showText.value = true;
                message.value = response.data.message;
            }

            Echo.channel(`user.${props.user.environment_id}`)
                .listen('.showMemberId', (payload) => {
                    showMemberId.value = true;
                    setTimeout(() => {
                        showMemberId.value = false;
                    }, 5000);
                })
                .listen('.streaming.reload', (payload) => {
                    window.location.reload();
                })
                .listen(`.streaming.block.${props.user.id}`, (payload) => {
                    window.location.reload();
                });

            autoHideVideoControls();

            interval.value = setInterval(showDateTime, 1000);

            window.addEventListener('message', (event) => {
                const responseDatas = event.data;

                if (responseDatas.action == 'control-option-value') {
                    isMuted.value = responseDatas.isMuted;
                    isPaused.value = responseDatas.isPaused;
                }
            }, false);

            intervalTakeIframeControlOptionsValue.value = setInterval(takeIframeControlOptionsValue, 1000);
        });

        onBeforeUnmount(() => {
            clearInterval(interval.value);
        });

        return {
            streamingUrl,
            showText,
            showMemberId,
            message,
            showFightNumber,
            currentDateTime,
            streamingUrl1,
            streamingUrl2,
            handleLinkChange,
            isVideoControlsShow,
            handleShowVideoControls,
            handleAudioChange,
            handlePlayChange,
            isMuted,
            isPaused
        };
    },
};
</script>

<style scoped>
.show-member-id {
    width: 50px;
    height: 23px;
    text-align: center;
    position: absolute;
    z-index: 9999999;
    background: #ffffa4;
    color: black;
    opacity: 0.8;
    left: 50%;
    top: 30%;
}
</style>
