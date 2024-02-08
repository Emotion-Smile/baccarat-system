<template>
    <Head title="Log in" />

    <div class="h-screen overflow-hidden bg-bg flex flex-col">
        <div class="h-full px-3 pb-12 space-y-6 overflow-y-auto flex-1 pt-6">
            <div class="w-full mx-auto sm:max-w-md">
                <img
                    v-if="logo.url"
                    :class="`w-${logo.width || 'full'}`"
                    :src="logo.url"
                    alt="logo"
                    class="mx-auto mb-5"
                />

                <div
                    class="relative overflow-hidden rounded bg-login-form sm:rounded-lg"
                >
                    <div
                        class="px-6 py-4 text-sm sm:text-base font-bold space-x-2 text-white border-l-4 border-[#C5B627] uppercase"
                    >
                        login to your account
                    </div>

                    <form
                        @submit.prevent="submit"
                        class="p-6 space-y-4 border-t border-gray-500/50"
                    >
                        <jet-validation-errors class="mb-4" />

                        <div>
                            <label
                                for="username"
                                class="block font-medium text-sm text-[#9f9f9f]"
                            >
                                Username
                            </label>
                            <input
                                v-model="form.name"
                                type="text"
                                class="border-gray-300 focus:border-[#c5b627] focus:ring focus:ring-[#c5b627] rounded-md shadow-sm block w-full mt-1"
                                required
                                autofocus
                            />
                        </div>
                        <div>
                            <label
                                for="password"
                                class="block font-medium text-sm text-[#9f9f9f]"
                            >
                                Password
                            </label>
                            <input
                                v-model="form.password"
                                type="password"
                                class="border-gray-300 focus:border-[#c5b627] focus:ring focus:ring-[#c5b627] rounded-md shadow-sm block w-full mt-1"
                                required
                            />
                        </div>
                        <div class="flex items-center justify-start">
                            <label class="flex items-center">
                                <input
                                    v-model="form.remember"
                                    type="checkbox"
                                    name="remember"
                                    class="border-gray-300 rounded shadow-sm text-status-head focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                />
                                <span class="ml-2 text-sm text-[#C5B627]"
                                    >Remember me</span
                                >
                            </label>
                        </div>
                        <button
                            class="inline-flex items-center justify-center w-full px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition border border-transparent rounded-md bg-login-button hover:bg-login-button/70 active:bg-login-button/70 focus:outline-none disabled:opacity-25"
                        >
                            Sign in
                        </button>
                    </form>
                </div>
            </div>

            <div
                v-if="promotionImages.length > 0"
                id="indicators-carousel"
                class="relative w-full mx-auto sm:max-w-md"
                data-carousel="slide"
            >
                <div class="relative overflow-hidden h-28">
                    <div
                        v-for="(promotionImage, index) in promotionImages"
                        :key="index"
                        class="absolute inset-0 z-20 transition-all duration-700 ease-in-out transform translate-x-0"
                        :data-carousel-item="index === 0 ? 'active' : ''"
                    >
                        <img
                            :src="promotionImage"
                            class="absolute block w-full h-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2"
                            :alt="`slide-${index}`"
                        />
                    </div>
                </div>
            </div>

            <div
                v-if="
                    phoneNumbers.length > 0 ||
                    socials.telegram ||
                    socials.facebook
                "
                class="relative w-full mx-auto overflow-hidden rounded bg-login-form sm:max-w-md sm:rounded-lg"
            >
                <div
                    class="px-6 py-4 font-bold text-sm sm:text-base space-x-2 text-white border-l-4 border-[#C5B627]"
                >
                    <span>DON’T HAVE AN ACCOUNT YET?</span>
                    <span class="text-[#C5B627]"
                        ><a href="javascipt:void(0);">CONTACT US</a></span
                    >
                </div>
                <div class="p-3 border-t sm:p-6 border-gray-500/50">
                    <div class="grid grid-cols-2 gap-1 mb-3 sm:gap-4">
                        <div v-if="phoneNumbers.length > 0" class="space-y-2">
                            <a
                                v-for="(phoneNumber, index) in phoneNumbers"
                                :key="index"
                                :href="`tel:${phoneNumber}`"
                                class="flex items-center text-white text-sm font-medium justify-between rounded-lg bg-[#333F4B] py-1 px-2"
                            >
                                <img
                                    class="h-6 sm:h-8"
                                    :src="asset('images/call.svg')"
                                    alt="call"
                                />
                                <span>{{ formatPhoneNumber(phoneNumber) }}</span>
                                <img
                                    class="h-4 sm:h-6"
                                    :src="
                                        asset(
                                            `images/${phoneNumberCompanyType(
                                                phoneNumber
                                            )}.svg`
                                        )
                                    "
                                    :alt="phoneNumberCompanyType(phoneNumber)"
                                />
                            </a>
                        </div>
                        <div
                            v-if="socials.telegram || socials.facebook"
                            class="space-y-2"
                        >
                            <a
                                v-if="socials.telegram"
                                :href="socials.telegram"
                                target="__blank"
                                class="flex space-x-2 flex-auto w-32 items-center text-white font-medium rounded-lg bg-[#333F4B] py-1 px-2"
                            >
                                <img
                                    class="h-6 sm:h-8"
                                    :src="asset('images/telegram.svg')"
                                    alt="telegram"
                                />
                                <span>Telegram</span>
                            </a>
                            <a
                                v-if="socials.facebook"
                                :href="socials.facebook"
                                target="__blank"
                                class="flex space-x-2 flex-auto w-32 items-center text-white font-medium rounded-lg bg-[#333F4B] py-1 px-2"
                            >
                                <img
                                    class="h-6 sm:h-8"
                                    :src="asset('images/facebook.svg')"
                                    alt="facbook"
                                />
                                <span>Facebook</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div 
            v-if="copyrightText" 
            class="mt-auto py-2 text-white text-center text-sm lg:text-base"
        >
            &copy; {{ copyrightText }}
        </div>
    </div>
    
</template>

<script>
import { defineComponent } from "vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import JetValidationErrors from "@/Jetstream/ValidationErrors.vue";

export default defineComponent({
    props: {
        logo: Object,
        promotionImages: Array,
        phoneNumbers: Array,
        socials: Object,
        themeColor: String,
        copyrightText: String
    },

    components: {
        Head,
        Link,
        JetValidationErrors,
    },

    data() {
        return {
            form: this.$inertia.form({
                name: "",
                password: "",
                remember: false,
            }),
        };
    },

    methods: {
        submit() {
            this.form
                .transform((data) => ({
                    ...data,
                    remember: this.form.remember ? "on" : "",
                }))
                .post(this.route("login"), {
                    onFinish: () => this.form.reset("password"),
                });
        },

        phoneNumberCompanyType(phoneNumber) {
            const first3Digit = phoneNumber.slice(0, 3);

            const cellcardFirst3Digit = [
                "011",
                "012",
                "014",
                "017",
                "061",
                "076",
                "077",
                "078",
                "085",
                "089",
                "092",
                "095",
                "099",
            ];

            const smartFirst3Digit = [
                "010",
                "015",
                "016",
                "069",
                "070",
                "081",
                "086",
                "087",
                "093",
                "096",
                "098",
            ];

            const metfoneFirst3Digit = [
                "088",
                "097",
                "071",
                "031",
                "060",
                "066",
                "067",
                "068",
                "090",
            ];

            let result = "";

            if (cellcardFirst3Digit.includes(first3Digit)) result = "cellcard";

            if (smartFirst3Digit.includes(first3Digit)) result = "smart";

            if (metfoneFirst3Digit.includes(first3Digit)) result = "metfone";

            return result;
        },

        formatPhoneNumber(phoneNumberString) {
            const cleaned = phoneNumberString.toString().replace(/\D/g, '');
            
            const match = cleaned.match(/^(\d{3})(\d{3})(\d{3,4})$/);
            
            if (!match) return phoneNumberString;

            return match[1] + ' ' + match[2] + ' ' + match[3];
        }

    },

    mounted() {
        if(this.themeColor) {
            document.body.classList.add(this.themeColor);
        }     
    }
});
</script>
