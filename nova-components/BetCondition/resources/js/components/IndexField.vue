<template>
    <div>
        <button
            :disabled="shouldDisable()"
            v-on:click="showForm"
            class="btn btn-default btn-primary flex items-center justify-center">

            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                 stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>

        </button>

        <!-- use the modal component, pass in the prop -->
        <KvModal v-if="showModal" @save="save" @close="showModal = false">
            <h4 slot="header">Bet Condition ({{ field.user.name }})</h4>
            <div slot="body">
                <div
                    class="flex items-stretch gap-1 bg-white justify-between divide-x divide-40 border-b border-40">

                    <div class="flex-1 cursor-pointer h-12 flex items-center font-bold justify-center"
                         v-for="(group, index) in groups"
                         :class="index === 0 ?'bg-30':''"
                         :key="group.id"
                         v-on:click="groupOnClick(group.id, $event)"
                    >
                        {{ group.name }}
                    </div>
                </div>

                <div v-if="field.user.type==='agent'" class="flex border-b border-40">
                    <div class="px-8 py-6 w-2/5">
                        <label for=""
                               class="inline-block text-80 pt-2 leading-tight">
                            Force
                        </label>
                    </div>

                    <div class="py-6 px-8 w-3/5">
                        <input
                            v-model="force"
                            type="checkbox"
                            class="checkbox mr-2 cursor-pointer">
                    </div>
                </div>

                <KvInput
                    :currency="field.user.currency"
                    :error="resolveErrorMessage('minBetPerTicket')"
                    :readonly="readonly"
                    label="Minimum bet per ticket"
                    v-model.number="minBetPerTicket"

                />
                <KvInput
                    :currency="field.user.currency"
                    :error="resolveErrorMessage('maxBetPerTicket')"
                    :readonly="readonly"
                    label="Maximum bet per ticket"
                    v-model.number="maxBetPerTicket"
                />
                <KvInput
                    :currency="field.user.currency"
                    :error="resolveErrorMessage('matchLimit')"
                    :readonly="readonly"
                    label="Match limit"
                    v-model.number="matchLimit"
                />
                <KvInput
                    :currency="field.user.currency"
                    :error="resolveErrorMessage('winLimitPerDay')"
                    :readonly="readonly"
                    label="Win limit per day"
                    v-model.number="winLimitPerDay"
                />

            </div>


        </KvModal>
    </div>


</template>

<script>

import KvModal from "./KvModal.vue";

export default {
    components: {KvModal},
    props: ['resourceName', 'field'],

    data: () => ({
        showModal: false,
        baseUrl: "/nova-vendor/bet-condition/",
        groups: Array,
        selectedGroupId: Number,
        minBetPerTicket: Number,
        maxBetPerTicket: Number,
        matchLimit: Number,
        winLimitPerDay: Number,
        force: Boolean,
        errors: Object,
        readonly: false
    }),
    mounted() {
        console.log(this.field.user);
    },
    methods: {
        showForm: async function () {
            this.groups = await this.getGroups();
            this.showModal = true;
            this.selectedGroupId = this.groups[0].id;

            this.setCondition(await this.getCondition(
                    this.selectedGroupId,
                    this.field.user.id,
                    this.field.user.parentId
                )
            );
        },
        shouldDisable: function () {
            return (this.field.user === undefined || this.field.actionUser === undefined);
        },
        groupOnClick: async function (groupId, event) {

            this.setActive(event);
            this.selectedGroupId = groupId;
            this.errors = {};
            this.setCondition(await this.getCondition(groupId, this.field.user.id, this.field.user.parentId));
        },
        getGroups: async function () {
            return await this.getRequest(`${this.baseUrl}get-groups`);
        },
        getCondition: async function (groupId, userId, parentId) {
            return await this.getRequest(`${this.baseUrl}get-condition/${groupId}/${userId}/${parentId}`)
        },
        getRequest: async function (url) {
            this.readonly = true;
            const {data} = await Nova.request().get(url);
            this.readonly = false;
            return data;
        },
        setCondition: function (condition) {
            this.minBetPerTicket = condition.minBetPerTicket;
            this.maxBetPerTicket = condition.maxBetPerTicket;
            this.matchLimit = condition.matchLimit;
            this.winLimitPerDay = condition.winLimitPerDay;
            this.force = condition.force
        },
        setActive: function (event) {
            event.preventDefault();

            event.target.parentElement.querySelectorAll(".bg-30")
                .forEach(e => e.classList.remove("bg-30"));

            event.target.classList.add("bg-30");
        },
        save: async function () {
            try {


                const payload = {
                    groupId: this.selectedGroupId,
                    userId: this.field.user.id,
                    parentId: this.field.user.parentId,
                    force: this.force,
                    minBetPerTicket: this.minBetPerTicket,
                    maxBetPerTicket: this.maxBetPerTicket,
                    matchLimit: this.matchLimit,
                    winLimitPerDay: this.winLimitPerDay
                };


                this.readonly = true;
                await Nova.request().post(`${this.baseUrl}bet-condition`, payload);
                this.readonly = false;
                this.$toasted.show('Bet condition set successfully', {type: 'success'})
                this.errors = {};

            } catch (error) {
                const {response} = error;
                if (response.status === 422) {
                    this.errors = response.data.errors
                }
                this.readonly = false;
            }
        },
        resolveErrorMessage: function (field) {
            if (this.errors[field] === undefined) {
                return '';
            }
            return this.errors[field].join(',');
        }
    },


}
</script>
