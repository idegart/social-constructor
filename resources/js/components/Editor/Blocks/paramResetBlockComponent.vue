<template>
    <div>
        <base-block-component :block="block" :blockClass="blockClass" editable @toEdit="showModal" />
    </div>
</template>

<script>
    import Block from '@model/Block'
    import ParamReset from "@model/Blocks/ParamReset";
    import baseBlockComponent from "@component/Editor/baseBlockComponent";
    import blockParamComponent from "@component/Editor/blockParamComponent";

    import {createNamespacedHelpers} from 'vuex'
    const {mapMutations} = createNamespacedHelpers('editor');


    export default {
        name: "paramResetBlockComponent",

        props: {
            block: Block,
            blockClass: ParamReset,
        },
        components: {baseBlockComponent, blockParamComponent},

        methods: {
            ...mapMutations([
                'openBlockModal',
            ]),

            showModal () {
                this.openBlockModal({modalName: 'paramResetModal', block: this.block})
            },
        },
    }
</script>

<style scoped>

</style>
