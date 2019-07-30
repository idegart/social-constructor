<template>
    <div>
        <base-block-component :block="block" :blockClass="blockClass" editable @toEdit="showModal" />
    </div>
</template>

<script>
    import Block from '@model/Block'
    import SendMessage from "@model/Blocks/SendMessage";
    import baseBlockComponent from "@component/Editor/baseBlockComponent";
    import blockParamComponent from "@component/Editor/blockParamComponent";

    import {createNamespacedHelpers} from 'vuex'
    const { mapMutations } = createNamespacedHelpers('editor');

    export default {
        name: "sendMessageBlockComponent",

        props: {
            block: Block,
            blockClass: SendMessage,
        },
        components: {baseBlockComponent, blockParamComponent},

        methods: {
            ...mapMutations([
                'openBlockModal',
            ]),

            showModal () {
                this.openBlockModal({modalName: 'sendMessageModal', block: this.block})
            },
        },
    }
</script>

<style scoped>

</style>
