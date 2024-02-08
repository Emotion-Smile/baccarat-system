<dropdown-trigger class="h-9 flex items-center">
    @isset($user->email)
        <img
            src="https://secure.gravatar.com/avatar/{{ md5(\Illuminate\Support\Str::lower($user->email)) }}?size=512"
            class="rounded-full w-8 h-8 mr-3"
        />
    @endisset

    <span class="text-90">
        {{ $user->name ?? $user->email ?? __('Nova User') }}
    </span>
</dropdown-trigger>

<dropdown-menu slot="menu" width="200" direction="rtl">

    <ul class="list-reset">
        <li>
            <router-link :to="{
                name: 'detail',
                params: {
                    resourceName: '{{ getResourceByUserType($user->type->value) }}',
                    resourceId: '{{ $user->id }}'
                }
            }" class="block no-underline text-90 hover:bg-30 p-3">
                Profile
            </router-link>
        </li>

        <li>
            <router-link :to="{
                name: 'edit',
                params: {
                    resourceName: '{{ getResourceByUserType($user->type->value) }}',
                    resourceId: '{{ $user->id }}'
                }
            }" class="block no-underline text-90 hover:bg-30 p-3">
                Change Password
            </router-link>
        </li>

        <li>
            <a href="{{ route('nova.logout') }}" class="block no-underline text-90 hover:bg-30 p-3">
                {{ __('Logout') }}
            </a>
        </li>
    </ul>
</dropdown-menu>