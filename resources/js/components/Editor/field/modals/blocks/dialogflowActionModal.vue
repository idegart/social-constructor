<template>
    <div id="externalAPIOptionModal" class="modal fade text-black-50" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        Add action
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="saveAction" autocomplete="off">
                        <div class="form-group row">
                            <label for="action" class="col-sm-2 col-form-label">Action</label>
                            <div class="col-sm-10">
                                <input v-model="form.action" id="action" class="form-control" placeholder="Enter action" />
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button @click="hideBlockModal" type="button" class="btn btn-secondary">Close</button>
                    <button @click="saveAction" type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {createNamespacedHelpers} from 'vuex'
    const { mapMutations } = createNamespacedHelpers('editor');

    export default {
        name: "dialogflowActionModal",

        props: ['block'],

        data: () => ({
            form: null,
        }),

        methods: {
            ...mapMutations([
                'hideBlockModal',
            ]),

            saveAction () {
                this.block.set({data: {action_store: this.form.action}});
                this.block.save();

                this.hideBlockModal()
            },
        },

        created() {
            this.form = {
                action: '',
            }
        },
    }
</script>

<style scoped>

</style>
