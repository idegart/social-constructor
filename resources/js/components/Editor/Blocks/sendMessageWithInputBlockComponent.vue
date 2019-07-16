<template>
    <div>
        <base-block-component :block="block" :blockClass="blockClass" editable @toEdit="showModal" />

        <div ref="editModal" class="modal fade text-black-50 unhandle" tabindex="-1" role="dialog" aria-hidden="true">
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
                                        <textarea v-model="form.message"
                                                  class="form-control"
                                                  id="message"
                                                  placeholder="Enter message">
                                        </textarea>
                                    </at-ta>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="param" class="col-sm-2 col-form-label">Param</label>
                                <div class="col-sm-10">
                                    <select v-model="form.param_id" class="form-control" id="param">
                                        <option v-for="variable in variables" :value="variable.id">
                                            {{ variable.variable }} ({{ variable.type }})
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button @click="hideModal()" type="button" class="btn btn-secondary">Close</button>
                        <button @click="saveMessage" type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Block from '@model/Block'
    import SendMessageWithInput from "@model/Blocks/SendMessageWithInput";
    import baseBlockComponent from "@component/Editor/baseBlockComponent";
    import blockParamComponent from "@component/Editor/blockParamComponent";

    import {createNamespacedHelpers} from 'vuex'
    const {mapActions, mapState} = createNamespacedHelpers('editor');

    import AtTa from 'vue-at/dist/vue-at-textarea'

    export default {
        name: "sendMessageWithInputBlockComponent",

        props: {
            block: Block,
            blockClass: SendMessageWithInput,
        },
        components: {baseBlockComponent, blockParamComponent, AtTa},

        data: () => ({
            form: {
                message: '',
                param_id: null,
            }
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
                this.block.set({data: {...this.form}})
                this.block.save()

                this.hideModal()
            },

            showModal () {
                $(this.$refs.editModal).modal('show')
            },

            hideModal () {
                $(this.$refs.editModal).modal('hide')
            },
        },

        mounted() {
            this.form.message = this.block.get('data.message')
            this.form.param_id = this.block.get('data.param_id')
        },

        beforeDestroy() {
            this.hideModal()
        },
    }
</script>

<style scoped>

</style>
