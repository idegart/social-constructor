<template>
    <div class="modal fade text-black-50" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        Dialogflow
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="saveExternalApi" autocomplete="off">
                        <div class="form-group row">
                            <label for="access_token" class="col-sm-3 col-form-label">Access Token</label>
                            <div class="col-sm-9">
                                <input v-model="form.access_token" id="access_token" class="form-control" placeholder="Enter access token" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="is_initial" class="col-sm-3 col-form-label">Initial</label>
                            <div class="col-sm-9">
                                <div class="custom-control custom-checkbox">
                                    <input v-model="form.is_initial" type="checkbox" class="custom-control-input" id="is_initial">
                                    <label class="custom-control-label" for="is_initial">Check if this is initial block</label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button @click="hideBlockModal" type="button" class="btn btn-secondary">Close</button>
                    <button @click="saveExternalApi" type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {createNamespacedHelpers} from 'vuex'
    const { mapMutations, mapState } = createNamespacedHelpers('editor');

    export default {
        name: "dialogflowModal",

        props: ['block'],

        data: () => ({
            form: null,
        }),

        computed: {
            ...mapState([
                'externalAPI',
            ]),
        },

        methods: {
            ...mapMutations([
                'hideBlockModal',
            ]),

            saveExternalApi () {
                this.block.set({data: {...this.form}});
                this.block.save()
                    .then(() => {
                        this.hideBlockModal()
                    })
                    .catch(({response}) => {
                        alert(response.response.data.message)
                    })
            },
        },

        created() {
            this.form = {
                is_initial: this.block.get('data.is_initial'),
                access_token: this.block.get('data.access_token'),
            }
        },
    }
</script>

<style scoped>

</style>
