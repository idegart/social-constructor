<template>
    <div>
        <base-block-component :block="block" :blockClass="blockClass" editable @toEdit="showModal" />
    </div>
</template>

<script>
    import Block from "@model/Block";
    import FilterChannel from "@model/Blocks/FilterChannel";
    import baseBlockComponent from "@component/Editor/baseBlockComponent";
    import blockParamComponent from "@component/Editor/blockParamComponent";

    import {createNamespacedHelpers} from 'vuex'
    const { mapMutations } = createNamespacedHelpers('editor');

    export default {
        name: "filterChannelBlockComponent",

        props: {
            block: Block,
            blockClass: FilterChannel,
        },

        components: {baseBlockComponent, blockParamComponent},

        methods: {
            ...mapMutations([
                'openBlockModal',
            ]),

            showModal () {
                this.openBlockModal({modalName: 'filterChannelModal', block: this.block})
            },
        },
    }
</script>

<style scoped>

</style>
