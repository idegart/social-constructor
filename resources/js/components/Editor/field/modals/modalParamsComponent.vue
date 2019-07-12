<template>
    <div class="modal fade" id="modalParams" tabindex="-1" role="dialog" aria-labelledby="modalParamsLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
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
                        <tr>
                            <th scope="row">1</th>
                            <td>Mark</td>
                            <td>Otto</td>
                            <td>@mdo</td>
                            <td>@mdo</td>
                            <td class="align-middle">
                                <button class="btn btn-sm btn-outline-danger">X</button>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row" class="text-primary align-middle">
                                <i class="far fa-plus-square"></i>
                            </th>
                            <td>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">@</span>
                                    </div>
                                    <input v-model="form.variable"
                                           class="form-control"
                                           style="text-transform: uppercase"
                                           placeholder="Variable">
                                </div>
                            </td>
                            <td>
                                <select v-model="form.type"
                                        class="form-control">
                                    <option v-for="type in variableTypes">
                                        {{ type }}
                                    </option>
                                </select>
                            </td>
                            <td>
                                <input v-model="form.validation" class="form-control">
                            </td>
                            <td>
                                <input v-model="form.default" class="form-control">
                            </td>
                            <td class="align-middle">
                                <button @click="addVariable" class="btn btn-primary btn-sm">+</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {cloneDeep} from 'lodash'

    import validationMixin from "@js/mixins/validationMixin";
    import { required, minLength, maxLength, alpha } from 'vuelidate/lib/validators'

    const PARAM_FORM = {
        variable: '',
        type: 'string',
        validation: '',
        default: '',
    };

    export default {
        name: "modalParamsComponent",
        mixins: [validationMixin],

        data: () => ({
            variableTypes: [
                'string',
                'integer',
                'boolean',
            ],
            form: null
        }),

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
            addVariable () {
                this.$v.$touch()

                if (this.$v.$invalid) {
                    return
                }

                console.log(this.form)

                this.clearForm()
            },

            clearForm() {
                this.form = cloneDeep(PARAM_FORM)
            }
        },

        created() {
            this.clearForm()
        }
    }
</script>

<style scoped>

</style>
