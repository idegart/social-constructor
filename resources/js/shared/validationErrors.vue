<template>
    <div class="invalid-feedback">
        <ul class="list-unstyled m-0">
            <li v-if="!checkValidationRule('required')">Required field</li>
            <li v-if="!checkValidationRule('requiredIf')">Required field</li>
            <li v-if="!checkValidationRule('maxLength')">Max length is invalid</li>
            <li v-if="!checkValidationRule('minLength')">Min length is invalid</li>
            <li v-if="!checkValidationRule('accepted')">Field should be accepted</li>
            <li v-if="!checkValidationRule('numeric')">The field must be numeric.</li>
            <li v-if="!checkValidationRule('alpha')">The field must be entirely alphabetic characters</li>
            <li v-if="!checkValidationRule('alpha_dash')">The field may have alpha-numeric characters, as well as dashes and underscores.</li>
            <li v-if="!checkValidationRule('url')">The field must be a valid URL.</li>
        </ul>
    </div>
</template>

<script>
    import { get, has } from 'lodash'

    export default {
        name: "validationErrors",
        props: {
            $v: {
                type: Object,
                required: true,
            },
            attribute: {
                type: String,
                required: true
            },
        },

        methods: {
            checkValidationRule (rule) {
                let rulePath = `${this.attribute}.${rule}`;

                return has(this.$v, rulePath) ? get(this.$v, rulePath) : true
            },
        },
    }
</script>

<style scoped>

</style>
