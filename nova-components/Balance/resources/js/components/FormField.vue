<template>
  <default-field :field="field" :errors="errors" :show-help-text="showHelpText">
    <template slot="field">
      <div class="flex flex-wrap items-stretch w-full relative">
        <div 
          v-if="field.currency" 
          class="flex -mr-px"
        >
          <span
            class="
              flex
              items-center
              leading-normal
              rounded rounded-r-none
              border border-r-0 border-60
              px-3
              whitespace-no-wrap
              bg-30
              text-80 text-sm
              font-bold
            "
          >
            {{ field.currency }}
          </span>
        </div>

        <input
          class="
            flex-shrink flex-grow flex-auto
            leading-normal
            w-px
            flex-1
            form-control form-input form-input-bordered
          "
          :class="{ 'rounded-l-none': field.currency }"
          :id="field.attribute"
          :dusk="field.attribute"
          v-bind="extraAttributes"
          :disabled="isReadonly"
          @input="handleChange"
          :value="value"
        />
      </div>
    </template>
  </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'
import numeral from 'numeral'

export default {
  mixins: [FormField, HandlesValidationErrors],

  props: ['resourceName', 'resourceId', 'field'],

  methods: {
    fill(formData) {
      formData.append(
        this.field.attribute,
        numeral(this.value).value() || ''
      )
    },
    
    setInitialValue() {
      this.value = (this.field.value && numeral(this.field.value).format('0,0[.]00')) || ''
    },

    handleChange(value) {
      const removeCharacterValue = value.target.value.toString().replace(/[^\d.]/g, '')
      const removeExtraDotAndFollowing = this.removeExtraDotAndFollowing(removeCharacterValue)

      const formatter = new Intl.NumberFormat('en-US', { style: 'decimal'})
      this.value = formatter.format(removeExtraDotAndFollowing)
    },

    removeExtraDotAndFollowing(value) {
      const parts = value.split('.')

      if (parts.length > 2) {
        return parts.slice(0, 2).join('.')
      }

      return value
    },
  },
  
  computed: {
    defaultAttributes() {
      return {
        type: 'text',
        min: this.field.min,
        max: this.field.max,
        step: this.field.step,
        pattern: this.field.pattern,
        placeholder: this.field.placeholder || this.field.name,
        class: this.errorClasses,
      }
    },
    extraAttributes() {
      const attrs = this.field.extraAttributes

      return {
        // Leave the default attributes even though we can now specify
        // whatever attributes we like because the old number field still
        // uses the old field attributes
        ...this.defaultAttributes,
        ...attrs,
      }
    }
  }
}
</script>
