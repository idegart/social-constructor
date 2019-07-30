<template>
    <div>
        <base-block-component :block="block" :blockClass="blockClass" editable @toEdit="showModal" />
    </div>
</template>

<script>
    import {createNamespacedHelpers} from 'vuex'
    const { mapMutations } = createNamespacedHelpers('editor');

    import Block from '@model/Block'
    import ParamSet from "@model/Blocks/ParamSet";
    import baseBlockComponent from "@component/Editor/baseBlockComponent";
    import blockParamComponent from "@component/Editor/blockParamComponent";

    export default {
        name: "paramSetBlockComponent",

        props: {
            block: Block,
            blockClass: ParamSet,
        },

        components: {baseBlockComponent, blockParamComponent,},

        methods: {
            ...mapMutations([
                'openBlockModal',
            ]),

            showModal () {
                this.openBlockModal({modalName: 'paramSetModal', block: this.block})
            },
        },
    }
</script>

<style scoped>

</style>
