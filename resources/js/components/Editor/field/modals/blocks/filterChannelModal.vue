<template>
    <div id="receiveMessageModal" class="modal fade text-black-50 unhandle" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        Edit channels
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="saveMessage" autocomplete="off">
                        <div class="form-group row">
                            <label for="channels" class="col-sm-2 col-form-label">Channels</label>
                            <div class="col-sm-10">
                                <input v-model="form.channels" class="form-control" id="channels" placeholder="Enter channels">
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

    export default {
        name: "filterChannelModal",

        props: ['block'],

        data: () => ({
            form: null,
        }),

        methods: {
            ...mapMutations([
                'hideBlockModal',
            ]),

            saveMessage () {
                this.block.set({data: this.form})
                this.block.save()

                this.hideBlockModal()
            },
        },

        created() {
            this.form = {
                channels: this.block.get('data.channels'),
            }
        },
    }
</script>

<style scoped>

</style>
