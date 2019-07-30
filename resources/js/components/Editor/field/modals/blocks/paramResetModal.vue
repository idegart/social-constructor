<template>
    <div id="paramResetModal" class="modal fade text-black-50" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        Settings
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="saveMessage" autocomplete="off">
                        <div class="form-group row">
                            <label for="param" class="col-sm-3 col-form-label">Reset all</label>
                            <div class="col-sm-9 d-flex align-items-center">
                                <div class="custom-control custom-switch">
                                    <input v-model="form.reset_all" type="checkbox" class="custom-control-input" id="customSwitch1">
                                    <label class="custom-control-label" for="customSwitch1">
                                        Reset all params
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div v-if="!form.reset_all" class="form-group row">
                            <label for="param" class="col-sm-3 col-form-label">Param</label>
                            <div class="col-sm-9">
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
    import {createNamespacedHelpers} from 'vuex'
    const { mapMutations, mapState } = createNamespacedHelpers('editor');

    import AtTa from 'vue-at/dist/vue-at-textarea'

    const DEFAULT_FORM = {
        reset_all: false,
        param_id: null,
    };

    export default {
        name: "paramResetModal",

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
                message: this.block.get('data.reset_all'),
                param_id: this.block.get('data.param_id')
            }
        },
    }
</script>

<style scoped>

</style>
