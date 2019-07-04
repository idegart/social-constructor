<template>
    <div class="d-flex" :class="[{'justify-content-end': isOut}]">
        <div class="d-flex" :class="[{'order-1': isOut}]">
            <div class="param">
                <slot name="param" :param="param"></slot>
            </div>
        </div>
        <div class="mx-1">
            <slot name="label" :param="param"></slot>
        </div>
    </div>
</template>

<script>
    import BlockParam from "@model/Blocks/BlockParam";

    export default {
        name: "blockParamComponent",
        props: {
            param: {
                type: BlockParam,
                required: true,
            },
        },

        computed: {
            isOut () {
                return this.param && this.param.type === BlockParam.TYPE_OUT
            }
        }
    }
</script>

<style scoped>

</style>
