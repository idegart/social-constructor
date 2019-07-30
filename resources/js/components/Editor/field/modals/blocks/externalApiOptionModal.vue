<template>
    <div id="externalAPIOptionModal" class="modal fade text-black-50" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        Add option
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="saveOption" autocomplete="off">
                        <div class="form-group row">
                            <label for="label" class="col-sm-2 col-form-label">Key</label>
                            <div class="col-sm-10">
                                <input v-model="form.key" id="label" class="form-control" placeholder="Enter option key" />
                                <small class="form-text text-muted">What server should return to continue script. The field may have alpha-numeric characters, as well as dashes and underscores</small>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button @click="hideBlockModal" type="button" class="btn btn-secondary">Close</button>
                    <button @click="saveOption" type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {createNamespacedHelpers} from 'vuex'
    const { mapMutations } = createNamespacedHelpers('editor');

    export default {
        name: "externalApiOptionModal",

        props: ['block'],

        data: () => ({
            form: null,
        }),

        methods: {
            ...mapMutations([
                'hideBlockModal',
            ]),

            saveOption () {
                this.block.set({data: {option_store: this.form.key}});
                this.block.save();

                this.hideBlockModal()
            },
        },

        created() {
            this.form = {
                key: '',
            }
        },
    }
</script>

<style scoped>

</style>
