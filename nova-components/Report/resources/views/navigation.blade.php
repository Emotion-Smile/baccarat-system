<h3 class="cursor-pointer flex items-center font-normal dim text-white mb-6 text-base no-underline">
    <svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
        <path fill="var(--sidebar-icon)"
              d="M288 248v28c0 6.6-5.4 12-12 12H108c-6.6 0-12-5.4-12-12v-28c0-6.6 5.4-12 12-12h168c6.6 0 12 5.4 12 12zm-12 72H108c-6.6 0-12 5.4-12 12v28c0 6.6 5.4 12 12 12h168c6.6 0 12-5.4 12-12v-28c0-6.6-5.4-12-12-12zm108-188.1V464c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V48C0 21.5 21.5 0 48 0h204.1C264.8 0 277 5.1 286 14.1L369.9 98c9 8.9 14.1 21.2 14.1 33.9zm-128-80V128h76.1L256 51.9zM336 464V176H232c-13.3 0-24-10.7-24-24V48H48v416h288z"
        />
    </svg>
    <span class="sidebar-label">Reports</span>
</h3>

<ul class="list-reset mb-8">
    <li class="leading-tight mb-4 ml-8 text-sm">
        <router-link
            :to="{ name: 'report-mixed-win-lose' }"
            class="text-white text-justify no-underline dim"
        >
            Win/Lose Detail (Mixed All Report)
        </router-link>
    </li>

    <li class="leading-tight mb-4 ml-8 text-sm">
        <router-link
            :to="{ name: 'report-win-lose' }"
            class="text-white text-justify no-underline dim"
        >
            CF Win/Lose
        </router-link>
    </li>

    @if(user()->canPlayDragonTiger() || user()->isCompany())
        <li class="leading-tight mb-4 ml-8 text-sm">
            <router-link
                :to="{ name: 'report-dragon-tiger-win-lose' }"
                class="text-white text-justify no-underline dim"
            >
                D&T Win/Lose
            </router-link>
        </li>
    @endif

    @if(user()->hasAllowT88Game())
        <li class="leading-tight mb-4 ml-8 text-sm">
            <router-link
                :to="{ name: 'report-t88-win-lose' }"
                class="text-white text-justify no-underline dim"
            >
                Yuki Win/Lose
            </router-link>
        </li>
    @endif

    @if(user()->isAgent() && user()->underSuperSenior->hasAllowAF88Game())
        <li class="leading-tight mb-4 ml-8 text-sm">
            <router-link
                :to="{ name: 'report-af88-win-lose' }"
                class="text-white text-justify no-underline dim"
            >
                AF88 Win/Lose
            </router-link>
        </li>
    @endif

    <li class="leading-tight mb-4 ml-8 text-sm">
        <router-link
            :to="{name: 'report-outstanding-tickets'}"
            class="text-white text-justify no-underline dim"
        >
            CF Outstanding Tickets
        </router-link>
    </li>

    @if(user()->canPlayDragonTiger() || user()->isCompany())
        <li class="leading-tight mb-4 ml-8 text-sm">
            <router-link
                :to="{
                    name: 'index',
                    params: {
                        resourceName: 'dragon-tiger-outstanding-ticket-resources'
                    }
                }"
                class="text-white text-justify no-underline dim"
            >
                D&T Outstanding Tickets
            </router-link>
        </li>
    @endif

    @if(user()->hasAllowT88Game())
        <li class="leading-tight mb-4 ml-8 text-sm">
            <router-link
                :to="{ name: 'report-t88-outstanding-tickets' }"
                class="text-white text-justify no-underline dim"
            >
                Yuki Outstanding Tickets
            </router-link>
        </li>
    @endif

    @if(user()->isAgent() && user()->underSuperSenior->hasAllowAF88Game())
        <li class="leading-tight mb-4 ml-8 text-sm">
            <router-link
                :to="{ name: 'report-af88-member-outstanding' }"
                class="text-white text-justify no-underline dim"
            >
                AF88 Member Outstanding
            </router-link>
        </li>
    @endif

    {{--    <li class="leading-tight mb-4 ml-8 text-sm">--}}
    {{--        <router-link--}}
    {{--            :to="{ name: 'report-booking-tickets' }"--}}
    {{--            class="text-white text-justify no-underline dim"--}}
    {{--        >--}}
    {{--            Boxing Tickets--}}
    {{--        </router-link>--}}
    {{--    </li>--}}

    {{--    @if(user()->hasPermission('MissingPayout:view-any') || user()->isRoot())--}}
    {{--        <li class="leading-tight mb-4 ml-8 text-sm">--}}
    {{--            <router-link--}}
    {{--                :to="{ name: 'report-missing-payouts' }"--}}
    {{--                class="text-white text-justify no-underline dim"--}}
    {{--            >--}}
    {{--                Missing Payouts--}}
    {{--            </router-link>--}}
    {{--        </li>--}}
    {{--    @endif--}}

    @if(user()->isCompany())
        <li class="leading-tight mb-4 ml-8 text-sm">
            <router-link
                :to="{ name: 'report-top-winners' }"
                class="text-white text-justify no-underline dim"
            >
                Top Winners
            </router-link>
        </li>
    @endif

    <li class="leading-tight mb-4 ml-8 text-sm">
        <router-link
            :to="{ name: 'report-payments' }"
            class="text-white text-justify no-underline dim"
        >
            Payments
        </router-link>
    </li>
</ul>
