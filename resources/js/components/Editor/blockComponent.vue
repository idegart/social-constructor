<template>
    <div>
        <component :is="blockComponent"
                   :block="block"
                   :blockClass="block.get('_blockClass')"
        />
    </div>
</template>

<script>
    import { camelCase } from 'lodash'
    import Block from "@model/Block";

    export default {
        name: "blockComponent",

        props: {
            block: Block,
        },

        computed: {
            blockComponent () {
                let blockComponent = camelCase(this.block.get('data_type'));

                return () => import(`./Blocks/${blockComponent}BlockComponent.vue`)
            }
        },
    }
</script>

<style scoped>

</style>
