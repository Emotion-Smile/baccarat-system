<template>
    <Head title="Log in"/>

    <jet-authentication-card :background-images="backgroundImages">
        <template #logo>
            <jet-authentication-card-logo
                :logo="logo"
            />
        </template>

        <jet-validation-errors class="mb-4"/>

        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <jet-label for="name" value="Username"/>
                <jet-input
                    id="name"
                    v-model="form.name"
                    type="text"
                    class="block w-full mt-1"
                    required
                    autofocus
                />
            </div>

            <div class="mt-6">
                <jet-label for="password" value="Password"/>
                <jet-input
                    id="password"
                    v-model="form.password"
                    type="password"
                    class="block w-full mt-1"
                    required
                    autocomplete="current-password"
                />
            </div>

            <div class="flex items-center justify-between block mt-4">
                <label class="flex items-center">
                    <jet-checkbox
                        v-model:checked="form.remember"
                        name="remember"
                    />
                    <span class="ml-2 text-sm text-gray-600">Remember me</span>
                </label>
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="text-sm text-gray-600 underline hover:text-gray-900"
                >
                    Forgot your password?
                </Link>
            </div>

            <div class="flex items-center justify-end my-6">
                <jet-button
                    class="justify-center w-full"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Sign In
                </jet-button>
            </div>

            <!--            <div class="flex items-center justify-center">-->
            <!--                <div @click="setLocale('en')" class="flex mr-6 cursor-pointer">-->
            <!--                    <img class="w-6" :src="asset('images/united-states-of-america.png')" alt="">-->
            <!--                    <span class="pl-1">English</span>-->
            <!--                </div>-->
            <!--                <div @click="setLocale('km')" class="flex cursor-pointer">-->
            <!--                    <img class="w-6" :src="asset('images/cambodia.png')" alt="">-->
            <!--                    <span class="pl-1">Khmer</span>-->
            <!--                </div>-->
            <!--            </div>-->
        </form>
    </jet-authentication-card>

    <!-- <div
    class="fixed bottom-0 flex flex-col items-center justify-between w-full px-3 py-3 text-white md:flex-row backdrop-filter backdrop-blur-3xl"
  >
    <div />

    <div class="inline-flex items-center space-x-3">
      <svg
        aria-hidden="true"
        focusable="false"
        data-prefix="fab"
        data-icon="facebook"
        class="w-6 h-6"
        role="img"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 512 512"
      >
        <path
          fill="currentColor"
          d="M504 256C504 119 393 8 256 8S8 119 8 256c0 123.78 90.69 226.38 209.25 245V327.69h-63V256h63v-54.64c0-62.15 37-96.48 93.67-96.48 27.14 0 55.52 4.84 55.52 4.84v61h-31.28c-30.8 0-40.41 19.12-40.41 38.73V256h68.78l-11 71.69h-57.78V501C413.31 482.38 504 379.78 504 256z"
        />
      </svg>
      <span>Cock Fight Online</span>
    </div>
  </div> -->
</template>

<script>
import {defineComponent} from "vue";
import JetAuthenticationCard from "@/Jetstream/AuthenticationCard.vue";
import JetAuthenticationCardLogo from "@/Jetstream/AuthenticationCardLogo.vue";
import JetButton from "@/Jetstream/Button.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetCheckbox from "@/Jetstream/Checkbox.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetValidationErrors from "@/Jetstream/ValidationErrors.vue";
import {Head, Link} from "@inertiajs/inertia-vue3";
import {Inertia} from "@inertiajs/inertia";

export default defineComponent({
    components: {
        Head,
        JetAuthenticationCard,
        JetAuthenticationCardLogo,
        JetButton,
        JetInput,
        JetCheckbox,
        JetLabel,
        JetValidationErrors,
        Link,
    },
    props: {
        logo: String,
        status: String,
        backgroundImages: Object,
        canResetPassword: Boolean,
    },
    data() {
        return {
            form: this.$inertia.form({
                name: "",
                email: "",
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
                    onSuccess: () => Inertia.get('/')
                });
        },
        setLocale(locale) {
            Inertia.post(
                route("locale"),
                {
                    lang: locale,
                },
                {
                    onFinish: () => window.location.reload(),
                }
            );
        },
    },
});
</script>
