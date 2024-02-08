<script setup>
import Profile from '@/Pages/DragonTiger/Components/Trader/Profile.vue';
</script>

<template>
    <div class="min-h-screen bg-dragon-bg">
        <slot name="navbar">
            <div
                class="flex h-12 shrink-0 items-center gap-x-6 border-b border-white/5 bg-dragon-nav px-4 shadow-sm sm:px-6 lg:px-8"
            >
                <div
                    class="flex flex-1 items-center justify-end gap-x-4 self-stretch lg:gap-x-6"
                >
                    <profile />
                </div>
            </div>
        </slot>

        <main class="px-6 py-3">
            <slot />
        </main>
    </div>
</template>

<style scoped></style>
