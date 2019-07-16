<template>
    <div>
        <base-block-component :block="block" :blockClass="blockClass" editable @toEdit="showModal" />

        <div ref="editModal" class="modal fade text-black-50 unhandle" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            Compare param
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="saveMessage" autocomplete="off">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Param</label>
                                <div class="col-sm-9">
                                    <select-param-component v-model="form.param_id" />
                                </div>
                            </div>
                            <div v-if="paramType !== 'boolean'" class="form-group row">
                                <label for="value_sign" class="col-sm-3 col-form-label">Sign</label>
                                <div class="col-sm-9">
                                    <select v-model="form.value_sign" class="form-control" id="value_sign">
                                        <option v-for="sign in SIGNS" :value="sign">
                                            {{ sign }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div v-if="paramType !== 'boolean'" class="form-group row">
                                <label class="col-sm-3 col-form-label">Value param</label>
                                <div class="col-sm-9">
                                    <select-param-component v-model="form.value_param_id" />
                                </div>
                            </div>
                            <div v-if="paramType === 'string'" class="form-group row">
                                <label for="value_string" class="col-sm-3 col-form-label">Value String</label>
                                <div class="col-sm-9">
                                    <input v-model="form.value_string" id="value_string"  class="form-control" placeholder="Input string">
                                </div>
                            </div>
                            <div v-if="paramType === 'integer'" class="form-group row">
                                <label for="value_integer" class="col-sm-3 col-form-label">Value Integer</label>
                                <div class="col-sm-9">
                                    <input v-model="form.value_integer" id="value_integer" type="number" class="form-control" placeholder="Input integer">
                                </div>
                            </div>
                            <div v-if="paramType === 'time'" class="form-group row">
                                <label for="value_integer" class="col-sm-3 col-form-label">Value Time</label>
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
                            <div v-if="paramType === 'date'" class="form-group row">
                                <label for="value_integer" class="col-sm-3 col-form-label">Value Date</label>
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
                            <div v-if="paramType === 'datetime'" class="form-group row">
                                <label for="value_integer" class="col-sm-3 col-form-label">Value Datetime</label>
                                <div class="col-sm-9">
                                    <date-time-picker v-model="form.value_datetime"
                                                      label="Select time & date"
                                                      dark locale="en"
                                                      no-label no-header no-button-now
                                    />
                                </div>
                            </div>
                            <div v-if="['time', 'date', 'datetime'].includes(paramType)" class="form-group row">
                                <label for="date_precision" class="col-sm-3 col-form-label">Value Datetime</label>
                                <div class="col-sm-9">
                                    <select v-model="form.date_precision" id="date_precision" class="form-control">
                                        <option>Not selected</option>
                                        <option v-for="precision in PRECISIONS" :value="precision">
                                            {{ precision }}
                                        </option>
                                    </select>
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
    import ParamCompare from "@model/Blocks/ParamCompare";
    import baseBlockComponent from "@component/Editor/baseBlockComponent";
    import blockParamComponent from "@component/Editor/blockParamComponent";

    import SelectParamComponent from "@component/Editor/shared/selectParamComponent";

    import DateTimePicker from 'vue-ctk-date-time-picker'
    import 'vue-ctk-date-time-picker/dist/vue-ctk-date-time-picker.css';


    import {createNamespacedHelpers} from 'vuex'
    const {mapActions, mapState} = createNamespacedHelpers('editor');

    import { get } from 'lodash'

    const SIGNS = ['=', '<', '<=', '>', '>='];
    const PRECISIONS = ['minute', 'hour', 'day'];

    export default {
        name: "paramCompareBlockComponent",

        props: {
            block: Block,
            blockClass: ParamCompare,
        },
        components: {SelectParamComponent, baseBlockComponent, blockParamComponent, DateTimePicker,},

        data: () => ({
            SIGNS,
            PRECISIONS,
            form: {
                param_id: null,
                value_sign: '=',
                value_param_id: null,
                value_string: null,
                value_integer: null,
                value_time: null,
                value_date: null,
                value_datetime: null,
                date_precision: 'minute',
            }
        }),

        computed: {
            ...mapState([
                'variables',
            ]),

            paramType () {
                return get(this.variables.find(variable => variable.id === Number(this.form.param_id)), 'type')
            },
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
                value_sign: this.block.get('data.value_sign'),
                value_param_id: this.block.get('data.value_param_id'),
                value_string: this.block.get('data.value_string'),
                value_integer: this.block.get('data.value_integer'),
                value_time: this.block.get('data.value_time'),
                value_date: this.block.get('data.value_date'),
                date_precision: this.block.get('data.date_precision'),
            }
        },

        beforeDestroy() {
            this.hideModal()
        },
    }
</script>

<style scoped>

</style>
