<template>
    <div>
        <base-block-component :block="block" :blockClass="blockClass" editable @toEdit="showModal" />
    </div>
</template>

<script>
    import {createNamespacedHelpers} from 'vuex'
    const { mapMutations } = createNamespacedHelpers('editor');

    import Block from '@model/Block'
    import ParamCompare from "@model/Blocks/ParamCompare";
    import baseBlockComponent from "@component/Editor/baseBlockComponent";
    import blockParamComponent from "@component/Editor/blockParamComponent";

    export default {
        name: "paramCompareBlockComponent",

        props: {
            block: Block,
            blockClass: ParamCompare,
        },
        components: {baseBlockComponent, blockParamComponent,},

        methods: {
            ...mapMutations([
                'openBlockModal',
            ]),

            showModal () {
                this.openBlockModal({modalName: 'paramCompareModal', block: this.block})
            },
        },
    }
</script>

<style scoped>

</style>
