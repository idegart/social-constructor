<template>
    <div>
        <base-block-component :block="block" :blockClass="blockClass" editable @toEdit="showModal('editModal')" needFooter>

            <template slot="dropdown">
                <button v-if="block.get('data.options', []).length < 4"
                        @click="showModal('addOptionModal')"
                        class="dropdown-item">
                    <i class="fas fa-plus-square mr-1"></i>
                    Add option
                </button>
            </template>

            <template slot="footer">
                <div class="p-1">
                    <ul class="list-unstyled m-0">
                        <li>API: <span v-if="block.get('data.url')" class="text-success">{{truncate(block.get('data.url').split('').reverse().join(''), {'length': 25}).split('').reverse().join('')}}</span></li>
                        <li>Handler: <span class="text-success">{{truncate(block.get('data.handler'), {'length': 15})}}</span></li>
                    </ul>
                </div>
            </template>

        </base-block-component>

        <div ref="editModal" class="modal fade text-black-50 unhandle" tabindex="-1" role="dialog" aria-hidden="true">
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
                                <label for="url" class="col-sm-2 col-form-label">URL</label>
                                <div class="col-sm-10">
                                    <input v-model="form.url" id="url" class="form-control" placeholder="Enter api URL" />
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
                        <button @click="hideModal('editModal')" type="button" class="btn btn-secondary">Close</button>
                        <button @click="saveExternalApi" type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>

        <div ref="addOptionModal" class="modal fade text-black-50 unhandle" tabindex="-1" role="dialog" aria-hidden="true">
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
                                    <input v-model="optionForm.key" id="label" class="form-control" placeholder="Enter option key" />
                                    <small class="form-text text-muted">What server should return to continue script. The field may have alpha-numeric characters, as well as dashes and underscores</small>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button @click="hideModal('addOptionModal')" type="button" class="btn btn-secondary">Close</button>
                        <button @click="saveOption" type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {truncate, get} from 'lodash'

    import Block from '@model/Block'
    import ExternalApi from "@model/Blocks/ExternalApi";
    import baseBlockComponent from "@component/Editor/baseBlockComponent";
    import blockParamComponent from "@component/Editor/blockParamComponent";

    export default {
        name: "externalApiBlockComponent",

        props: {
            block: Block,
            blockClass: ExternalApi,
        },
        components: {baseBlockComponent, blockParamComponent},

        data: () => ({
            form: null,
            optionForm: null,
        }),

        methods: {
            get,
            truncate,
            showModal (modal) {
                $(this.$refs[modal]).modal('show')
            },

            hideModal (modal) {
                $(this.$refs[modal]).modal('hide')
            },

            saveExternalApi () {
                this.block.set({data: {...this.form}});
                this.block.save()
                    .then(() => {
                        this.hideModal('editModal')
                    })
                    .catch(({response}) => {
                        alert(response.response.data.message)
                    })
            },

            saveOption () {
                this.block.set({data: {option_store: this.optionForm.key}});
                this.block.save();

                this.hideModal('addOptionModal')
            },

            setDefaultForm () {
                this.form = {
                    url: this.block.get('data.url'),
                    handler: this.block.get('data.handler'),
                }
            },

            setDefaultOptionForm () {
                this.optionForm = {
                    key: '',
                }
            }
        },

        created() {
            this.setDefaultForm();
            this.setDefaultOptionForm()
        },

        beforeDestroy() {
            this.hideModal('addOptionModal')
        },
    }
</script>

<style scoped>

</style>
