<template>
    <div
        class="relative flex flex-col items-center justify-center min-h-screen pt-6 bg sm:pt-0"
    >
        <!-- <div class="flex items-center justify-center w-40 h-40 transform translate-y-24 bg-white rounded-full">
            <slot name="logo" />
        </div> -->

        <!-- <img
            v-if="hasBackgroundImage"
            class="absolute inset-0 bg-red-600"
            :src="backgroundImages.desktop"
            alt="background"
        > -->

        <div v-if="hasBackgroundImage" class="absolute inset-0">
            <img
                class="w-full h-full web:hidden"
                :src="backgroundImages.mobile"
                alt="background-mobile"
            />
            <img
                class="hidden w-full h-full ip6:hidden ip6p:hidden web:block"
                :src="backgroundImages.desktop"
                alt="background-desktop"
            />
            <img
                class="hidden w-full h-full ip6:block ip6p:hidden web:hidden"
                :src="backgroundImages.ip6"
                alt="background-ip6"
            />
            <img
                class="hidden w-full h-full ip6p:hidden ip6:hidden web:hidden"
                :src="backgroundImages.ip6p"
                alt="background-ip6p"
            />
        </div>

        <div
            class="relative w-[90%] rounded-xl px-6 pt-20 pb-4 bg-white shadow-md mt-36 lg:mt-0 -top-16 sm:max-w-md sm:rounded-lg"
        >
            <div
                class="absolute inset-0 flex items-center justify-center w-32 h-32 mx-auto transform -translate-y-16"
            >
                <!-- bg-white rounded-full -->
                <slot name="logo"/>
            </div>
            
            <slot/>
        </div>
    </div>
</template>

<script setup>
import {computed} from "vue";

const props = defineProps({
    backgroundImages: {
        type: Object,
        default: {},
    },
});

const hasBackgroundImage = computed(() => {
    const backgroundImages = Object.values(props.backgroundImages);

    if (backgroundImages.length === 0) return false;

    return backgroundImages.every((item) => item);
});
</script>
