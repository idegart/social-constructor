<template>
    <div class="modal fade" id="modalExternalApi" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title" id="modalLabel">External API</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0">
                    <table class="table table-bordered m-0">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Title</th>
                            <th scope="col">URL</th>
                            <th scope="col">Login</th>
                            <th scope="col">Password</th>
                            <th scope="col"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(api, index) in externalAPI">
                            <td>{{ index + 1 }}</td>
                            <td>{{ api.title }}</td>
                            <td>{{ api.url }}</td>
                            <td>{{ api.auth_login }}</td>
                            <td>{{ api.auth_password }}</td>
                            <td class="align-middle">
                                <button @click="removeExternalApi(api.id)" class="btn btn-sm btn-outline-danger">X</button>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row" class="text-primary align-middle">
                                <i class="far fa-plus-square"></i>
                            </th>
                            <td>
                                <input v-model="form.title"
                                       class="form-control"
                                       :class="getValidationClass('form.title')"
                                       placeholder="Title">
                                <validation-errors :$v="$v" attribute="form.title" />
                            </td>
                            <td>
                                <input v-model="form.url"
                                       class="form-control"
                                       :class="getValidationClass('form.url')"
                                       placeholder="URL">
                                <validation-errors :$v="$v" attribute="form.url" />
                            </td>
                            <td>
                                <input v-model="form.auth_login" class="form-control" placeholder="AUTH login">
                            </td>
                            <td>
                                <input v-model="form.auth_password" class="form-control" placeholder="AUTH password">
                            </td>
                            <td class="align-middle">
                                <button @click="addAPI" class="btn btn-primary btn-sm">+</button>
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
    import {createNamespacedHelpers} from 'vuex'

    const { mapState, mapActions } = createNamespacedHelpers('editor');

    import validationMixin from "@js/mixins/validationMixin";
    import { required, url } from 'vuelidate/lib/validators'

    const DEFAULT_FORM = {
        title: '',
        url: '',
        auth_login: '',
        auth_password: '',
    }

    export default {
        name: "modalExternalApiComponent",
        mixins: [validationMixin],

        data: () => ({
            form: {...DEFAULT_FORM}
        }),

        computed: {
            ...mapState([
                'externalAPI',
            ]),
        },

        validations: {
            form: {
                title: {
                    required,
                },
                url: {
                    required,
                    url,
                },
            }
        },

        methods: {
            ...mapActions([
                'storeExternalAPI',
                'removeExternalApi',
            ]),

            addAPI () {
                this.$v.$touch()

                if (this.$v.$invalid) {
                    return
                }

                this.storeExternalAPI(this.form)
                    .then(() => {
                        this.$v.$reset()
                        this.form = {...DEFAULT_FORM}
                    })
            },
        },
    }
</script>

<style scoped>

</style>
