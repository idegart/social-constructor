<template>
    <div>
        <base-block-component :block="block" :blockClass="blockClass" editable @toEdit="showModal('dialogflowModal')">
            <template slot="dropdown">
                <button v-if="block.get('data.actions', []).length < 10"
                        @click="showModal('dialogflowActionModal')"
                        class="dropdown-item">
                    <i class="fas fa-plus-square mr-1"></i>
                    Add action
                </button>
            </template>
        </base-block-component>
    </div>
</template>

<script>
    import {createNamespacedHelpers} from 'vuex'
    const { mapMutations } = createNamespacedHelpers('editor');

    import Block from '@model/Block'
    import Dialogflow from "@model/Blocks/Dialogflow";
    import baseBlockComponent from "@component/Editor/baseBlockComponent";
    import blockParamComponent from "@component/Editor/blockParamComponent";

    export default {
        name: "dialogFlowBlockComponent",

        props: {
            block: Block,
            blockClass: Dialogflow,
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
