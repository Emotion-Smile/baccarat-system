<script setup>
import {useUserStore} from '@/Stores/DragonTigers/UserStore';
import {computed} from 'vue';
const userStore = useUserStore();

const dragon = computed(()=>userStore.scoreboardCount.dragon??0);
const tiger = computed(()=>userStore.scoreboardCount.tiger??0);
const tie = computed(()=>userStore.scoreboardCount.tie??0);
const cancel = computed(()=>userStore.scoreboardCount.cancel??0);
</script>

<template>
    <div class="overflow-hidden rounded-md mt-1">
        <div class="flex h-[4.5rem] flex-none items-stretch bg-white">
            <div
                class="flex h-full w-1/4 flex-col items-center justify-between py-2"
            >
                <div
                    class="flex h-8 w-8 items-center justify-center rounded-full border-2 border-dragon-red bg-dragon-dragon text-xs font-bold text-white"
                >
                    {{ dragon }}
                </div>
                <span class="text-sm">Dragon</span>
            </div>
            <div
                class="flex h-full w-1/4 flex-col items-center justify-between py-2"
            >
                <div
                    class="flex h-8 w-8 items-center justify-center rounded-full border-2 border-dragon-blue bg-dragon-tiger text-xs font-bold text-white"
                >
                    {{ tiger }}
                </div>
                <span class="text-sm">Tiger</span>
            </div>
            <div
                class="flex h-full w-1/4 flex-col items-center justify-between py-2"
            >
                <div
                    class="flex h-8 w-8 items-center justify-center rounded-full border-2 border-dragon-green bg-dragon-teal text-xs font-bold text-white"
                >
                    {{ tie }}
                </div>
                <span class="text-sm">Tie</span>
            </div>
            <div
                class="flex h-full w-1/4 flex-col items-center justify-between py-2"
            >
                <div
                    class="flex h-8 w-8 items-center justify-center rounded-full border-2 border-gray-600 bg-gray-500 text-xs font-bold text-white"
                >
                    {{ cancel }}
                </div>
                <span class="text-sm">Cancel</span>
            </div>
        </div>
    </div>
</template>
