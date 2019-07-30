<template>
    <div>
        <base-block-component :block="block" :blockClass="blockClass" editable @toEdit="showModal" />
    </div>
</template>

<script>
    import Block from '@model/Block'
    import ParamAdd from "@model/Blocks/ParamAdd";
    import baseBlockComponent from "@component/Editor/baseBlockComponent";
    import blockParamComponent from "@component/Editor/blockParamComponent";

    import {createNamespacedHelpers} from 'vuex'
    const {mapMutations} = createNamespacedHelpers('editor');

    export default {
        name: "paramAddBlockComponent",

        props: {
            block: Block,
            blockClass: ParamAdd,
        },
        components: {baseBlockComponent, blockParamComponent},

        methods: {
            ...mapMutations([
                'openBlockModal',
            ]),

            showModal () {
                this.openBlockModal({modalName: 'paramAddModal', block: this.block})
            },
        },
    }
</script>

<style scoped>

</style>
