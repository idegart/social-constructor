<template>
    <div class="field-grid"
         @mousemove="handleMouseMove"
         @mouseup="handleMouseUp">

        <hsc-menu-style-white>
            <hsc-menu-context-menu class="position-relative">

                <navbar-component />

                <context-menu-component slot="contextmenu" style="z-index: 9999" />

                <div id="editorField"></div>

                <block-component v-for="block in blocks"
                                 :key="block._id"
                                 :block="block" />

            </hsc-menu-context-menu>
        </hsc-menu-style-white>

        <modal-params-component />

    </div>
</template>

<script>
    import { createNamespacedHelpers } from 'vuex'
    const {mapActions, mapMutations, mapGetters} = createNamespacedHelpers('editor');

    import Vue from 'vue'
    import * as VueMenu from '@hscmap/vue-menu'
    import NavbarComponent from "@component/Editor/navbarComponent";
    import ContextMenuComponent from "@component/Editor/contextMenuComponent";
    Vue.use(VueMenu);

    import blockComponent from "@component/Editor/blockComponent";
    import ModalParamsComponent from "@component/Editor/field/modals/modalParamsComponent";

    export default {
        name: "fieldComponent",
        components: {ModalParamsComponent, ContextMenuComponent, NavbarComponent, blockComponent},
        methods: {
            ...mapMutations([
                'setSchemaField',
                'setEndConnector',
                'removeConnector',
            ]),

            handleMouseMove (e) {
                if (this.isConnectorDragging) {
                    this.setEndConnector(e);
                }
            },

            handleMouseUp () {
                if (this.isConnectorDragging) {
                    this.removeConnector()
                }
            },
        },

        computed: {
            ...mapGetters([
                'blocks',
                'isConnectorDragging',
            ]),
        },

        mounted() {
            this.setSchemaField();
        },
    }
</script>

<style scoped>

</style>
