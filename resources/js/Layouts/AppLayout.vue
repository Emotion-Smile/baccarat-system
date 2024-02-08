<template>
    <div>
        <Head :title="title"/>

        <JetBanner/>

        <div class="bg flex min-h-screen flex-col">
            <nav class="bg-navbar sticky top-0 z-50">
                <!-- Primary Navigation Menu -->
                <div class="mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex h-12 justify-between">
                        <div
                            class="relative mr-3 flex items-center justify-center"
                        >
                            <JetDropdown
                                align="left"
                                width="48"
                                content-classes="bg-fill"
                            >
                                <template #trigger>
                                    <span class="inline-flex rounded-md">
                                        <button
                                            type="button"
                                            class="inline-flex items-center rounded-md border border-transparent bg-transparent px-3 py-2 text-sm font-medium leading-4 text-navbar-item-text transition hover:text-wala focus:outline-none"
                                        >
                                            <img
                                                v-if="
                                                    $page.props.locale === 'en'
                                                "
                                                class="w-6"
                                                :src="
                                                    asset(
                                                        'images/united-states-of-america.png'
                                                    )
                                                "
                                                alt=""
                                            />

                                            <img
                                                v-if="
                                                    $page.props.locale === 'km'
                                                "
                                                class="w-6"
                                                :src="
                                                    asset('images/cambodia.png')
                                                "
                                                alt=""
                                            />

                                            <img
                                                v-if="
                                                    $page.props.locale === 'th'
                                                "
                                                class="w-6"
                                                :src="
                                                    asset('images/thailand.png')
                                                "
                                                alt=""
                                            />

                                            <img
                                                v-if="
                                                    $page.props.locale === 'vi'
                                                "
                                                class="w-6"
                                                :src="
                                                    asset('images/vietnam.png')
                                                "
                                                alt=""
                                            />
                                        </button>
                                    </span>
                                </template>

                                <template #content>
                                    <!-- Account Management -->
                                    <div
                                        class="block px-4 py-2 text-xs text-gray-400"
                                    >
                                        {{ __('change_language') }}
                                    </div>

                                    <JetDropdownLink
                                        href="#"
                                        @click="setLocale('km')"
                                    >
                                        <img
                                            class="mr-3 inline w-6"
                                            :src="asset('images/cambodia.png')"
                                            alt=""
                                        />
                                        <span>ខ្មែរ/Khmer</span>
                                    </JetDropdownLink>

                                    <JetDropdownLink
                                        href="#"
                                        @click="setLocale('en')"
                                    >
                                        <img
                                            class="mr-3 inline w-6"
                                            :src="
                                                asset(
                                                    'images/united-states-of-america.png'
                                                )
                                            "
                                            alt=""
                                        />
                                        <span>អង់គ្លេស/English</span>
                                    </JetDropdownLink>

                                    <JetDropdownLink
                                        href="#"
                                        @click="setLocale('th')"
                                    >
                                        <img
                                            class="mr-3 inline w-6"
                                            :src="asset('images/thailand.png')"
                                            alt=""
                                        />
                                        <span>ថៃ/Thai</span>
                                    </JetDropdownLink>

                                    <JetDropdownLink
                                        href="#"
                                        @click="setLocale('vi')"
                                    >
                                        <img
                                            class="mr-3 inline w-6"
                                            :src="asset('images/vietnam.png')"
                                            alt=""
                                        />
                                        <span>វៀតណាម/Vietnam</span>
                                    </JetDropdownLink>
                                </template>
                            </JetDropdown>
                        </div>
                        <div class="flex items-center space-x-3">
                            <template v-if="$page.props.user.type === 'trader'">
                                <Link
                                    :href="route('accept-ticket')"
                                    class="w-30 inline-flex w-32 items-center justify-center space-x-2 rounded-full border border-border bg-black px-4 py-2 text-sm font-medium text-white shadow-sm focus:outline-none"
                                >
                                    <span>{{ __('accept_ticket') }}</span>
                                </Link>

                                <Link
                                    v-if="$page.props.user.group_id !== 3"
                                    :href="route('open-bet')"
                                    class="w-30 inline-flex w-32 items-center justify-center space-x-2 rounded-full border border-border bg-black px-4 py-2 text-sm font-medium text-white shadow-sm focus:outline-none"
                                >
                                    <span>{{ __('open_bet') }}</span>
                                </Link>

                                <Link
                                    v-if="$page.props.user.group_id === 3"
                                    :href="route('boxing')"
                                    class="w-30 inline-flex w-32 items-center justify-center space-x-2 rounded-full border border-border bg-black px-4 py-2 text-sm font-medium text-white shadow-sm focus:outline-none"
                                >
                                    <span>{{ __('open_bet') }}</span>
                                </Link>
                            </template>

                            <template v-if="$page.props.user.type === 'member'">
                                <!-- <Link
                                  :href="route('member')"
                                  class="inline-flex items-center justify-center w-32 px-4 py-2 space-x-2 text-sm font-medium text-white bg-black border rounded-full shadow-sm w-30 border-border focus:outline-none sm:hidden"
                                >

                                  <span>{{ $page.props.user.name }}</span>
                                </Link> -->

                                <Menu
                                    as="div"
                                    class="relative hidden text-left sm:inline-block"
                                >
                                    <div>
                                        <MenuButton
                                            class="inline-flex w-40 justify-center space-x-2 rounded-full border border-border bg-navbar-item px-4 py-2 text-sm font-medium text-white shadow-sm focus:outline-none"
                                        >
                                            <img
                                                class="h-5"
                                                :src="
                                                    asset(
                                                        'images/icons/deposit.svg'
                                                    )
                                                "
                                                alt="deposit"
                                            />
                                            <span>{{ __('statement') }}</span>
                                        </MenuButton>
                                    </div>

                                    <transition
                                        enter-active-class="transition duration-100 ease-out"
                                        enter-from-class="transform scale-95 opacity-0"
                                        enter-to-class="transform scale-100 opacity-100"
                                        leave-active-class="transition duration-75 ease-in"
                                        leave-from-class="transform scale-100 opacity-100"
                                        leave-to-class="transform scale-95 opacity-0"
                                    >
                                        <MenuItems
                                            class="top absolute right-0 mt-0 w-40 origin-top-right rounded-md bg-black shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                        >
                                            <div class="py-1">
                                                <MenuItem v-slot="{ active }">
                                                    <Link
                                                        :href="route(routePrefix+'.betting.history', {_query: {date:'today' }})"
                                                        :class="[
                                                            active
                                                                ? 'bg-gray-100 text-gray-900'
                                                                : 'text-white',
                                                            'flex items-center px-2 py-2 text-sm',
                                                        ]"
                                                    >
                                                        <img
                                                            :src="
                                                                asset(
                                                                    'images/icons/history.svg'
                                                                )
                                                            "
                                                            class="mr-2"
                                                            alt="history"
                                                        />
                                                        {{ __( 'betting_history' ) }}
                                                    </Link>
                                                </MenuItem>
                                                <MenuItem v-slot="{ active }">
                                                    <Link
                                                        :href="route(routePrefix+'.deposit') + '?date=today'"
                                                        :class="[
                                                            active
                                                                ? 'bg-gray-100 text-gray-900'
                                                                : 'text-white',
                                                            'flex px-2 py-2 text-center text-sm',
                                                        ]"
                                                    >
                                                        <img
                                                            :src="
                                                                asset(
                                                                    'images/icons/bill.svg'
                                                                )
                                                            "
                                                            class="mr-2"
                                                            alt="bill"
                                                        />
                                                        {{
                                                            __(
                                                                'deposit_withdraw'
                                                            )
                                                        }}
                                                    </Link>
                                                </MenuItem>
                                            </div>
                                        </MenuItems>
                                    </transition>
                                </Menu>
                                <Link
                                    :href="route(routePrefix+'.feedback')"
                                    class="md:inline-flex w-40 justify-center space-x-2 rounded-full border border-border bg-navbar-item px-4 py-2 text-sm font-medium text-white shadow-sm focus:outline-none hidden"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-border"
                                    >
                                        <path
                                            stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 01-.923 1.785A5.969 5.969 0 006 21c1.282 0 2.47-.402 3.445-1.087.81.22 1.668.337 2.555.337z"
                                        />
                                    </svg>
                                    <span>{{ __('feedback') }}</span>
                                </Link>
                            </template>
                        </div>


<!--                        <Message :data="messages"/>-->
                        <div class="hidden sm:flex sm:items-center sm:ml-auto">
                            <!-- Settings Dropdown -->
                            <div class="relative ml-3">
                                <JetDropdown
                                    align="right"
                                    width="48"
                                    content-classes="bg-fill"
                                >
                                    <template #trigger>
                                        <button
                                            v-if=" $page.props.jetstream .managesProfilePhotos"
                                            class="flex rounded-full border-2 border-transparent text-sm transition focus:border-gray-300 focus:outline-none"
                                        >
                                            <img
                                                class="h-8 w-8 rounded-full object-cover"
                                                :src=" $page.props.user.profile_photo_url"
                                                :alt="$page.props.user.name"
                                            />
                                        </button>

                                        <span
                                            v-else
                                            class="inline-flex rounded-md"
                                        >
                                            <button
                                                type="button"
                                                class="inline-flex items-center rounded-md border border-transparent bg-transparent px-3 py-2 text-sm font-medium leading-4 text-navbar-item-text transition hover:text-wala focus:outline-none"
                                            >
                                                {{ $page.props.user.name }}

                                                <svg
                                                    class="ml-2 -mr-0.5 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <!-- Account Management -->
                                        <div
                                            class="block px-4 py-2 text-xs text-gray-400"
                                        >
                                            {{ __('manage_account') }}
                                        </div>

                                        <JetDropdownLink
                                            :href="route('profile.show')"
                                        >
                                            {{ __('profile') }}
                                        </JetDropdownLink>

                                        <div class="border-t border-gray-100"/>

                                        <!-- Authentication -->
                                        <form @submit.prevent="logout">
                                            <JetDropdownLink as="button">
                                                {{ __('logout') }}
                                            </JetDropdownLink>
                                        </form>
                                    </template>
                                </JetDropdown>
                            </div>
                        </div>
                        <Message :data="messages"/>
                        <slot name="sound"/>
                        <button
                            v-if="$page.props.user.type === 'member'"
                            type="button"
                            class="my-1 inline-flex items-center justify-center space-x-3 rounded-full border border-border bg-navbar-item px-6 py-1.5 text-sm text-white sm:ml-2"
                            @click="refreshBalance"
                        >
                            <img
                                class="h-4"
                                :src="asset('images/icons/coin-stack.svg')"
                                alt=""
                            />
                            <span class="font-medium">{{ balance }}</span>
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                :class="{ 'animate-spin': balanceSpin }"
                                class="h-5 w-5 text-white"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                                />
                            </svg>
                        </button>
                        <!-- Hamburger -->

                        <div class="ml-2 -mr-2 flex items-center sm:hidden">
                            <button
                                class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none"
                                @click="
                                    showingNavigationDropdown =
                                        !showingNavigationDropdown
                                "
                            >
                                <svg
                                    class="h-6 w-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex':
                                                !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex':
                                                showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div
                    :class="{
                        block: showingNavigationDropdown,
                        hidden: !showingNavigationDropdown,
                    }"
                    class="sm:hidden"
                >
                    <!-- Responsive Settings Options -->
                    <div class="border-t border-gray-200 pt-4 pb-1">
                        <div class="flex items-center px-4">
                            <div
                                v-if="
                                    $page.props.jetstream.managesProfilePhotos
                                "
                                class="mr-3 shrink-0"
                            >
                                <img
                                    class="h-10 w-10 rounded-full object-cover"
                                    :src="$page.props.user.profile_photo_url"
                                    :alt="$page.props.user.name"
                                />
                            </div>

                            <div>
                                <div class="text-base font-medium text-white">
                                    {{ $page.props.user.name }}
                                </div>
                                <!--                                <div class="text-sm font-medium text-gray-900">{{ $page.props.user.email }}</div>-->
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <JetResponsiveNavLink
                                :href="route(routePrefix+'.betting.history') + '?date=today'"
                                :active="route().current(routePrefix+'.betting.history')"
                            >
                                {{ __('betting_history') }}
                            </JetResponsiveNavLink>

                            <JetResponsiveNavLink
                                :href="route(routePrefix+'.deposit', {_query:{ date: 'today'}})"
                                :active="route().current(routePrefix+'.deposit')"
                            >
                                {{ __('deposit_withdraw') }}
                            </JetResponsiveNavLink>
                            <JetResponsiveNavLink
                                :href="route(routePrefix +'.feedback')"
                                :active="route().current(routePrefix +'.feedback')"
                            >
                                {{ __('feedback') }}
                            </JetResponsiveNavLink>

                            <JetResponsiveNavLink
                                :href="route('profile.show')"
                                :active="route().current('profile.show')"
                            >
                                {{ __('profile') }}
                            </JetResponsiveNavLink>

                            <!-- Authentication -->
                            <form method="POST" @submit.prevent="logout">
                                <JetResponsiveNavLink as="button">
                                    {{ __('logout') }}
                                </JetResponsiveNavLink>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header v-if="$slots.header" class="bg-fill shadow">
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <slot name="header"/>
                </div>
            </header>

            <!-- Page Content -->
            <main
                class="mb-20 h-full flex-1 space-y-4 overflow-y-scroll bg-transparent p-1 md:mb-20 md:flex xl:space-x-4 xl:space-y-0 3xl:container"
            >
                <slot/>
            </main>

            <div
                v-if="$page.props.user.type === 'member'"
                class="bg-navbar fixed bottom-0 flex h-16 w-full shadow-lg"
            >
                <div
                    v-for="(game, key) in games"
                    :key="key"
                    class="h-full flex-1 flex items-center justify-center"
                >
                    <a
                        v-if="game.link"
                        :href="game.link"
                        :target="game.target && game.target"
                    >
                        <div
                            class="transform rounded-full flex flex-col items-center justify-center bg-transparent md:-translate-y-4 xl:-translate-y-4"
                        >
                            <img
                                class="m-auto h-8 md:h-12 xl:h-14"
                                :src="asset(game.icon)"
                                :alt="game.name"
                            />

                            <div class="text-white text-sm md:text-base">
                                {{ game.name }}
                            </div>
                        </div>
                    </a>

                    <div
                        v-else
                        class="cursor-pointer"
                        @click.prevent="comingSoon"
                    >
                        <div
                            class="transform rounded-full flex flex-col items-center justify-center bg-transparent md:-translate-y-4 xl:-translate-y-4"
                        >
                            <img
                                class="m-auto h-8 md:h-12 xl:h-14"
                                :src="asset(game.icon)"
                                :alt="game.name"
                            />

                            <div class="text-white text-sm md:text-base">
                                {{ game.name }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <Toast :data="$page.props.flash"/>
</template>

<script>
import {Menu, MenuButton, MenuItem, MenuItems} from '@headlessui/vue';
import JetBanner from '@/Jetstream/Banner.vue';
import JetDropdown from '@/Jetstream/Dropdown.vue';
import JetDropdownLink from '@/Jetstream/DropdownLink.vue';
import JetResponsiveNavLink from '@/Jetstream/ResponsiveNavLink.vue';
import {Head, Link, usePage} from '@inertiajs/inertia-vue3';
import Toast from '@/Components/Toast';
import {computed, onMounted, ref} from 'vue';
import {Inertia} from '@inertiajs/inertia';
import {alertInfo} from '@/Functions/useHelper';
import {useTranslate} from '@/Functions/useTranslate';
import Message from '@/Components/Message';
import {useUserStore} from '@/Stores/DragonTigers/UserStore';


export default {
    name: 'AppLayout',
    components: {
        Toast,
        Head,
        JetBanner,
        JetDropdown,
        JetDropdownLink,
        JetResponsiveNavLink,
        Link,
        Menu,
        MenuButton,
        MenuItem,
        MenuItems,
        Message,
    },
    props: {
        title: {
            type: String,
            default: '',
        },
    },

    setup() {
        const {__} = useTranslate();

        let showingNavigationDropdown = ref(false);

        let balance = ref('0');
        let balanceSpin = ref(false);
        let messages = ref([]);

        const user = usePage().props.value.user;

        const routePrefix = computed(()=>{
            const routeName= route().v().name;
            return  routeName.includes('dragon-tiger')? 'dragon-tiger': 'member';
        });

        const logout = () => {
            Inertia.post(route('logout'), {
                _token: usePage().props.value.csrf_token,
            });
        };

        const refreshBalance = async () => {
            balanceSpin.value = true;
            const response = await axios.get('/refresh-balance');
            balance.value = response.data.balance;
            balanceSpin.value = false;
        };

        const getMessages = async () => {
            const response = await axios.get('/messages');
            messages.value = response.data;
        };

        const comingSoon = () => alertInfo(__('coming_soon'));
        const games = [
            {
                name: __('cock_fight'),
                icon: 'images/icons/cock-fight.png',
                link: route('member'),
                active: true
            },
            {
                name: __('sport'),
                icon: 'images/icons/sport.png',
                link: route('member.integration.gateway', {site: 'af88'}),
                active: usePage().props.value.hasGame.af88,
            },
            {
                name: __('live_casino'),
                icon: 'images/icons/live-casino.png',
                link: route('member.integration.gateway', {site: 't88'}),
                active: usePage().props.value.hasGame.t88.yuki,
                // target: '_blank'
            },
            {
                name: __('dragon_tiger'),
                icon: 'images/icons/dragon_Tiger.png',
                link: route('dragon-tiger'),
                active: user.can_play_dragon_tiger,
            },
            {
                name: __('baccarat'),
                icon: 'images/icons/dragon_Tiger.png',
                link: route('baccarat'),
                active: true,
            }
        ].filter(game => game.active);

        onMounted(async () => {
            await getMessages();
            await refreshBalance();

            Echo.channel(`match.${user.environment_id}.${user.group_id}`)
                .listen(`.payout.deposited.${user.id}`, (payload) => {
                    updateBalance(payload.balance);
                })
                .listen('.payout.deposited.all', (payload) => {
                    if (payload.balances[user.id] !== undefined) {
                        updateBalance(payload.balances[user.id]);
                    }
                });

            Echo.channel(`user.${user.environment_id}`)
                .listen(`.force.logout.${user.id}`, () => {
                    Inertia.post(route('logout'));
                })
                .listen(`.balance.refresh.${user.id}`, (payload) => {
                    updateBalance(payload.balance);
                })
                .listen('.notify.new.message', async () => {
                    await getMessages();
                })
                .listen(`.refresh.page.${user.id}`, () => {
                    window.location.reload();
                });

            useUserStore().$subscribe(function (mutation, state){
                if (mutation.payload?.balance){
                    updateBalance(state.balance);
                }
            });

            function updateBalance(currentBalance) {
                balanceSpin.value = true;
                balance.value = currentBalance;
                setTimeout(function () {
                    balanceSpin.value = false;
                }, 2000);
            }

            shouldLogoutThisUser();

            if (usePage().props.value.themeColor) {
                document.body.classList.add(usePage().props.value.themeColor);
            }
        });

        function shouldLogoutThisUser() {
            setTimeout(async function () {
                //const response = await axios.get(route('member.should-logout'));
                //console.log(response.data);
            }, 2000);
        }

        function setLocale(locale) {
            Inertia.post(
                route('locale'),
                {
                    lang: locale,
                },
                {
                    onFinish: () => window.location.reload(),
                }
            );
        }

        return {
            showingNavigationDropdown,
            refreshBalance,
            balanceSpin,
            balance,
            logout,
            setLocale,
            messages,
            games,
            comingSoon,
            routePrefix
        };
    },
};
</script>
