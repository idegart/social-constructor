<template>
    <select :value="value" @input="$emit('input', $event.target.value)" class="form-control">
        <option :value="null">Not selected</option>
        <option v-for="variable in params" :value="variable.id">
            {{ variable.variable }} ({{ variable.type }})
        </option>
    </select>
</template>

<script>
    import {createNamespacedHelpers} from 'vuex'
    const {mapActions, mapState} = createNamespacedHelpers('editor');

    export default {
        name: "selectParamComponent",

        props: {
            value: {},
        },

        computed: {
            ...mapState([
                'variables',
            ]),

            params () {
                return this.variables
            },
        },
    }
</script>
