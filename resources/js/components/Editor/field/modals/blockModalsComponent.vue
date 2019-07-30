<template>
    <div>
        <component ref="modalComponent"
                   :is="currentModal"
                   :block="block"
        />
    </div>
</template>

<script>
    import {camelCase} from 'lodash'

    import {createNamespacedHelpers} from 'vuex'
    const { mapState, mapMutations } = createNamespacedHelpers('editor');

    const context = require.context('./blocks', false, /\.vue$/i);
    const components = context.keys().reduce((modules, path) => ({
        ...modules,
        [/[a-z]+/i.exec(path).pop()]: context(path).default,
    }), {});

    export default {
        name: "blockModalsComponent",

        components: {
            ...components
        },

        computed: {
            ...mapState([
                'blockModal',
            ]),

            block () {
                return this.blockModal.block
            },

            currentModal () {
                if (!this.blockModal.id) {
                    return null
                }

                return camelCase(this.blockModal.id)
            }
        },

        methods: {
            ...mapMutations([
                'hideBlockModal',
            ]),
        },

        watch: {
            blockModal: {
                handler (modal) {
                    if (modal.block) {
                        this.$nextTick(() => {
                            $(this.$refs.modalComponent.$el)
                                .modal('show')
                                .on('hidden.bs.modal', (e) => {
                                    this.hideBlockModal();
                                })
                        });
                        return
                    }

                    $(this.$refs.modalComponent.$el).modal('hide')
                },
                deep: true,
            }
        }
    }
</script>

<style scoped>

</style>
