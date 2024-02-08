<template>
    <div class="flex flex-none items-center gap-1 pr-3">
        <button
            class="flex h-8 w-8 items-center justify-center rounded-full border border-border bg-navbar-item"
            @click="toggleSound"
        >
            <svg
                v-if="!userStore.isSoundPlay"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="h-4 w-4 text-white"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M17.25 9.75 19.5 12m0 0 2.25 2.25M19.5 12l2.25-2.25M19.5 12l-2.25 2.25m-10.5-6 4.72-4.72a.75.75 0 0 1 1.28.53v15.88a.75.75 0 0 1-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.009 9.009 0 0 1 2.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75Z"
                />
            </svg>
            <svg
                v-else
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="h-4 w-4 text-white"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M19.114 5.636a9 9 0 0 1 0 12.728M16.463 8.288a5.25 5.25 0 0 1 0 7.424M6.75 8.25l4.72-4.72a.75.75 0 0 1 1.28.53v15.88a.75.75 0 0 1-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.009 9.009 0 0 1 2.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75Z"
                />
            </svg>
        </button>
        <button
            class="flex h-8 w-8 items-center justify-center rounded-full border border-border bg-navbar-item relative"
            @click="toggleMusic"
        >
            <div v-if="!userStore.music.playing()" class="absolute w-full h-[2px] bg-white origin-center transform rotate-45 left-0 top-3 scale-75"></div>
            <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="h-4 w-4 text-white"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="m9 9 10.5-3m0 6.553v3.75a2.25 2.25 0 0 1-1.632 2.163l-1.32.377a1.803 1.803 0 1 1-.99-3.467l2.31-.66a2.25 2.25 0 0 0 1.632-2.163Zm0 0V2.25L9 5.25v10.303m0 0v3.75a2.25 2.25 0 0 1-1.632 2.163l-1.32.377a1.803 1.803 0 0 1-.99-3.467l2.31-.66A2.25 2.25 0 0 0 9 15.553Z"
                />
            </svg>
        </button>
    </div>
</template>

<script setup>
import { useUserStore } from '@/Stores/DragonTigers/UserStore';
import { useGameStore } from '@/Stores/DragonTigers/GameStore';
import { useStorage } from '@vueuse/core';
import {Howl} from 'howler';
const userStore = useUserStore();
const gameStore = useGameStore();
const playList = ['music01', 'music02', 'music03', 'music04', 'music05'];
let currentTrackIndex = 0;

const start = new Howl({
    src: window.route('music.track', {fileName: 'start'}),
    xhr: { method: 'GET' },
    volume: 1.0,
    onload: function(){
        if (window.AudioContext || window.webkitAudioContext) {
            const audioContext = new (window.AudioContext || window.webkitAudioContext)();
            userStore.$patch({isSoundPlay: audioContext.state !== 'suspended'});
        }
        else {
            alert('Audio is not supported in this browser.');
        }
    },
    format: ['webm', 'mp3'],
});

const stop = new Howl({
    src: window.route('music.track', {fileName: 'stop'}),
    xhr: { method: 'GET' },
    volume: 1.0,
    format: ['webm', 'mp3'],
});


function initHowler() {
    userStore.music = new Howl({
        src: window.route('music.track', {fileName: `${playList[currentTrackIndex]}`}),
        xhr: { method: 'GET' },
        volume: 0.5,
        onload: function(){
            if (window.AudioContext || window.webkitAudioContext) {
                const audioContext = new (window.AudioContext || window.webkitAudioContext)();
                userStore.$patch({isMusicPlay: audioContext.state !== 'suspended' && userStore.music.playing()});
            }
            else {
                console.error('Audio is not supported in this browser.');
            }
        },
        onplayerror: function() {
            userStore.music.once('unlock', function() {
                if(!userStore.music.playing()) userStore.music.play();
            });
        },
        format: ['webm', 'mp3'],
        onend: playNextTrack
    });
}

playCurrentTrack();

// Function to play the current track
function playCurrentTrack() {
    if (!userStore.music) {
        initHowler();
    }
    if(!userStore.music.playing()) userStore.music.play();
}

// Function to pause the current track
function pauseCurrentTrack() {
    if (userStore.music) {
        userStore.music.pause();
    }
}

// Function to stop and reset the current track
function stopCurrentTrack() {
    if (userStore.music) {
        userStore.music.stop();
        userStore.music.unload();
    }
}

// Function to play the next track in the playlist
function playNextTrack() {
    stopCurrentTrack(); // Stop and unload the current track
    currentTrackIndex = (currentTrackIndex + 1) % playList.length;
    initHowler();
    userStore.music.play();
}

function toggleSound() {
    setTimeout(()=>userStore.$patch({isSoundPlay: !userStore.isSoundPlay}), 500);
}
function toggleMusic() {
    if (userStore.music.playing()){
        stopCurrentTrack();
    }
    else playNextTrack();
}

gameStore.$subscribe((mutation, state) => {
    if (!userStore.isSoundPlay && mutation.payload?.isStartBetting){
        state.isStartBetting = false;
    }

    if (userStore.isSoundPlay){
        const startBetting = useStorage('startBetting', {});
        const stopBetting = useStorage('stopBetting', {});

        if (state.isStartBetting && !startBetting.value.isPlayed && (state.game.gameNumber !== '#' &&  startBetting.value.gameNumber === state.game.gameNumber) ){
            start.play();
            startBetting.value.isPlayed = true;
            state.isStartBetting = false;
        }

        if (state.game.bettingInterval - 1 < 0){
            stopBetting.value.isPlayed= true;
        }

        if (!stopBetting.value.isPlayed && (stopBetting.value.gameNumber === state.game.gameNumber && state.gameNumber !== '#') && state.game.bettingInterval === 1 ){
            stop.play();
            stopBetting.value.isPlayed= true;
        }
    }
});

</script>

<style></style>
