<template>
    <div>
        <base-block-component :block="block" :blockClass="blockClass" editable @toEdit="showModal('sendMessageWithKeyboardModal')">

            <template slot="dropdown">
                <button v-if="block.get('data.buttons', []).length < 4"
                        @click="showModal('sendMessageWithKeyboardButtonModal')"
                        class="dropdown-item">
                    <i class="fas fa-plus-square mr-1"></i>
                    Add button
                </button>
            </template>

        </base-block-component>
    </div>
</template>

<script>
    import Block from '@model/Block'
    import SendMessageWithKeyboard from "@model/Blocks/SendMessageWithKeyboard";
    import baseBlockComponent from "@component/Editor/baseBlockComponent";
    import blockParamComponent from "@component/Editor/blockParamComponent";

    import {createNamespacedHelpers} from 'vuex'
    const {mapMutations} = createNamespacedHelpers('editor');


    export default {
        name: "sendMessageWithKeyboardBlockComponent",

        props: {
            block: Block,
            blockClass: SendMessageWithKeyboard,
        },
        components: {baseBlockComponent, blockParamComponent},

        methods: {
            ...mapMutations([
                'openBlockModal',
            ]),

            showModal (modalName) {
                this.openBlockModal({modalName, block: this.block})
            },
        },
    }
</script>

<style scoped>

</style>
