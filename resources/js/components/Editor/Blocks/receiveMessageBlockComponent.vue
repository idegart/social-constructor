<template>
    <div>
        <base-block-component :block="block" :blockClass="blockClass" editable @toEdit="showModal" />
    </div>
</template>

<script>
    import {createNamespacedHelpers} from 'vuex'
    const { mapMutations } = createNamespacedHelpers('editor');

    import Block from '@model/Block'
    import ReceiveMessage from "@model/Blocks/ReceiveMessage";
    import baseBlockComponent from "@component/Editor/baseBlockComponent";
    import blockParamComponent from "@component/Editor/blockParamComponent";

    export default {
        name: "receiveMessageBlockComponent",

        props: {
            block: Block,
            blockClass: ReceiveMessage,
        },

        components: {baseBlockComponent, blockParamComponent},

        methods: {
            ...mapMutations([
                'openBlockModal',
            ]),

            showModal () {
                this.openBlockModal({modalName: 'receiveMessageModal', block: this.block})
            },
        },
    }
</script>

<style scoped>

</style>
