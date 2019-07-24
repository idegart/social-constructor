<template>
    <div>
        <hsc-menu-item label="Receive">
            <hsc-menu-item label="Message" @click="addBlock('receive_message')" />
        </hsc-menu-item>
        <hsc-menu-item label="Send">
            <hsc-menu-item label="Message" @click="addBlock('send_message')" />
            <hsc-menu-item label="Message w/keyboard" @click="addBlock('send_message_with_keyboard')" />
            <hsc-menu-item label="Message w/input" @click="addBlock('send_message_with_input')" />
        </hsc-menu-item>
        <hsc-menu-separator />
        <hsc-menu-item label="Params">
            <hsc-menu-item label="Set" @click="addBlock('param_set')" />
            <hsc-menu-item label="Add" @click="addBlock('param_add')" />
            <hsc-menu-item label="Compare" @click="addBlock('param_compare')" />
            <hsc-menu-item label="Reset" @click="addBlock('param_reset')" />
        </hsc-menu-item>
        <hsc-menu-item label="Filters">
            <hsc-menu-item label="Socials" @click="addBlock('filter_social')" />
        </hsc-menu-item>
        <hsc-menu-item label="External">
            <hsc-menu-item label="External API" @click="addBlock('external_api')" />
        </hsc-menu-item>
    </div>
</template>

<script>
    import { merge } from 'lodash'
    import { createNamespacedHelpers } from 'vuex'

    const {mapGetters, mapActions} = createNamespacedHelpers('editor');

    export default {
        name: "contextMenuComponent",

        computed: {
            ...mapGetters([
                'getRealPosition',
            ]),
        },

        methods: {
            ...mapActions([
                'storeBlock',
            ]),

            addBlock (data_type) {
                let payload = merge(this.getRealPosition(this.$el.parentElement.parentElement, false), {data_type});
                this.storeBlock(payload);
            },
        },
    }
</script>

<style scoped>

</style>
