<template>
    <div id="sendMessageWithKeyboardButtonModal" class="modal fade text-black-50" tabindex="-1" role="dialog" aria-hidden="true">
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
                    <button @click="hideBlockModal" type="button" class="btn btn-secondary">Close</button>
                    <button @click="saveButton" type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {createNamespacedHelpers} from 'vuex'
    const { mapMutations, mapState } = createNamespacedHelpers('editor');

    export default {
        name: "sendMessageWithKeyboardButtonModal",
        props: ['block'],

        data: () => ({
            form: {
                label: '',
            },
        }),

        methods: {
            ...mapMutations([
                'hideBlockModal',
            ]),

            saveButton () {
                this.block.set({data: {button_store: this.label}});
                this.block.save();

                this.hideBlockModal()
            },
        },
    }
</script>

<style scoped>

</style>
