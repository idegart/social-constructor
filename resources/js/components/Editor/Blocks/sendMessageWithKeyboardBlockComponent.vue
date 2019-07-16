<template>
    <div>
        <base-block-component :block="block" :blockClass="blockClass" editable @toEdit="showModal('editTextModal')">

            <template slot="dropdown">
                <button v-if="block.get('data.buttons', []).length < 4"
                        @click="showModal('addButtonModal')"
                        class="dropdown-item">
                    <i class="fas fa-plus-square mr-1"></i>
                    Add button
                </button>
            </template>

        </base-block-component>

        <div ref="editTextModal" class="modal fade text-black-50 unhandle" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            Edit message
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="saveMessage" autocomplete="off">
                            <div class="form-group row">
                                <label for="message" class="col-sm-2 col-form-label">Message</label>
                                <div class="col-sm-10">
                                    <at-ta at="@" :members="params" >
                                        <textarea v-model="message"
                                                  class="form-control"
                                                  id="message"
                                                  placeholder="Enter message">
                                        </textarea>
                                    </at-ta>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button @click="hideModal('editTextModal')" type="button" class="btn btn-secondary">Close</button>
                        <button @click="saveMessage" type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>

        <div ref="addButtonModal" class="modal fade text-black-50 unhandle" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            Add button
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="saveButton" autocomplete="off">
                            <div class="form-group row">
                                <label for="label" class="col-sm-2 col-form-label">Button</label>
                                <div class="col-sm-10">
                                    <input v-model="label" id="label" class="form-control" placeholder="Enter button label" />
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button @click="hideModal('addButtonModal')" type="button" class="btn btn-secondary">Close</button>
                        <button @click="saveButton" type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Block from '@model/Block'
    import SendMessageWithKeyboard from "@model/Blocks/SendMessageWithKeyboard";
    import baseBlockComponent from "@component/Editor/baseBlockComponent";
    import blockParamComponent from "@component/Editor/blockParamComponent";

    import {createNamespacedHelpers} from 'vuex'
    const {mapActions, mapState} = createNamespacedHelpers('editor');

    import AtTa from 'vue-at/dist/vue-at-textarea'

    export default {
        name: "sendMessageWithKeyboardBlockComponent",

        props: {
            block: Block,
            blockClass: SendMessageWithKeyboard,
        },
        components: {baseBlockComponent, blockParamComponent, AtTa},

        data: () => ({
            message: '',
            label: '',
        }),

        computed: {
            ...mapState([
                'variables',
            ]),

            params () {
                return this.variables.map(variable => variable.variable)
            },
        },

        methods: {
            saveMessage () {
                this.block.set({data: {message: this.message}})
                this.block.save()

                this.hideModal('editTextModal')
            },

            saveButton () {
                this.block.set({data: {button_store: this.label}})
                this.block.save()

                this.hideModal('addButtonModal')
            },

            showModal (modal) {
                $(this.$refs[modal]).modal('show')
            },

            hideModal (modal) {
                $(this.$refs[modal]).modal('hide')
            },
        },

        mounted() {
            this.message = this.block.get('data.message')
        },

        beforeDestroy() {
            this.hideModal()
        },
    }
</script>

<style scoped>

</style>
