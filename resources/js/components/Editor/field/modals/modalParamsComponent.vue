<template>
    <div class="modal fade" id="modalParams" tabindex="-1" role="dialog" aria-labelledby="modalParamsLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title" id="modalParamsLabel">Params</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0">
                    <table class="table table-bordered m-0">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Variable</th>
                            <th scope="col">Type</th>
                            <th scope="col">Validation</th>
                            <th scope="col">Default</th>
                            <th scope="col"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(variable, index) in variables">
                            <th scope="row">{{ index + 1 }}</th>
                            <td>{{ variable.variable }}</td>
                            <td class="text-center">
                                <span v-if="variable.type === 'string'" class="badge badge-warning">String</span>
                                <span v-else-if="variable.type === 'integer'" class="badge badge-primary">Integer</span>
                                <span v-else-if="variable.type === 'boolean'" class="badge badge-dark">Boolean</span>
                                <span v-else-if="variable.type === 'date'" class="badge badge-info">Date</span>
                                <span v-else-if="variable.type === 'time'" class="badge badge-info">Time</span>
                                <span v-else-if="variable.type === 'datetime'" class="badge badge-info">Datetime</span>
                            </td>
                            <td class="text-center">{{ variable.validation}}</td>
                            <td class="text-center">{{ variable.default}}</td>
                            <td class="align-middle">
                                <button @click="removeParam(variable.id)" class="btn btn-sm btn-outline-danger">X</button>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row" class="text-primary align-middle">
                                <i class="far fa-plus-square"></i>
                            </th>
                            <td style="width: 200px">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">@</span>
                                    </div>
                                    <input v-model="form.variable"
                                           class="form-control"
                                           :class="getValidationClass('form.variable')"
                                           style="text-transform: uppercase"
                                           placeholder="Variable">
                                    <validation-errors :$v="$v" attribute="form.variable" />
                                </div>
                            </td>
                            <td>
                                <select v-model="form.type"
                                        class="form-control"
                                        :class="getValidationClass('form.type')">
                                    <option v-for="type in variableTypes">
                                        {{ type }}
                                    </option>
                                </select>
                                <validation-errors :$v="$v" attribute="form.type" />
                            </td>
                            <td>
                                <input v-if="['string','integer'].includes(form.type)" v-model="form.validation" class="form-control">
                            </td>
                            <td>
                                <input v-if="form.type === 'string'" v-model="form.default_string" class="form-control">
                                <input v-if="form.type === 'integer'" v-model.number="form.default_integer" type="number" class="form-control">
                                <div v-if="form.type === 'boolean'" class="custom-control custom-switch text-center">
                                    <input v-model="form.default_boolean" type="checkbox" class="custom-control-input" id="customSwitch1" value="1">
                                    <label class="custom-control-label" for="customSwitch1"></label>
                                </div>
                                <date-time-picker v-if="form.type === 'time'"
                                                  v-model="form.default_time"
                                                  label="Select time"
                                                  format="hh:mm a"
                                                  formatted="LT"
                                                  only-time dark locale="en"
                                                  no-label no-header no-button-now
                                />
                                <date-time-picker v-if="form.type === 'date'"
                                                  v-model="form.default_date"
                                                  label="Select date"
                                                  format="YYYY-MM-DD"
                                                  formatted="L"
                                                  only-date dark locale="en"
                                                  no-label no-header no-button-now
                                />
                                <date-time-picker v-if="form.type === 'datetime'"
                                                  v-model="form.default_datetime"
                                                  label="Select time & date"
                                                  dark locale="en"
                                                  no-label no-header no-button-now
                                />
                            </td>
                            <td class="align-middle">
                                <button @click="addVariable" class="btn btn-primary btn-sm">+</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer border-top-0">
                    <div class="text-danger">
                        <b>Reserved variables:</b>
                        <ul class="list-unstyled">
                            <li>@INPUT</li>
                            <li>@USERNAME</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {cloneDeep} from 'lodash'

    import validationMixin from "@js/mixins/validationMixin";
    import { required, minLength, maxLength, alpha, } from 'vuelidate/lib/validators'

    import {createNamespacedHelpers} from 'vuex'
    const {mapActions, mapState} = createNamespacedHelpers('editor');

    import DateTimePicker from 'vue-ctk-date-time-picker'
    import 'vue-ctk-date-time-picker/dist/vue-ctk-date-time-picker.css';

    const PARAM_FORM = {
        variable: '',
        type: '',
        validation: null,
        default_string: null,
        default_integer: null,
        default_boolean: null,
        default_date: null,
        default_time: null,
        default_datetime: null,
    };

    export default {
        name: "modalParamsComponent",
        mixins: [validationMixin],

        components: {
            DateTimePicker,
        },

        data: () => ({
            variableTypes: [
                'string',
                'integer',
                'boolean',
                'date',
                'time',
                'datetime',
            ],
            form: null
        }),

        computed: {
            ...mapState([
                'variables',
            ]),
        },

        validations: {
            form: {
                variable: {
                    required,
                    alpha,
                    minLength: minLength(2),
                    maxLength: maxLength(16),
                },
                type: {
                    required,
                },
                validation: {},
                default: {},
            }
        },

        methods: {
            ...mapActions([
                'storeParam',
                'removeParam',
            ]),
            addVariable () {
                this.$v.$touch()

                if (this.$v.$invalid) {
                    return
                }

                let data = {...this.form}

                if (data.type === 'boolean') {
                    data.default = data.default ? '1' : '0'
                }

                this.storeParam(data)
                    .then(() => {
                        this.$v.$reset()
                        this.clearForm()
                    })
            },

            clearForm() {
                this.form = cloneDeep(PARAM_FORM)
            }
        },

        created() {
            this.clearForm()
        },

        watch: {
            'form.type' () {
                this.form.validation = null

                this.form.default_string = null
                this.form.default_integer = null
                this.form.default_boolean = null
                this.form.default_date = null
                this.form.default_time = null
                this.form.default_datetime = null
            }
        }
    }
</script>

<style scoped>

</style>
