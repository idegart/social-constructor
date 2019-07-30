<template>
    <div>
        <base-block-component :block="block" :blockClass="blockClass" editable @toEdit="showModal" />
    </div>
</template>

<script>
    import Block from '@model/Block'
    import SendMessageWithInput from "@model/Blocks/SendMessageWithInput";
    import baseBlockComponent from "@component/Editor/baseBlockComponent";
    import blockParamComponent from "@component/Editor/blockParamComponent";

    import {createNamespacedHelpers} from 'vuex'
    const {mapMutations} = createNamespacedHelpers('editor');

    export default {
        name: "sendMessageWithInputBlockComponent",

        props: {
            block: Block,
            blockClass: SendMessageWithInput,
        },
        components: {baseBlockComponent, blockParamComponent},

        methods: {
            ...mapMutations([
                'openBlockModal',
            ]),

            showModal () {
                this.openBlockModal({modalName: 'sendMessageWithInputModal', block: this.block})
            },
        },
    }
</script>

<style scoped>

</style>
