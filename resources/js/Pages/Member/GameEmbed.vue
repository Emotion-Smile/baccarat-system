<script setup>
import { onMounted, ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout';
import GameEmbedLoading from '@/Components/GameEmbedLoading';

const props = defineProps({
    game: String,
    link: String
});

const loading = ref(true);

onMounted(() => {
    document.getElementById('game-frame').addEventListener('load', () => {
        loading.value = false;
    });
});
</script>

<template>
    <app-layout :title="game">
        <game-embed-loading
            :loading="loading" 
            v-slot="{ loaded }"
        >
            <iframe 
                v-show="loaded"
                :src="link"
                class="flex-1"
                id="game-frame"
            >
            </iframe>
        </game-embed-loading>
    </app-layout>
</template>