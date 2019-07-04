<template>
    <div>
        <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#storeScriptModal">
            Add new
            <i class="fas fa-plus"></i>
        </button>

        <div class="modal fade" id="storeScriptModal" tabindex="-1" role="dialog" aria-labelledby="storeScriptModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="storeScriptModalLabel">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="submitScript" autocomplete="off">
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input v-model="form.title"
                                       class="form-control"
                                       :class="getValidationClass('form.title')"
                                       id="title"
                                       placeholder="Enter title">
                                <validation-errors :$v="$v" attribute="form.title" />
                            </div>
                            <div class="form-group form-check">
                                <input v-model="form.accept"
                                       type="checkbox"
                                       class="form-check-input"
                                       :class="getValidationClass('form.accept')"
                                       id="exampleCheck1">
                                <label class="form-check-label" for="exampleCheck1">Check me out</label>
                                <validation-errors :$v="$v" attribute="form.accept" />
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                        <button @click="submitScript" type="submit" class="btn btn-primary" :disabled="loading">
                            <span v-if="loading" class="spinner-border spinner-border-sm"
                                  style="vertical-align: sub"
                                  role="status" aria-hidden="true"></span>
                            Save changes
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { required, minLength, maxLength, } from 'vuelidate/lib/validators'
    import { accepted } from '@plugin/validationRules'
    import validationMixin from "@js/mixins/validationMixin";

    import { apiAxios } from '@plugin/axios'



    export default {
        name: "storeScriptComponent",
        mixins: [validationMixin],

        validations: {
            form: {
                title: {
                    required,
                    minLength: minLength(2),
                    maxLength: maxLength(24),
                },
                accept: {
                    required,
                    accepted,
                }
            }
        },

        data: () => ({
            loading: false,

            form: {
                title: '',
                accept: false,
            }
        }),

        methods: {
            submitScript () {
                this.$v.$touch()

                if (this.$v.$invalid) {
                    return
                }

                this.loading = true;

                apiAxios.post('/scripts/', this.form)
                    .then(() => {
                        window.location.reload();
                    })
                    .finally(() => {
                        this.loading = false
                    })
            }
        }
    }
</script>

<style scoped>

</style>
