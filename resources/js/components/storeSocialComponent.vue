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
                        <h5 class="modal-title" id="storeScriptModalLabel">Add new social channel</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="submitScript" autocomplete="off">
                            <div class="form-group">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input v-model="form.type"
                                           value="vkontakte"
                                           type="radio"
                                           id="socialVk"
                                           name="customRadioInline1"
                                           class="custom-control-input">
                                    <label class="custom-control-label" for="socialVk">Vkontakte</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input v-model="form.type"
                                           value="telegram"
                                           type="radio"
                                           id="socialTg"
                                           name="customRadioInline1"
                                           class="custom-control-input">
                                    <label class="custom-control-label" for="socialTg">Telegram</label>
                                </div>
                            </div>
                            <div v-if="form.type === 'vkontakte'">
                                <div class="form-group">
                                    <label for="title">Group ID</label>
                                    <input v-model="form.vk_group_id"
                                           class="form-control"
                                           :class="getValidationClass('form.vk_group_id')"
                                           id="title"
                                           placeholder="Enter title">
                                    <validation-errors :$v="$v" attribute="form.vk_group_id" />
                                </div>
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
    import { required, numeric, requiredIf } from 'vuelidate/lib/validators'
    import { accepted } from '@plugin/validationRules'
    import validationMixin from "@js/mixins/validationMixin";

    import { apiAxios } from '@plugin/axios'

    export default {
        name: "storeSocialComponent",
        mixins: [validationMixin],

        validations: {
            form: {
                type: {
                    required,
                },
                vk_group_id: {
                    required: requiredIf(function () {
                        return this.type !== 'vkontakte'
                    }),
                    numeric,
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
                type: 'vkontakte',
                vk_group_id: '',
                accept: false
            }
        }),

        methods: {
            submitScript () {
                this.$v.$touch()

                if (this.$v.$invalid) {
                    return
                }

                this.loading = true;

                apiAxios.post('/socialChannels/', this.form)
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
