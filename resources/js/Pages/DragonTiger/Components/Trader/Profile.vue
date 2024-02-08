<script setup>
import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue';
import { Link } from '@inertiajs/inertia-vue3';
</script>
<template>
    <Menu as="div" class="relative inline-block text-left">
        <div class="flex items-center gap-3">
            <MenuButton
                class="inline-flex h-10 w-10 justify-center rounded-full bg-black bg-opacity-20 text-sm font-medium text-white hover:bg-opacity-30 focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75"
            >
                <img
                    class="h-full w-full"
                    src="https://cdn-icons-png.flaticon.com/128/149/149071.png"
                    alt="profile"
                />
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
                class="absolute right-0 z-50 mt-2 w-56 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
            >
                <div class="px-1 py-1">
                    <MenuItem v-slot="{ active }">
                        <Link
                            :href="route('logout')"
                            method="post"
                            as="button"
                            :class="[ active ? 'bg-dragon-nav text-white' : 'text-gray-900', 'group flex w-full items-center rounded-md px-2 py-2 text-sm']"
                        >
                            Logout
                        </Link>
                    </MenuItem>
                </div>
            </MenuItems>
        </transition>
    </Menu>
</template>
