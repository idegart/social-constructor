<template>
    <div>
        <base-block-component :block="block" :blockClass="blockClass" editable @toEdit="showModal" />

        <div ref="editTextModal" class="modal fade text-black-50 unhandle" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            Add to param
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
                                        <option :value="null">Select param</option>
                                        <option v-for="variable in acceptedVariables" :value="variable.id" class="form-control">
                                            {{ variable.variable }} ({{ variable.type }})
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="sign" class="col-sm-3 col-form-label">Sign</label>
                                <div class="col-sm-9">
                                    <select v-model="form.value_sign" class="form-control" id="sign">
                                        <option v-for="sign in signs" :value="sign" class="form-control">
                                            {{ sign }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div v-if="secondVariableAccepted" class="form-group row">
                                <label for="second_param" class="col-sm-3 col-form-label">Param to add</label>
                                <div class="col-sm-9">
                                    <select v-model="form.value_param_id" class="form-control" id="second_param">
                                        <option :value="null">Select param</option>
                                        <option v-for="variable in acceptedSecondParam" :value="variable.id" class="form-control">
                                            {{ variable.variable }} ({{ variable.type }})
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div v-if="secondVariableAccepted" class="form-group row">
                                <label for="add_integer" class="col-sm-3 col-form-label">Number</label>
                                <div class="col-sm-9">
                                    <input v-model="form.value_integer" id="add_integer" type="number" class="form-control">
                                </div>
                            </div>

                            <div v-if="isDateSelectAvailable" class="form-group row">
                                <label for="add_days" class="col-sm-3 col-form-label">Days</label>
                                <div class="col-sm-9">
                                    <select v-model="form.value_days" class="form-control" id="add_days">
                                        <option :value="null">Select days</option>
                                        <option v-for="day in 90" :value="day" class="form-control">
                                            {{ day }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div v-if="isTimeSelectAvailable" class="form-group row">
                                <label for="add_hours" class="col-sm-3 col-form-label">Hours</label>
                                <div class="col-sm-9">
                                    <select v-model="form.value_hours" class="form-control" id="add_hours">
                                        <option :value="null">Select hours</option>
                                        <option v-for="day in 23" :value="day" class="form-control">
                                            {{ day }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div v-if="isTimeSelectAvailable" class="form-group row">
                                <label for="add_minutes" class="col-sm-3 col-form-label">Minutes</label>
                                <div class="col-sm-9">
                                    <select v-model="form.value_minutes" class="form-control" id="add_minutes">
                                        <option :value="null">Select minutes</option>
                                        <option v-for="day in 59" :value="day" class="form-control">
                                            {{ day }}
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
    import ParamAdd from "@model/Blocks/ParamAdd";
    import baseBlockComponent from "@component/Editor/baseBlockComponent";
    import blockParamComponent from "@component/Editor/blockParamComponent";

    import {createNamespacedHelpers} from 'vuex'
    const {mapState} = createNamespacedHelpers('editor');

    export default {
        name: "paramAddBlockComponent",

        props: {
            block: Block,
            blockClass: ParamAdd,
        },
        components: {baseBlockComponent, blockParamComponent},

        data: () => ({
            form: {
                param_id: null,
                value_sign: '+',
                value_param_id: null,
                value_integer: null,
                value_days: null,
                value_hours: null,
                value_minutes: null,
            },

            signs: ['+', '-'],
        }),

        computed: {
            ...mapState([
                'variables',
            ]),

            acceptedVariables () {
                return this.variables
                    .filter(variable => {
                        return ['integer', 'date', 'time', 'datetime',].includes(variable.type)
                    })
            },

            acceptedSecondParam () {
                return this.variables
                    .filter(variable => {
                        return ['integer',].includes(variable.type)
                    })
            },

            firstParam () {
                return this.variables.find(variable => variable.id === this.form.param_id);
            },

            secondVariableAccepted() {

                return this.firstParam && this.firstParam.type === 'integer'
            },

            isDateSelectAvailable () {
                return this.firstParam && ['date', 'datetime'].includes(this.firstParam.type)
            },

            isTimeSelectAvailable () {
                return this.firstParam && ['date', 'time', 'datetime'].includes(this.firstParam.type)
            },
        },

        methods: {
            saveMessage () {
                this.block.set({data: {...this.form}})
                this.block.save()

                this.hideModal()
            },

            showModal () {
                $(this.$refs.editTextModal).modal('show')
            },

            hideModal () {
                $(this.$refs.editTextModal).modal('hide')
            },
        },

        mounted() {
            this.form = {
                param_id: this.block.get('data.param_id'),
                value_sign: this.block.get('data.value_sign'),
                value_param_id: this.block.get('data.value_param_id'),
                value_integer: this.block.get('data.value_integer'),
                value_days: this.block.get('data.value_days'),
                value_hours: this.block.get('data.value_hours'),
                value_minutes: this.block.get('data.value_minutes'),
            }
        },

        beforeDestroy() {
            this.hideModal()
        },
    }
</script>

<style scoped>

</style>
