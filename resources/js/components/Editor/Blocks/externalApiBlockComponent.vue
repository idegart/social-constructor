<template>
    <div>
        <base-block-component :block="block" :blockClass="blockClass" editable @toEdit="openModal('externalAPIModal')" needFooter>

            <template slot="dropdown">
                <button v-if="block.get('data.options', []).length < 4"
                        @click="openModal('externalAPIOptionModal')"
                        class="dropdown-item">
                    <i class="fas fa-plus-square mr-1"></i>
                    Add option
                </button>
            </template>

            <template slot="footer">
                <div class="p-1">
                    <ul class="list-unstyled m-0">
                        <li>API: <span class="text-success">{{truncate(apiTitle, {'length': 25})}}</span></li>
                        <li>Handler: <span class="text-success">{{truncate(block.get('data.handler'), {'length': 15})}}</span></li>
                    </ul>
                </div>
            </template>

        </base-block-component>
    </div>
</template>

<script>
    import {truncate, get} from 'lodash'

    import {createNamespacedHelpers} from 'vuex'
    const { mapMutations, mapState } = createNamespacedHelpers('editor');

    import Block from '@model/Block'
    import ExternalApi from "@model/Blocks/ExternalApi";
    import baseBlockComponent from "@component/Editor/baseBlockComponent";
    import blockParamComponent from "@component/Editor/blockParamComponent";

    export default {
        name: "externalApiBlockComponent",

        props: {
            block: Block,
            blockClass: ExternalApi,
        },
        components: {baseBlockComponent, blockParamComponent},

        computed: {
            ...mapState([
                'externalAPI',
            ]),
            apiTitle () {
                let apiId = this.block.get('data.external_api_id');

                if (!apiId) {
                    return null
                }

                let api = this.externalAPI.find(api => api.id === apiId)

                return  api ? api.title : null
            }
        },

        methods: {
            get,
            truncate,
            ...mapMutations([
                'openBlockModal',
            ]),

            openModal (modalName) {
                this.openBlockModal({modalName, block: this.block})
            }
        },
    }
</script>

<style scoped>

</style>
