<template>
    <div class="invalid-feedback">
        <ul class="list-unstyled">
            <li v-if="!checkValidationRule('required')">Required field</li>
            <li v-if="!checkValidationRule('maxLength')">Max length is invalid</li>
            <li v-if="!checkValidationRule('minLength')">Min length is invalid</li>
            <li v-if="!checkValidationRule('accepted')">Field should be accepted</li>
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
