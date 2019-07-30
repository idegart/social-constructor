<template>
    <div id="sendMessageWithInputModal" class="modal fade text-black-50 unhandle" tabindex="-1" role="dialog" aria-hidden="true">
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
                    <button @click="hideBlockModal" type="button" class="btn btn-secondary">Close</button>
                    <button @click="saveMessage" type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import AtTa from 'vue-at/dist/vue-at-textarea'

    import {createNamespacedHelpers} from 'vuex'
    const { mapMutations, mapState } = createNamespacedHelpers('editor');

    export default {
        name: "sendMessageWithInputModal",

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
                this.block.set({data: this.form});
                this.block.save();

                this.hideBlockModal()
            },

        },

        created() {
            this.form = {
                message: this.block.get('data.message'),
                param_id: this.block.get('data.param_id'),
            }
        },
    }
</script>

<style scoped>

</style>
