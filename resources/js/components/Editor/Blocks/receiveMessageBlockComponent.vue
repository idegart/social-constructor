<template>
    <div>
        <base-block-component :block="block" :blockClass="blockClass">

            <template slot="footer">
                <div class="text-center p-1">
                    <button @click="showModal" class="btn btn-sm btn-primary unhandle">
                        Message
                    </button>
                </div>
            </template>

        </base-block-component>

        <div ref="editTextModal" class="modal fade text-black-50 unhandle" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
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
                                    <input v-model="message" class="form-control" id="message" placeholder="Enter nessage">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button @click="hideModal" type="button" class="btn btn-secondary">Close</button>
                        <button @click="saveMessage" type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Block from '@model/Block'
    import ReceiveMessage from "@model/Blocks/ReceiveMessage";
    import baseBlockComponent from "@component/Editor/baseBlockComponent";
    import blockParamComponent from "@component/Editor/blockParamComponent";

    export default {
        name: "receiveMessageBlockComponent",

        props: {
            block: Block,
            blockClass: ReceiveMessage,
        },
        components: {baseBlockComponent, blockParamComponent},

        data: () => ({
            message: ''
        }),

        methods: {
            saveMessage () {
                this.block.set({data: {message: this.message}})
                this.block.save()

                this.hideModal()
            },

            showModal () {
                $(this.$refs.editTextModal).modal('show')
            },

            hideModal () {
                $(this.$refs.editTextModal).modal('hide')
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
