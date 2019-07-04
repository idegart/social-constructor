<template>
    <div>
        <hsc-menu-item label="Receive">
            <hsc-menu-item label="Message" @click="addBlock('receive_message')" />
        </hsc-menu-item>
        <hsc-menu-item label="Send">
            <hsc-menu-item label="Message" @click="addBlock('send_message')" />
            <hsc-menu-item label="Message w/keyboard" @click="addBlock('send_message_with_keyboard')" />
        </hsc-menu-item>
        <hsc-menu-separator />
        <hsc-menu-item label="Exit chat" @click="addBlock('exit_chat')" />
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
