<template>
    <div>
        <base-block-component :block="block" :blockClass="blockClass" />
    </div>
</template>

<script>
    import Block from "@model/Block";
    import FilterSocial from "@model/Blocks/FilterSocial";
    import baseBlockComponent from "@component/Editor/baseBlockComponent";
    import blockParamComponent from "@component/Editor/blockParamComponent";

    export default {
        name: "filterSocialBlockComponent",

        props: {
            block: Block,
            blockClass: FilterSocial,
        },

        components: {baseBlockComponent, blockParamComponent},
    }
</script>

<style scoped>

</style>
