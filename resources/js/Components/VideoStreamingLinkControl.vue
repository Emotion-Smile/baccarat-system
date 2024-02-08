<template>
    <Menu as="div" class="relative inline-block text-right">
        <transition 
            enter-active-class="transition ease-out duration-100" 
            enter-from-class="transform opacity-0 scale-95" 
            enter-to-class="transform opacity-100 scale-100" 
            leave-active-class="transition ease-in duration-75" 
            leave-from-class="transform opacity-100 scale-100" 
            leave-to-class="transform opacity-0 scale-95"
        >
            <MenuItems class="bottom-10 right-0 origin-top-right rounded-md absolute mt-2 w-[120px] bg-black bg-opacity-70 divide-y-[0.5px] focus:outline-none">
                <div class="py-1">
                    <MenuItem>
                        <a 
                            href="javascript:void(0);" 
                            :class="[
                                streamingLink1 === currentLink ? 'text-wala' : 'text-white',
                                'block px-4 py-2 font-bold  cursor-pointer hover:text-wala'
                            ]"
                            @click.prevent="handleLinkChange(streamingLink1)"
                        >Streaming 1</a>
                    </MenuItem>
                </div>
                <div class="py-1">
                    <MenuItem>
                        <a 
                            href="javascript:void(0);" 
                            :class="[
                                streamingLink2 === currentLink ? 'text-wala' : 'text-white',
                                'block px-4 py-2 font-bold  cursor-pointer hover:text-wala'
                            ]"
                            @click.prevent="handleLinkChange(streamingLink2)"
                        >Streaming 2</a>
                    </MenuItem>
                </div>
            </MenuItems>
        </transition>
        <div>
            <MenuButton class="inline-flex justify-center w-full text-white hover:text-blue-400 py-2 bg-transparent">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 xl:w-6 xl:h-6" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 5a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2h-2.22l.123.489.804.804A1 1 0 0113 18H7a1 1 0 01-.707-1.707l.804-.804L7.22 15H5a2 2 0 01-2-2V5zm5.771 7H5V5h10v7H8.771z" clip-rule="evenodd" />
                </svg>
            </MenuButton>
        </div>
    </Menu>
</template>

<script>
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue';

export default {
    components: {
        Menu, 
        MenuButton, 
        MenuItem, 
        MenuItems
    },

    props: {
        currentLink: String,
        streamingLink1: String,
        streamingLink2: String
    },

    setup(props, context) {
        const handleLinkChange = (link) => {
            if(props.currentLink && props.currentLink === link) return;

            context.emit('linkChange', link);
        };

        return {
            handleLinkChange
        };
    }
}
</script>