<template>
    <div id="sendMessageWithKeyboardModal" class="modal fade text-black-50" tabindex="-1" role="dialog" aria-hidden="true">
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
                    <button @click="hideBlockModal" type="button" class="btn btn-secondary">Close</button>
                    <button @click="saveMessage" type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {createNamespacedHelpers} from 'vuex'
    const { mapMutations, mapState } = createNamespacedHelpers('editor');

    import AtTa from 'vue-at/dist/vue-at-textarea'

    export default {
        name: "sendMessageWithKeyboardModal",

        components: {
            AtTa,
        },

        props: ['block'],

        data: () => ({
            form: null,
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
            ...mapMutations([
                'hideBlockModal',
            ]),

            saveMessage () {
                this.block.set({data: {message: this.message}})
                this.block.save()

                this.hideBlockModal()
            },
        },

        mounted() {
            this.form = {
                message: this.block.get('data.message')
            }
        },
    }
</script>

<style scoped>

</style>
