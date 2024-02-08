<template>
    <div class="flex items-center tab py-1 px-2">
        <a 
            v-for="(item, index) in items"
            :key="index"
            href="javascript:void(0);" 
            class="relative tab-bar font-semibold rounded-md inline-flex items-center p-2 rounded-r-md text-sm lg:text-lg"
            :class="{ 'tab-bar-active':  item.value === activeTab }"
            @click.prevent="selectedTab(item.value)"
        >
            {{ item.text }}
        </a>
    </div>
</template>

<script>

export default {
    name: 'PeriodDatePicker',

    props: {
        selected: {
            type: String,
            default: null
        },

        items: {
            type: Array,
            default: [
                {
                    text: 'Today',
                    value: 'today'
                },
                {
                    text: 'Yesterday',
                    value: 'yesterday'
                },
                {
                    text: 'This Week',
                    value: 'this-week'
                },
                {
                    text: 'Last Week',
                    value: 'last-week'
                },
                {
                    text: 'Current Month',
                    value: 'current-month'
                },
                {
                    text: 'Last Month',
                    value: 'last-month'
                },
            ]
        },
    },

    data() {
        return {
            activeTab: this.selected,
        }
    },

    methods: {
        selectedTab(value) {
            this.activeTab = value;
            this.$emit('change', value);
        }
    },

    watch: { 
        selected(newValue, oldValue) {
            this.activeTab = newValue;
        }
    }
}
</script>

<style scoped>
    .tab {
        background: #f3f4f6;
        border-radius: 5px;
    }

    .tab-bar {
        text-decoration: none;
        color: #000;
    }

    .tab-bar:hover {
        color: #4099de;
    }

    .tab-bar-active {
        color: #4099de;
        font-weight: bold;
        background: white;
        border-radius: 5px;
    }
</style>