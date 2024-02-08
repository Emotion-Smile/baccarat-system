<template>
    <div>
        <button
            class="btn btn-default btn-primary flex items-center justify-center"
            :class="{ hidden: hidden }"
            :disabled="disabled"
            @click="openConfirmationModal"
        >
            <span>{{ buttonText }}</span>
        </button>

        <portal to="modals" transition="fade-transition">
            <condition-modal
                v-if="confirmActionModalOpened"
                class="text-left"
                :name="field.name"
                :working="working"
                :fields="fields"
                :selected-resources="selectedResources"
                :resource-name="resourceName"
                :errors="errors"
                @confirm="executeAction"
                @close="closeConfirmationModal"
            />
        </portal>
    </div>
</template>

<script>
import { Errors, HandlesValidationErrors, FormField } from 'laravel-nova';
import ConditionModal from './ConditionModal';

export default {
    mixins: [
        FormField,
        HandlesValidationErrors,
    ],

    props: {
        resourceName: String,
        field:  Object,
    },

    components: {
        ConditionModal
    },

    data: () => ({
        fields: [],
        working: false,
        confirmActionModalOpened: false,
    }),

    methods: {
        openConfirmationModal() {
            Nova.request({
                method: 'get',
                url: this.fieldEndpoint,
            })
                .then(({ data }) => {
                    this.fields = data.fields;
                    this.confirmActionModalOpened = true;
                })
                .catch((error) => {});
        },

        closeConfirmationModal() {
            this.confirmActionModalOpened = false;
            this.errors = new Errors();
        },

        executeAction() {
            this.working = true;

            Nova.request({
                method: 'post',
                url: this.executeEndpoint,
                data: this.actionFormData(),
            })
                .then((response) => {
                    this.confirmActionModalOpened = false;
                    this.handleActionResponse(response.data);
                    this.working = false;
                })
                .catch((error) => {
                    this.working = false;

                    if (error.response.status == 422) {
                        this.errors = new Errors(error.response.data.errors);
                        Nova.error(this.__('There was a problem executing the action.'));
                    }
                });
        },

        actionFormData() {
            return _.tap(new FormData(), (formData) => {
                formData.append('resources', this.selectedResources);

                _.each(this.fields, (field) => {
                    field.fill(formData);
                });
            });
        },

        handleActionResponse(data) {
            if (data.message) {
                this.$parent.$emit('actionExecuted');
                Nova.$emit('action-executed');
                Nova.success(data.message);
            } else if (data.deleted) {
                this.$parent.$emit('actionExecuted');
                Nova.$emit('action-executed');
            } else if (data.danger) {
                this.$parent.$emit('actionExecuted');
                Nova.$emit('action-executed');
                Nova.error(data.danger);
            } else if (data.download) {
                let link = document.createElement('a');
                link.href = data.download;
                link.download = data.name;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            } else if (data.redirect) {
                window.location = data.redirect;
            } else if (data.push) {
                this.$router.push(data.push);
            } else if (data.openInNewTab) {
                window.open(data.openInNewTab, '_blank');
            } else {
                this.$parent.$emit('actionExecuted');
                Nova.$emit('action-executed');
                Nova.success(this.__('The action ran successfully!'));
            }
        },
    },

    computed: {
        buttonText() {
            return this.field.text || this.__('Run');
        },

        fieldEndpoint() {
            return this.field.fieldEndpoint;
        },

        executeEndpoint() {
            return this.field.executeEndpoint;
        },

        selectedResources() {
            return this.field.resourceId;
        },

        hidden() {
            return this.field.hidden || false;
        },

        disabled() {
            return this.field.readonly;
        },
    },
}
</script>