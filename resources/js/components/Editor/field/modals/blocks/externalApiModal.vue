<template>
    <div class="modal fade text-black-50" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        Edit external API
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="saveExternalApi" autocomplete="off">
                        <div class="form-group row">
                            <label for="api" class="col-sm-2 col-form-label">URL</label>
                            <div class="col-sm-10">
                                <select v-model="form.external_api_id" class="form-control" id="api">
                                    <option :value="null">Select API</option>
                                    <option v-for="api in externalAPI" :value="api.id">
                                        {{ api.title }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="handler" class="col-sm-2 col-form-label">Handler</label>
                            <div class="col-sm-10">
                                <input v-model="form.handler" id="handler" class="form-control" placeholder="Enter handler" />
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
        name: "externalApiModal",

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
                external_api_id: this.block.get('data.external_api_id'),
                handler: this.block.get('data.handler'),
            }
        },
    }
</script>

<style scoped>

</style>
