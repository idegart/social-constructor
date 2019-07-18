<template>
    <div>
        <base-block-component :block="block" :blockClass="blockClass" editable @toEdit="showModal" />

        <div ref="editModal" class="modal fade text-black-50 unhandle" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            Edit set param
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="saveMessage" autocomplete="off">
                            <div class="form-group row">
                                <label for="first_param" class="col-sm-3 col-form-label">Param</label>
                                <div class="col-sm-9">
                                    <select v-model="form.param_id" class="form-control" id="first_param">
                                        <option v-for="variable in variables" :value="variable.id" class="form-control">
                                            {{ variable.variable }} ({{ variable.type }})
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div v-if="selectedParam" class="form-group row">
                                <label for="value_param" class="col-sm-3 col-form-label">Set param</label>
                                <div class="col-sm-9">
                                    <select v-model="form.value_param_id" class="form-control" id="value_param">
                                        <option v-for="variable in acceptedVariables" :value="variable.id" class="form-control">
                                            {{ variable.variable }} ({{ variable.type }})
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div v-if="paramType === 'boolean'" class="form-group row">
                                <label for="value_boolean" class="col-sm-3 col-form-label">Set boolean</label>
                                <div class="col-sm-9 d-flex align-items-center">
                                    <div class="custom-control custom-switch">
                                        <input v-model="form.value_boolean" type="checkbox" class="custom-control-input" id="value_boolean">
                                        <label class="custom-control-label" for="value_boolean"></label>
                                    </div>
                                </div>
                            </div>
                            <div v-if="paramType === 'string'" class="form-group row">
                                <label for="value_string" class="col-sm-3 col-form-label">Set string</label>
                                <div class="col-sm-9">
                                    <input v-model="form.value_string" type="text" id="value_string" class="form-control">
                                </div>
                            </div>
                            <div v-if="paramType === 'integer'" class="form-group row">
                                <label for="value_int" class="col-sm-3 col-form-label">Set int</label>
                                <div class="col-sm-9">
                                    <input v-model="form.value_integer" type="number" id="value_int" class="form-control">
                                </div>
                            </div>
                            <div v-if="paramType === 'date'" class="form-group row">
                                <label for="value_int" class="col-sm-3 col-form-label">Set date</label>
                                <div class="col-sm-9">
                                    <date-time-picker v-model="form.value_date"
                                                      label="Select date"
                                                      format="YYYY-MM-DD"
                                                      formatted="L"
                                                      only-date dark locale="en"
                                                      no-label no-header no-button-now
                                    />
                                </div>
                            </div>
                            <div v-if="paramType === 'time'" class="form-group row">
                                <label for="value_int" class="col-sm-3 col-form-label">Set time</label>
                                <div class="col-sm-9">
                                    <date-time-picker v-model="form.value_time"
                                                      label="Select time"
                                                      format="hh:mm a"
                                                      formatted="LT"
                                                      only-time dark locale="en"
                                                      no-label no-header no-button-now
                                    />
                                </div>
                            </div>
                            <div v-if="paramType === 'datetime'" class="form-group row">
                                <label for="value_int" class="col-sm-3 col-form-label">Set datetime</label>
                                <div class="col-sm-9">
                                    <date-time-picker v-model="form.value_datetime"
                                                      label="Select time & date"
                                                      dark locale="en"
                                                      no-label no-header no-button-now
                                    />
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button @click="hideModal" type="button" class="btn btn-secondary">Close</button>
                        <button @click="saveMessage" type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Block from '@model/Block'
    import ParamSet from "@model/Blocks/ParamSet";
    import baseBlockComponent from "@component/Editor/baseBlockComponent";
    import blockParamComponent from "@component/Editor/blockParamComponent";

    import {get} from 'lodash'

    import {createNamespacedHelpers} from 'vuex'
    const {mapState} = createNamespacedHelpers('editor');

    import DateTimePicker from 'vue-ctk-date-time-picker'
    import 'vue-ctk-date-time-picker/dist/vue-ctk-date-time-picker.css';

    export default {
        name: "paramSetBlockComponent",

        props: {
            block: Block,
            blockClass: ParamSet,
        },
        components: {baseBlockComponent, blockParamComponent, DateTimePicker},

        data: () => ({
            form: {
                param_id: null,
                value_param_id: null,
                value_boolean: null,
                value_string: null,
                value_integer: null,
                value_date: null,
                value_time: null,
                value_datetime: null,
            }
        }),

        computed: {
            ...mapState([
                'variables',
            ]),

            selectedParam () {
                return this.variables.find(variable => variable.id === this.form.param_id);
            },

            paramType () {
                return get(this.selectedParam, 'type')
            },

            acceptedVariables () {
                return this.variables.filter(variable => variable.type === this.paramType && variable.id !== this.selectedParam.id)
            }
        },

        methods: {
            saveMessage () {
                this.block.set({data: {...this.form}})
                this.block.save()

                this.hideModal()
            },

            showModal () {
                $(this.$refs.editModal).modal('show')
            },

            hideModal () {
                $(this.$refs.editModal).modal('hide')
            },
        },

        mounted() {
            this.form = {
                param_id: this.block.get('data.param_id'),
                value_param_id: this.block.get('data.value_param_id'),
                value_boolean: this.block.get('data.value_boolean'),
                value_string: this.block.get('data.value_string'),
                value_integer: this.block.get('data.value_integer'),
                value_date: this.block.get('data.value_date'),
                value_time: this.block.get('data.value_time'),
                value_datetime: this.block.get('data.value_datetime'),
            }
        },

        beforeDestroy() {
            this.hideModal()
        },
    }
</script>

<style scoped>

</style>
