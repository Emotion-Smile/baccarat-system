<template>
  <modal
    data-testid="confirm-action-modal"
    tabindex="-1"
    role="dialog"
    :closes-via-backdrop="canLeave"
    @modal-close="handleClose"
  >
    <form
      autocomplete="off"
      @keydown="handleKeydown"
      @submit.prevent.stop="handleConfirm"
      class="bg-white rounded-lg shadow-lg overflow-hidden w-action-fields"
    >
      <div>
        <heading 
          :level="2" 
          class="border-b border-40 py-8 px-8"
        >
          {{ modalName }}
        </heading>

        <div>
          <!-- Validation Errors -->
          <validation-errors :errors="errors" />

          <!-- Action Fields -->
          <div
            class="action"
            v-for="field in fields"
            :key="field.attribute"
          >
            <component
              :is="'form-' + field.component"
              :errors="errors"
              :resource-name="resourceName"
              :field="field"
              :show-help-text="field.helpText != null"
            />
          </div>
        </div>
      </div>

      <div class="bg-30 px-6 py-3 flex">
        <div class="flex items-center ml-auto">
          <button
            dusk="cancel-action-button"
            type="button"
            @click.prevent="handleClose"
            class="btn btn-link dim cursor-pointer text-80 ml-auto mr-6"
          >
            Cancel
          </button>

          <loading-button
            ref="runButton"
            dusk="confirm-action-button"
            :processing="working"
            :disabled="working"
            type="submit"
            class="btn btn-default btn-primary"
          >
            Save
          </loading-button>
        </div>
      </div>
    </form>
  </modal>
</template>

<script>
export default {
  props: {
    name: String,
    working: Boolean,
    resourceName: { type: String, required: true },
    fields: { type: Array, required: true },
    selectedResources: { type: [Array, String], required: true },
    errors: { type: Object, required: true },
  },

  created() {
    const listenToDatePickerOpened = event => {
      this.canLeave = false
    }

    const listenToDatePickerClosed = event => {
      this.canLeave = true
    }

    Nova.$on('datepicker-opened', listenToDatePickerOpened)
    Nova.$on('datepicker-closed', listenToDatePickerClosed)

    this.$once('hook:beforeDestroy', () => {
      Nova.$off('datepicker-opened', listenToDatePickerOpened)
      Nova.$off('datepicker-closed', listenToDatePickerClosed)
    })
  },

  /**
   * Mount the component.
   */
  mounted() {
    // If the modal has inputs, let's highlight the first one, otherwise
    // let's highlight the submit button
    if (document.querySelectorAll('.modal input').length) {
      document.querySelectorAll('.modal input')[0].focus()
    } else {
      this.$refs.runButton.focus()
    }
  },

  data: () => ({
    canLeave: true,
  }),

  methods: {
    /**
     * Stop propogation of input events unless it's for an escape or enter keypress
     */
    handleKeydown(e) {
      if (['Escape', 'Enter'].indexOf(e.key) !== -1) {
        return
      }

      e.stopPropagation()
    },

    /**
     * Execute the selected action.
     */
    handleConfirm() {
      this.$emit('confirm')
    },

    /**
     * Close the modal.
     */
    handleClose() {
      this.$emit('close')
    },
  },

  computed: {
    modalName() {
      return this.name || this.__('Game Condition');
    },
  }
}
</script>
